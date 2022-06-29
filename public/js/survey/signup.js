"use strict";
var KTSignupGeneral = (function () {
    var e,
        t,
        a,
        s,
        i,
        r = function () {
            return 100 === s.getScore();
        };
    return {
        init: function () {
            // sign up
            e = document.querySelector("#kt_sign_up_form");
            t = document.querySelector("#kt_sign_up_submit");
            s = KTPasswordMeter.getInstance(
                e.querySelector('[data-kt-password-meter="true"]')
            );
            a = FormValidation.formValidation(e, {
                fields: {
                    first_name: {
                        validators: {
                            notEmpty: { message: "Voornaam is required" },
                        },
                    },
                    last_name: {
                        validators: {
                            notEmpty: { message: "Achternaam is required" },
                        },
                    },
                    chamber_commerce: {
                        validators: {
                            notEmpty: { message: "KvK# is required" },
                        },
                    },
                    city: {
                        validators: {
                            notEmpty: { message: "Stad is required" },
                        },
                    },
                    tel: {
                        validators: {
                            notEmpty: { message: "Tel is required" },
                        },
                    },
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
                            callback: {
                                message: "Please enter valid password",
                                callback: function (e) {
                                    if (e.value.length > 0) return r();
                                },
                            },
                        },
                    },
                    "confirm-password": {
                        validators: {
                            notEmpty: {
                                message:
                                    "The password confirmation is required",
                            },
                            identical: {
                                compare: function () {
                                    return e.querySelector('[name="password"]')
                                        .value;
                                },
                                message:
                                    "The password and its confirm are not the same",
                            },
                        },
                    },
                    toc: {
                        validators: {
                            notEmpty: {
                                message:
                                    "You must accept the terms and conditions",
                            },
                        },
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger({
                        event: { password: !1 },
                    }),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: "",
                    }),
                },
            });
            t.addEventListener("click", function (r) {
                r.preventDefault();
                a.revalidateField("password");
                a.validate().then(function (a) {
                    if ("Valid" == a) {
                        t.setAttribute("data-kt-indicator", "on");
                        t.disabled = !0;

                        let _url = `/registratie`;
                        let _token = $('meta[name="csrf-token"]').attr(
                            "content"
                        );

                        let data = {
                            company_name: $("#company_name").val(),
                            first_name: $("#first_name").val(),
                            last_name: $("#last_name").val(),
                            chamber_commerce: $("#chamber_commerce").val(),
                            city: $("#city").val(),
                            email: $("#email").val(),
                            tel: $("#tel").val(),
                            password: $("#password").val(),
                            _token: _token,
                        };

                        $.ajax({
                            url: _url,
                            type: "POST",
                            data: data,
                            success: function (response) {
                                if (response.code == 200) {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        },
                                    }).then(function (t) {
                                        // t.isConfirmed && (e.reset(), s.reset());
                                        location.href = "/login";
                                    });
                                }
                            },
                            error: function (response) {
                                Swal.fire({
                                    text: "Sorry, looks like there are some errors detected, please try again later.",
                                    icon: "warning",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    },
                                });
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
            e.querySelector('input[name="password"]').addEventListener(
                "input",
                function () {
                    this.value.length > 0 &&
                        a.updateFieldStatus("password", "NotValidated");
                }
            );
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTSignupGeneral.init();
});
