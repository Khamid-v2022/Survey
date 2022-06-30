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
                                message: "Email address is required",
                            },
                            emailAddress: {
                                message:
                                    "The value is not a valid email address",
                            },
                        },
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: "The password is required",
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
                                if (response.code == 200) {
                                    location.href = "/";
                                } else if (response.code == 201) {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "warning",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        },
                                    });
                                    signin_btn.setAttribute(
                                        "data-kt-indicator",
                                        "off"
                                    );
                                    signin_btn.disabled = !1;
                                } else {
                                    Swal.fire({
                                        text: "Oppes! You have entered invalid credentials",
                                        icon: "warning",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        },
                                    });
                                    signin_btn.setAttribute(
                                        "data-kt-indicator",
                                        "off"
                                    );
                                    signin_btn.disabled = !1;
                                }
                            },
                            error: function (response) {
                                Swal.fire({
                                    text: "Oppes! You have entered invalid credentials",
                                    icon: "warning",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
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
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTSignupGeneral.init();
});
