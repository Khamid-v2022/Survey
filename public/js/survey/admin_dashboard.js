$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const container = document.getElementById("kt_docs_toast_stack_container");
    const targetElement = document.querySelector(
        '[data-kt-docs-toast="stack"]'
    );

    $("#kt_modal").on("hidden.bs.modal", function (event) {
        $(this).find("form").trigger("reset");
        $(this).find("select").trigger("change");
        $("#action_type").val("Add");
        $("#kt_modal_new_target_submit .indicator-label").html("Toevoegen");
        $("#m_user_id").val("");
    });

    $(".active-company-btn").on("click", function () {
        let active;
        if ($(this).prop("checked") == true) {
            active = "active";
        } else {
            active = "inactive";
        }

        let id = $(this).parents("tr").attr("company_id");
        let _url = "/admin_dashboard";

        $.ajax({
            type: "POST",
            url: _url,
            data: {
                id: id,
                active: active,
            },
            success: function (response) {
                // console.log(data);
                if (response.code == 202) {
                    location.reload();
                } else if (response.code == 200) {
                    $(".toast-body").html(response.message);
                    const newToast = targetElement.cloneNode(true);
                    container.append(newToast);

                    // Create new toast instance --- more info: https://getbootstrap.com/docs/5.1/components/toasts/#getorcreateinstance
                    const toast = bootstrap.Toast.getOrCreateInstance(newToast);

                    // Toggle toast to show --- more info: https://getbootstrap.com/docs/5.1/components/toasts/#show
                    toast.show();
                }
            },
            error: function (data) {
                location.reload();
                console.log("Error:", data);
            },
        });
    });

    $(".info-btn").on("click", function () {
        let id = $(this).parents("tr").attr("company_id");

        $("#action_type").val("Edit");
        $("#kt_modal_new_target_submit .indicator-label").html("Update");
        $("#m_user_id").val(id);

        let _url = "/admin_dashboard";
        $.ajax({
            type: "GET",
            url: _url + "/" + id,
            success: function (data) {
                let info = data.data;
                $("#m_company_name").val(info.name);
                $("#m_org_type").val(info.org_type).trigger("change");
                $("#m_first_name").val(info.first_name);
                $("#m_last_name").val(info.last_name);
                $("#m_chamber_commerce").val(info.chamber_commerce);
                $("#m_city").val(info.city);
                $("#m_email").val(info.email);
                $("#m_tel").val(info.tel);

                $("#kt_modal").modal("show");
            },
            error: function (data) {
                console.log("Error:", data);
            },
        });
    });

    $(".delete-company-btn").on("click", function () {
        let id = $(this).parents("tr").attr("company_id");
        let _url = "/admin_dashboard";

        $.ajax({
            type: "DELETE",
            url: _url + "/" + id,
            success: function (data) {
                $("tr[company_id = " + id + "]").remove();
            },
            error: function (data) {
                console.log("Error:", data);
            },
        });
    });

    var t, e, n, a, o, i;
    i = document.querySelector("#kt_modal");
    o = new bootstrap.Modal(i);
    a = document.querySelector("#kt_modal_new_target_form");
    t = document.getElementById("kt_modal_new_target_submit");
    e = document.getElementById("kt_modal_cancel");
    n = FormValidation.formValidation(a, {
        fields: {
            m_company_name: {
                validators: {
                    notEmpty: {
                        message: "Dit veld is vereist",
                    },
                },
            },
            m_first_name: {
                validators: {
                    notEmpty: {
                        message: "Dit veld is vereist",
                    },
                },
            },
            m_last_name: {
                validators: {
                    notEmpty: {
                        message: "Dit veld is vereist",
                    },
                },
            },
            m_chamber_commerce: {
                validators: {
                    notEmpty: {
                        message: "Dit veld is vereist",
                    },
                },
            },
            m_city: {
                validators: {
                    notEmpty: {
                        message: "Dit veld is vereist",
                    },
                },
            },
            m_email: {
                validators: {
                    notEmpty: {
                        message: "Dit veld is vereist",
                    },
                },
            },
            m_tel: {
                validators: {
                    notEmpty: {
                        message: "Dit veld is vereist",
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
                        t.setAttribute("data-kt-indicator", "on");
                        t.disabled = !0;

                        let _url = `/admin_dashboard`;

                        let data = {
                            org_type: $("#m_org_type").val(),
                            company_name: $("#m_company_name").val(),
                            first_name: $("#m_first_name").val(),
                            last_name: $("#m_last_name").val(),
                            chamber_commerce: $("#m_chamber_commerce").val(),
                            city: $("#m_city").val(),
                            email: $("#m_email").val(),
                            tel: $("#m_tel").val(),
                            action_type: $("#action_type").val(),
                        };

                        if ($("#action_type").val() == "Edit") {
                            data["id"] = $("#m_user_id").val();
                        }

                        $.ajax({
                            url: _url,
                            type: "PUT",
                            data: data,
                            success: function (response) {
                                t.setAttribute("data-kt-indicator", "off");
                                t.disabled = !1;
                                if (response.code == 200) {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Sluiten",
                                        customClass: {
                                            confirmButton: "btn btn-success",
                                        },
                                    }).then(function (t) {
                                        location.reload();
                                    });
                                } else if (response.code == 422) {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Sluiten",
                                        customClass: {
                                            confirmButton: "btn btn-success",
                                        },
                                    }).then(function (t) {});
                                }
                            },
                            error: function (response) {
                                Swal.fire({
                                    text: "Sorry, het lijkt erop dat er enkele fouten zijn gedetecteerd, probeer het opnieuw.",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Sluiten",
                                    customClass: {
                                        confirmButton: "btn btn-success",
                                    },
                                });
                                t.setAttribute("data-kt-indicator", "off");
                                t.disabled = !1;
                            },
                        });
                    } else {
                    }
                });
    });
    
    $("#kt_modal_cancel").on("click", function (t) {
        t.preventDefault();
        // a.reset(), o.hide();
        $("#kt_modal").modal("toggle");
    });
});
