"use strict";
var KTPasswordResetGeneral = (function () {
    var t, e, i;
    return {
        init: function () {
            (t = document.querySelector("#kt_password_reset_form")),
                (e = document.querySelector("#kt_password_reset_submit")),
                (i = FormValidation.formValidation(t, {
                    fields: {
                        email: {
                            validators: {
                                notEmpty: {
                                    message: "E-mailadres is vereist",
                                },
                                emailAddress: {
                                    message:
                                        "De waarde is geen geldig e-mailadres",
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
                })),
                e.addEventListener("click", function (o) {
                    o.preventDefault(),
                        i.validate().then(function (i) {
                            if ("Valid" == i) {
                                e.setAttribute("data-kt-indicator", "on");
                                e.disabled = !0;

                                let _url = "/password_reset";
                                let _token = $('meta[name="csrf-token"]').attr(
                                    "content"
                                );

                                let data = {
                                    email: $("#email").val(),
                                    _token: _token,
                                };

                                $.ajax({
                                    url: _url,
                                    type: "POST",
                                    data: data,
                                    success: function (response) {
                                        console.log(response);

                                        e.removeAttribute("data-kt-indicator");
                                        e.disabled = !1;

                                        if (response.code == 200) {
                                            Swal.fire({
                                                text: "Controleer uw e-mailbox",
                                                icon: "success",
                                                buttonsStyling: !1,
                                                confirmButtonText:
                                                    "OK ik snap het!",
                                                customClass: {
                                                    confirmButton:
                                                        "btn btn-success",
                                                },
                                            }).then(function (e) {
                                                location.href = "/";
                                            });
                                        } else {
                                            Swal.fire({
                                                text: response.message,
                                                icon: "warning",
                                                buttonsStyling: !1,
                                                confirmButtonText:
                                                    "Oké, snap het!",
                                                customClass: {
                                                    confirmButton:
                                                        "btn btn-success",
                                                },
                                            });
                                        }
                                    },
                                    error: function (response) {
                                        e.removeAttribute("data-kt-indicator");
                                        e.disabled = !1;
                                        Swal.fire({
                                            text: "Kan e-mail niet verzenden",
                                            icon: "warning",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Oké, snap het!",
                                            customClass: {
                                                confirmButton:
                                                    "btn btn-success",
                                            },
                                        });
                                    },
                                });
                            } else {
                                Swal.fire({
                                    text: "Sorry, het lijkt erop dat er enkele fouten zijn gedetecteerd, probeer het opnieuw.",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "OK ik snap het!",
                                    customClass: {
                                        confirmButton: "btn btn-success",
                                    },
                                });
                            }
                        });
                });
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTPasswordResetGeneral.init();
});
