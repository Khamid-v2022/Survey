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
                            notEmpty: { message: "Voornaam is verplicht" },
                        },
                    },
                    last_name: {
                        validators: {
                            notEmpty: { message: "Achternaam is verplicht" },
                        },
                    },
                    chamber_commerce: {
                        validators: {
                            notEmpty: { message: "KvK# is verplicht" },
                        },
                    },
                    city: {
                        validators: {
                            notEmpty: { message: "Stad is verplicht" },
                        },
                    },
                    tel: {
                        validators: {
                            notEmpty: { message: "Tel is verplicht" },
                        },
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: "E-mailadres is verplicht",
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
                            callback: {
                                message: "Voer een geldig wachtwoord in",
                                callback: function (e) {
                                    if (e.value.length > 0) return r();
                                },
                            },
                        },
                    },
                    "confirm-password": {
                        validators: {
                            notEmpty: {
                                message: "De wachtwoordbevestiging is vereist",
                            },
                            identical: {
                                compare: function () {
                                    return e.querySelector('[name="password"]')
                                        .value;
                                },
                                message:
                                    "Het wachtwoord en de bevestiging zijn niet hetzelfde",
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
                                        confirmButtonText: "Oké, snap het!",
                                        customClass: {
                                            confirmButton: "btn btn-success",
                                        },
                                    }).then(function (t) {
                                        // t.isConfirmed && (e.reset(), s.reset());
                                        location.href = "/login";
                                    });
                                } else if (response.code == 422) {
                                    t.setAttribute("data-kt-indicator", "off");
                                    t.disabled = !1;
                                    Swal.fire({
                                        text: response.message,
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Oké, snap het!",
                                        customClass: {
                                            confirmButton: "btn btn-success",
                                        },
                                    }).then(function (t) {});
                                }
                            },
                            error: function (response) {
                                Swal.fire({
                                    text: "Sorry, het lijkt erop dat er fouten zijn gedetecteerd, probeer het later opnieuw.",
                                    icon: "warning",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Oké, snap het!",
                                    customClass: {
                                        confirmButton: "btn btn-success",
                                    },
                                });
                                t.setAttribute("data-kt-indicator", "off");
                                t.disabled = !1;
                            },
                        });
                    } else {
                        Swal.fire({
                            text: "Sorry, het lijkt erop dat er enkele fouten zijn gedetecteerd, probeer het opnieuw.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Oké, snap het!",
                            customClass: {
                                confirmButton: "btn btn-success",
                            },
                        });
                        t.setAttribute("data-kt-indicator", "off");
                        t.disabled = !1;
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



