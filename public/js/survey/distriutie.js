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
});
