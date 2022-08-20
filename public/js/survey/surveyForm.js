$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#kt_modal_add_form").on("hidden.bs.modal", function (event) {
        $(this).find("form").trigger("reset");
        $(this).find("select").trigger("change");
        $(".action-type").html("Toevoegen");
        $("#action_type").val("Add");
        $("#m_question_id").val("");
        $("#m_options").html("");
        $("#m_option_values").val("[]");
        $("#m_preview_question").html("Vraag...");
        $(".question-object").css("display", "none");
        $(".question-object-text").css("display", "block");
    });

    new Tagify(document.querySelector('[id="m_option_tags"]'), {
        // whitelist: ["Important", "Urgent", "High", "Medium", "Low"],
        // maxTags: 5,
        // dropdown: { maxItems: 10, enabled: 0, closeOnSelect: !1 },
    }).on("change", function () {
        // n.revalidateField("tags");
    });

    $("#m_question").on("keyup change", function (e) {
        let title = $(this).val();

        if (title) $("#m_preview_question").html(title);
        else $("#m_preview_question").html("Vraag...");
    });

    $("#m_answer_type").on("change", function () {
        let value = $(this).val();
        $(".question-object").css("display", "none");
        switch (value) {
            case "Option":
                $(".question-object-option").css("display", "block");
                break;
            case "Paragraph":
                $(".question-object-paragraph").css("display", "block");
                break;
            case "Rating":
                $(".question-object-rating").css("display", "block");
                break;
            case "Text":
                $(".question-object-text").css("display", "block");
                break;
        }
    });

    $("#m_option_tags").on("change", function () {
        let value = $(this).val();
        if (value) {
            let values = JSON.parse($(this).val());

            let html = "";
            let option_values = "[";
            let fist_flag = 1;
            values.forEach((item) => {
                html += "<option>" + item.value + "</option>";
                if (!fist_flag) option_values += ", ";
                option_values += '"' + item.value + '"';
                fist_flag = 0;
            });
            option_values += "]";

            $("#m_options").html(html);
            $("#m_option_values").val(option_values);
        } else {
            $("#m_options").html("");
            $("#m_option_values").val("[]");
        }
    });

    var t, e, n, a, o, i;
    i = document.querySelector("#kt_modal_add_form");
    o = new bootstrap.Modal(i);
    a = document.querySelector("#kt_modal_new_target_form");
    t = document.getElementById("kt_modal_new_target_submit");
    e = document.getElementById("kt_modal_new_target_cancel");
    n = FormValidation.formValidation(a, {
        fields: {
            m_question: {
                validators: {
                    notEmpty: {
                        message: "Vragen zijn verplicht.",
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
        e.preventDefault();
        n &&
            n.validate().then(function (e) {
                if ("Valid" == e) {
                    if (
                        $("#m_answer_type").val() == "Option" &&
                        $("#m_option_tags").val() == ""
                    ) {
                        Swal.fire({
                            text: "Sorry, Voer keuzeopties in.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Sluiten",
                            customClass: {
                                confirmButton: "btn btn-success",
                            },
                        });
                        return;
                    }
                    t.setAttribute("data-kt-indicator", "on");
                    t.disabled = !0;

                    let _url = "/survey_form/addUpdateQuestion";
                    let is_require = "No";
                    if ($("#is_require").prop("checked") == true)
                        is_require = "Yes";

                    let data = {
                        form_id: $("#form_id").val(),
                        question: $("#m_question").val(),
                        question_option: $("#m_option_values").val(),
                        answer_type: $("#m_answer_type").val(),
                        is_require: is_require,
                    };

                    if ($("#action_type").val() == "Edit") {
                        data["id"] = $("#m_question_id").val();
                    }

                    $.ajax({
                        type: "POST",
                        url: _url,
                        data: data,
                        success: function (response) {
                            if (response.code == 200) {
                                location.reload();
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

    // edit mode
    $(".edit-question-btn").on("click", function () {
        let id = $(this).parents(".accordion-item").attr("question_id");
        $("#m_question_id").val(id);
        $(".action-type").html("Edit");
        $("#action_type").val("Edit");

        let _url = "/survey_form/getQuestion/" + id;

        $.ajax({
            type: "GET",
            url: _url,
            success: function (response) {
                if (response.code == 200) {
                    let info = response.data;
                    $("#m_question").val(info.question);
                    $("#m_option_values").val(info.question_option);
                    $("#m_answer_type").val(info.answer_type).trigger("change");

                    if (info.answer_type == "Option") {
                        $("#m_option_tags")
                            .val(info.question_option)
                            .trigger("change");
                    }

                    if (info.is_require == "Yes")
                        $("#is_require").prop("checked", true);
                }
            },
            error: function (data) {
                console.log("Error:", data);
                t.removeAttribute("data-kt-indicator");
                t.disabled = !1;
                o.hide();
            },
        });

        o.show();
    });

    // delete button
    $(".delete-question-btn").on("click", function () {
        let id = $(this).parents(".accordion-item").attr("question_id");

        let _url = "/survey_form/deleteQuestion/" + id;
        $.ajax({
            type: "DELETE",
            url: _url,
            success: function (data) {
                location.reload();
            },
            error: function (data) {
                console.log("Error:", data);
            },
        });
    });
});
