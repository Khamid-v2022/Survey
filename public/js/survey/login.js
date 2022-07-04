"use strict";
var KTSignupGeneral = (function () {
    return {
        init: function () {
            // sign in
            let signin_form = document.querySelector("#kt_sign_in_form");
            let signin_btn = document.querySelector("#kt_sign_in_submit");
            let form_validation = FormValidation.formValidation(signin_form, {
                fields: {
                    email: {
                        validators: {
                            notEmpty: {
                                message: "E-mailadres is vereist",
                            },
                            emailAddress: {
                                message: "De waarde is geen geldig e-mailadres",
                            },
                        },
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: "Het wachtwoord is vereist",
                            },
                        },
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                    }),
                },
            });
            signin_btn.addEventListener("click", function (n) {
                n.preventDefault();
                form_validation.validate().then(function (i) {
                    if ("Valid" == i) {
                        signin_btn.setAttribute("data-kt-indicator", "on");
                        signin_btn.disabled = !0;

                        let _url = "/login";
                        let _token = $('meta[name="csrf-token"]').attr(
                            "content"
                        );

                        let data = {
                            email: $("#email").val(),
                            password: $("#password").val(),
                            _token: _token,
                        };

                        $.ajax({
                            url: _url,
                            type: "POST",
                            data: data,
                            success: function (response) {
                                // console.log(response);
                                signin_btn.setAttribute(
                                    "data-kt-indicator",
                                    "off"
                                );
                                signin_btn.disabled = !1;
                                if (response.code == 200) {
                                    location.href = "/";
                                } else if (response.code == 201) {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "warning",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Oké, snap het!",
                                        customClass: {
                                            confirmButton: "btn btn-success",
                                        },
                                    });
                                } else {
                                    Swal.fire({
                                        text: "Oppe! U heeft ongeldige inloggegevens ingevoerd",
                                        icon: "warning",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Oké, snap het!",
                                        customClass: {
                                            confirmButton: "btn btn-success",
                                        },
                                    });
                                }
                            },
                            error: function (response) {
                                Swal.fire({
                                    text: "Oppe! U heeft ongeldige inloggegevens ingevoerd",
                                    icon: "warning",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Oké, snap het!",
                                    customClass: {
                                        confirmButton: "btn btn-success",
                                    },
                                });
                                signin_btn.setAttribute(
                                    "data-kt-indicator",
                                    "off"
                                );
                                signin_btn.disabled = !1;
                            },
                        });
                    } else {
                        Swal.fire({
                            text: "Sorry, het lijkt erop dat er fouten zijn gedetecteerd, probeer het opnieuw.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Oké, snap het!",
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
    KTSignupGeneral.init();
});
