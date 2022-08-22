$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Toast
    const container = document.getElementById("kt_docs_toast_stack_container");
    const targetElement = document.querySelector(
        '[data-kt-docs-toast="stack"]'
    );

    $("#kt_modal_add_form").on("hidden.bs.modal", function (event) {
        $(this).find("form").trigger("reset");
        $(this).find("select").trigger("change");
    });

    var datatable = $("#kt_datatable").DataTable({
        info: !1,
        order: [],
        // pageLength: 5,
        // lengthChange: !1,
        columnDefs: [{ orderable: !1, targets: 0 }],
    });

    document
        .querySelector('[data-kt-permissions-table-filter="search"]')
        .addEventListener("keyup", function (e) {
            datatable.search(e.target.value).draw();
        });

    // when click check box of datatable
    $("#kt_datatable tbody input.form-check-input").on("click", function () {
        let ids = $("#kt_datatable tbody")
            .find("tr")
            .map(function () {
                if ($(this).find("input.form-check-input").prop("checked"))
                    return $(this).attr("trainee_id");
            });

        if (ids.length > 0) $("#send_form_btn").removeAttr("disabled");
        else $("#send_form_btn").attr("disabled", true);
    });

    $("#kt_datatable thead input.form-check-input").on("click", function () {
        if ($(this).prop("checked")) $("#send_form_btn").removeAttr("disabled");
        else $("#send_form_btn").attr("disabled", true);
    });

    $("#sel_form").on("change", function () {
        datatable.columns(5).search($(this).val()).draw();
    });

    $("#sel_status").on("change", function () {
        datatable.columns(6).search($(this).val()).draw();
    });

    var t, e, n, a, o, i;
    i = document.querySelector("#kt_modal_add_form");
    o = new bootstrap.Modal(i);
    a = document.querySelector("#kt_modal_new_target_form");
    t = document.getElementById("kt_modal_new_target_submit");
    e = document.getElementById("kt_modal_new_target_cancel");
    n = FormValidation.formValidation(a, {
        fields: {
            m_sel_form: {
                validators: {
                    notEmpty: {
                        message: "Formuliernaam is vereist",
                    },
                },
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: ".fv-row",
                eleInvalidClass: "",
                eleValidClass: "",
            }),
        },
    });

    // modal submit button
    t.addEventListener("click", function (e) {
        e.preventDefault(),
            n &&
                n.validate().then(function (e) {
                    if ("Valid" == e) {
                        let ids = $("#kt_datatable tbody")
                            .find("tr")
                            .map(function () {
                                if (
                                    $(this)
                                        .find("input.form-check-input")
                                        .prop("checked")
                                ) {
                                    return parseInt($(this).attr("trainee_id"));
                                }
                            })
                            .toArray();
                        t.setAttribute("data-kt-indicator", "on");
                        t.disabled = !0;

                        let _url = "/distributie/sendFormToTranees";
                        let data = {
                            form_id: $("#m_sel_form").val(),
                            tranee_ids: ids,
                        };

                        $.ajax({
                            type: "POST",
                            url: _url,
                            data: data,
                            success: function (response) {
                                t.removeAttribute("data-kt-indicator");
                                t.disabled = !1;
                                o.hide();
                                $("input.form-check-input").prop(
                                    "checked",
                                    false
                                );
                                $("#send_form_btn").attr("disabled", true);

                                if (response.code == 200) {
                                    $(".toast-body").html(response.message);
                                    const newToast =
                                        targetElement.cloneNode(true);
                                    container.append(newToast);

                                    // Create new toast instance --- more info: https://getbootstrap.com/docs/5.1/components/toasts/#getorcreateinstance
                                    const toast =
                                        bootstrap.Toast.getOrCreateInstance(
                                            newToast
                                        );

                                    // Toggle toast to show --- more info: https://getbootstrap.com/docs/5.1/components/toasts/#show
                                    toast.show();

                                    location.reload();
                                } else if (response.code == 202) {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "warning",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Sluiten",
                                        customClass: {
                                            confirmButton: "btn btn-success",
                                        },
                                    }).then(function (t) {});
                                } else if (response.code == 204) {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "warning",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Sluiten",
                                        customClass: {
                                            confirmButton: "btn btn-success",
                                        },
                                    }).then(function (t) {
                                        location.href = '/survey_form/' + $("#m_sel_form").val();
                                    });
                                }
                            },
                            error: function (data) {
                                console.log("Error:", data);
                                t.removeAttribute("data-kt-indicator");
                                t.disabled = !1;
                                o.hide();
                            },
                        });
                    } else {
                        Swal.fire({
                            text: "Sorry, het lijkt erop dat er enkele fouten zijn gedetecteerd, probeer het opnieuw.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Sluiten",
                            customClass: {
                                confirmButton: "btn btn-success",
                            },
                        });
                    }
                });
    });

    // modal cancel button
    e.addEventListener("click", function (t) {
        t.preventDefault();
        a.reset(), o.hide();
    });

    $("#kt_datatable").on("click", ".delete-btn", function () {
        let survey_id = $(this).parents("tr").attr("survey_id");

        let _url = "/distributie/deleteSurveyItem";
        let data = {
            survey_id: survey_id,
        };

        $.ajax({
            type: "DELETE",
            url: _url,
            data: data,
            success: function (response) {
                if (response.code == 200) {
                    location.reload();
                }
            },
            error: function (data) {
                console.log("Error:", data);
            },
        });
    });

    $("#kt_datatable").on("click", ".view-btn", function () {
        let survey_id = $(this).parents("tr").attr("survey_id");

        let _url = "/distributie/viewSurveyInfo/" + survey_id;

        $.ajax({
            type: "GET",
            url: _url,
            success: function (response) {
                if (response.code == 200) {
                    let form_info = response.data.form_info;
                    let answers = response.data.answers;

                    $("#m_form_title").html(form_info["form_name"]);
                    let html = "";
                    if (answers.length > 0) {
                        for (index = 0; index < answers.length; index++) {
                            let item = answers[index];
                            html += "<div class='question-item p-3 mt-3'>";
                            html += "<h3 ";
                            if (item["is_require"] == "Yes")
                                html += "class = 'required'";
                            html +=
                                "> Q" +
                                (index + 1) +
                                ". " +
                                item["question"] +
                                "</h3>";
                            html += "<div class='answer ps-8 pt-4'>";
                            switch (item["answer_type"]) {
                                case "Option":
                                    let question_options = JSON.parse(
                                        item["question_option"]
                                    );
                                    question_options.forEach((option) => {
                                        html +=
                                            "<div class='form-check form-check-custom form-check-solid p-1'>";
                                        html +=
                                            "<input class='form-check-input me-3' type='radio' name = 'q" +
                                            index +
                                            "' value='" +
                                            option +
                                            "' disabled ";
                                        if (option == item["answer"])
                                            html += " checked ";
                                        html += ">";
                                        html +=
                                            "<label class='form-check-label'>" +
                                            option +
                                            "</label><br>";
                                        html += "</div>";
                                    });
                                    break;
                                case "Paragraph":
                                    html +=
                                        "<textarea type='text' class='form-control form-control-solid' rows='5' readonly>" +
                                        item["answer"] +
                                        "</textarea>";
                                    break;
                                case "Rating":
                                    for (
                                        score = 0;
                                        score < item["answer"];
                                        score++
                                    ) {
                                        html +=
                                            "<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg' class='rating-star'>";
                                        html +=
                                            "<path d='M11.1359 4.48359C11.5216 3.82132 12.4784 3.82132 12.8641 4.48359L15.011 8.16962C15.1523 8.41222 15.3891 8.58425 15.6635 8.64367L19.8326 9.54646C20.5816 9.70867 20.8773 10.6186 20.3666 11.1901L17.5244 14.371C17.3374 14.5803 17.2469 14.8587 17.2752 15.138L17.7049 19.382C17.7821 20.1445 17.0081 20.7069 16.3067 20.3978L12.4032 18.6777C12.1463 18.5645 11.8537 18.5645 11.5968 18.6777L7.69326 20.3978C6.99192 20.7069 6.21789 20.1445 6.2951 19.382L6.7248 15.138C6.75308 14.8587 6.66264 14.5803 6.47558 14.371L3.63339 11.1901C3.12273 10.6186 3.41838 9.70867 4.16744 9.54646L8.3365 8.64367C8.61089 8.58425 8.84767 8.41222 8.98897 8.16962L11.1359 4.48359Z' fill='gold'/>";
                                        html += "</svg>";
                                    }

                                    for (
                                        score = 0;
                                        score < 5 - item["answer"];
                                        score++
                                    ) {
                                        html +=
                                            "<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg' class='rating-star'>";
                                        html +=
                                            "<path d='M11.1359 4.48359C11.5216 3.82132 12.4784 3.82132 12.8641 4.48359L15.011 8.16962C15.1523 8.41222 15.3891 8.58425 15.6635 8.64367L19.8326 9.54646C20.5816 9.70867 20.8773 10.6186 20.3666 11.1901L17.5244 14.371C17.3374 14.5803 17.2469 14.8587 17.2752 15.138L17.7049 19.382C17.7821 20.1445 17.0081 20.7069 16.3067 20.3978L12.4032 18.6777C12.1463 18.5645 11.8537 18.5645 11.5968 18.6777L7.69326 20.3978C6.99192 20.7069 6.21789 20.1445 6.2951 19.382L6.7248 15.138C6.75308 14.8587 6.66264 14.5803 6.47558 14.371L3.63339 11.1901C3.12273 10.6186 3.41838 9.70867 4.16744 9.54646L8.3365 8.64367C8.61089 8.58425 8.84767 8.41222 8.98897 8.16962L11.1359 4.48359Z' fill='gray'/>";
                                        html += "</svg>";
                                    }

                                    break;
                                case "Text":
                                    html +=
                                        "<input type='text' class='form-control form-control-solid' value='" +
                                        item["answer"] +
                                        "' readonly />";
                                    break;
                            }
                            html += "</div>";
                            html += "</div>";
                        }
                    }
                    $("#m_answers").html(html);
                    $("#kt_modal_view_survey").modal("show");
                }
            },
            error: function (data) {
                console.log("Error:", data);
            },
        });
    });

    $("#kt_close").on("click", function () {
        $("#kt_modal_view_survey").modal("toggle");
    });
});
