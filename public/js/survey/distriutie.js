$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

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
                        message: "Form Name is required",
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
                                if (response.code == 200) {
                                    t.removeAttribute("data-kt-indicator");
                                    t.disabled = !1;
                                    o.hide();
                                    $("input.form-check-input").prop(
                                        "checked",
                                        false
                                    );
                                    $("#send_form_btn").attr("disabled", true);
                                    Swal.fire({
                                        text: response.message,
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
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
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
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
});
