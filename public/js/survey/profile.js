$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var t, e;
    !(function () {
        var t = document.getElementById("kt_signin_email"),
            e = document.getElementById("kt_signin_email_edit"),
            n = document.getElementById("kt_signin_password"),
            o = document.getElementById("kt_signin_password_edit"),
            i = document.getElementById("kt_signin_email_button"),
            s = document.getElementById("kt_signin_cancel"),
            r = document.getElementById("kt_signin_password_button"),
            a = document.getElementById("kt_password_cancel");
        i.querySelector("button").addEventListener("click", function () {
            l();
        }),
            s.addEventListener("click", function () {
                l();
            }),
            r.querySelector("button").addEventListener("click", function () {
                d();
            }),
            a.addEventListener("click", function () {
                d();
            });
        var l = function () {
                t.classList.toggle("d-none"),
                    i.classList.toggle("d-none"),
                    e.classList.toggle("d-none");
            },
            d = function () {
                n.classList.toggle("d-none"),
                    r.classList.toggle("d-none"),
                    o.classList.toggle("d-none");
            };
    })(),
        (e = document.getElementById("kt_signin_change_email")),
        (t = FormValidation.formValidation(e, {
            fields: {
                emailaddress: {
                    validators: {
                        notEmpty: { message: "E-mail is vereist" },
                        emailAddress: {
                            message: "De waarde is geen geldig e-mailadres",
                        },
                    },
                },
                confirmemailpassword: {
                    validators: {
                        notEmpty: { message: "Een wachtwoord is verplicht" },
                    },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row",
                }),
            },
        })),
        e
            .querySelector("#kt_signin_submit")
            .addEventListener("click", function (n) {
                n.preventDefault();
                console.log("click");
                t.validate().then(function (n) {
                    if ("Valid" == n) {
                        let _url = "/update_profile";

                        let data = {
                            email: $("#emailaddress").val(),
                            password: $("#confirmemailpassword").val(),
                        };

                        $.ajax({
                            type: "POST",
                            url: _url,
                            data: data,
                            success: function (response) {
                                if (response.code == 200) {
                                    swal.fire({
                                        text: response.message,
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "OK ik snap het!",
                                        customClass: {
                                            confirmButton:
                                                "btn font-weight-bold btn-light-success",
                                        },
                                    }).then(function () {
                                        $("#user_email").html(
                                            $("#emailaddress").val()
                                        );
                                        $("#confirmemailpassword").val("");
                                        document
                                            .getElementById("kt_signin_email")
                                            .classList.toggle("d-none");
                                        document
                                            .getElementById(
                                                "kt_signin_email_button"
                                            )
                                            .classList.toggle("d-none");
                                        document
                                            .getElementById(
                                                "kt_signin_email_edit"
                                            )
                                            .classList.toggle("d-none");
                                    });
                                } else if (response.code == 422) {
                                    swal.fire({
                                        text: response.message,
                                        icon: "warning",
                                        buttonsStyling: !1,
                                        confirmButtonText: "OK ik snap het!",
                                        customClass: {
                                            confirmButton:
                                                "btn font-weight-bold btn-light-success",
                                        },
                                    }).then(function () {});
                                } else if (response.code == 401) {
                                    swal.fire({
                                        text: response.message,
                                        icon: "warning",
                                        buttonsStyling: !1,
                                        confirmButtonText: "OK ik snap het!",
                                        customClass: {
                                            confirmButton:
                                                "btn font-weight-bold btn-light-success",
                                        },
                                    }).then(function () {});
                                }
                            },
                            error: function (data) {
                                console.log("Error:", data);
                            },
                        });
                    } else {
                        swal.fire({
                            text: "Sorry, het lijkt erop dat er enkele fouten zijn gedetecteerd, probeer het opnieuw.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "OK ik snap het!",
                            customClass: {
                                confirmButton:
                                    "btn font-weight-bold btn-light-success",
                            },
                        });
                    }
                });
            }),
        (function (t) {
            var e,
                n = document.getElementById("kt_signin_change_password");
            (e = FormValidation.formValidation(n, {
                fields: {
                    currentpassword: {
                        validators: {
                            notEmpty: {
                                message: "Huidig wachtwoord is vereist",
                            },
                        },
                    },
                    newpassword: {
                        validators: {
                            notEmpty: {
                                message: "Nieuw wachtwoord is vereist",
                            },
                        },
                    },
                    confirmpassword: {
                        validators: {
                            notEmpty: {
                                message: "Bevestig dat wachtwoord vereist is",
                            },
                            identical: {
                                compare: function () {
                                    return n.querySelector(
                                        '[name="newpassword"]'
                                    ).value;
                                },
                                message:
                                    "Het wachtwoord en de bevestiging zijn niet hetzelfde",
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
            })),
                n
                    .querySelector("#kt_password_submit")
                    .addEventListener("click", function (t) {
                        t.preventDefault();
                        e.validate().then(function (t) {
                            if ("Valid" == t) {
                                let _url = "/change_password";

                                let data = {
                                    current_password:
                                        $("#currentpassword").val(),
                                    new_password: $("#newpassword").val(),
                                };

                                $.ajax({
                                    type: "POST",
                                    url: _url,
                                    data: data,
                                    success: function (response) {
                                        if (response.code == 200) {
                                            swal.fire({
                                                text: response.message,
                                                icon: "success",
                                                buttonsStyling: !1,
                                                confirmButtonText:
                                                    "OK ik snap het!",
                                                customClass: {
                                                    confirmButton:
                                                        "btn font-weight-bold btn-light-success",
                                                },
                                            }).then(function () {
                                                n.reset(), e.resetForm();
                                            });
                                        } else if (response.code == 401) {
                                            swal.fire({
                                                text: response.message,
                                                icon: "warning",
                                                buttonsStyling: !1,
                                                confirmButtonText:
                                                    "OK ik snap het!",
                                                customClass: {
                                                    confirmButton:
                                                        "btn font-weight-bold btn-light-success",
                                                },
                                            }).then(function () {});
                                        } else if (response.code == 402) {
                                            swal.fire({
                                                text: response.message,
                                                icon: "warning",
                                                buttonsStyling: !1,
                                                confirmButtonText:
                                                    "OK ik snap het!",
                                                customClass: {
                                                    confirmButton:
                                                        "btn font-weight-bold btn-light-success",
                                                },
                                            }).then(function () {});
                                        }
                                    },
                                    error: function (data) {
                                        console.log("Error:", data);
                                    },
                                });
                            } else {
                                swal.fire({
                                    text: "Sorry, het lijkt erop dat er enkele fouten zijn gedetecteerd, probeer het opnieuw.",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "OK ik snap het!",
                                    customClass: {
                                        confirmButton:
                                            "btn font-weight-bold btn-light-success",
                                    },
                                });
                            }
                        });
                    });
        })();;
});
