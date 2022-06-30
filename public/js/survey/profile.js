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
                        notEmpty: { message: "Email is required" },
                        emailAddress: {
                            message: "The value is not a valid email address",
                        },
                    },
                },
                confirmemailpassword: {
                    validators: {
                        notEmpty: { message: "Password is required" },
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
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton:
                                                "btn font-weight-bold btn-light-primary",
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
                                } else if (response.code == 401) {
                                    swal.fire({
                                        text: response.message,
                                        icon: "warning",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton:
                                                "btn font-weight-bold btn-light-primary",
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
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton:
                                    "btn font-weight-bold btn-light-primary",
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
                                message: "Current Password is required",
                            },
                        },
                    },
                    newpassword: {
                        validators: {
                            notEmpty: {
                                message: "New Password is required",
                            },
                        },
                    },
                    confirmpassword: {
                        validators: {
                            notEmpty: {
                                message: "Confirm Password is required",
                            },
                            identical: {
                                compare: function () {
                                    return n.querySelector(
                                        '[name="newpassword"]'
                                    ).value;
                                },
                                message:
                                    "The password and its confirm are not the same",
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
                                                    "Ok, got it!",
                                                customClass: {
                                                    confirmButton:
                                                        "btn font-weight-bold btn-light-primary",
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
                                                    "Ok, got it!",
                                                customClass: {
                                                    confirmButton:
                                                        "btn font-weight-bold btn-light-primary",
                                                },
                                            }).then(function () {});
                                        } else if (response.code == 402) {
                                            swal.fire({
                                                text: response.message,
                                                icon: "warning",
                                                buttonsStyling: !1,
                                                confirmButtonText:
                                                    "Ok, got it!",
                                                customClass: {
                                                    confirmButton:
                                                        "btn font-weight-bold btn-light-primary",
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
                                    text: "Sorry, looks like there are some errors detected, please try again.",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton:
                                            "btn font-weight-bold btn-light-primary",
                                    },
                                });
                            }
                        });
                    });
        })();
});
