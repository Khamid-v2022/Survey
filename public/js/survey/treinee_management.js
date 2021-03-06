var parent_set_flag = false;

let nIntervId;
var arr_parentOrg_info = [];

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#kt_modal_add_user").on("hidden.bs.modal", function (event) {
        $(this).find("form").trigger("reset");
        $(this).find("select").trigger("change");
        $(".action-type").html("Add");
        $("#kt_modal_new_target_submit .indicator-label").html("Submit");
        $("#m_user_id").val("");
    });

    var datatable = $("#kt_datatable").DataTable({
        info: !1,
        order: [],
        // pageLength: 5,
        // lengthChange: !1,
        columnDefs: [{ orderable: !1, targets: 4 }],
    });

    document
        .querySelector('[data-kt-permissions-table-filter="search"]')
        .addEventListener("keyup", function (e) {
            datatable.search(e.target.value).draw();
        });

    var t, e, n, a, o, i;
    i = document.querySelector("#kt_modal_add_user");
    o = new bootstrap.Modal(i);
    a = document.querySelector("#kt_modal_new_target_form");
    t = document.getElementById("kt_modal_new_target_submit");
    e = document.getElementById("kt_modal_new_target_cancel");
    n = FormValidation.formValidation(a, {
        fields: {
            m_user_role: {
                validators: {
                    notEmpty: {
                        message: "Rol is vereist",
                    },
                },
            },
            m_user_parent: {
                validators: {
                    notEmpty: {
                        message: "Organisatie is verplicht",
                    },
                },
            },
            m_first_name: {
                validators: {
                    notEmpty: {
                        message: "Voornaam is verplicht",
                    },
                },
            },
            m_last_name: {
                validators: {
                    notEmpty: {
                        message: "Achternaam is verplicht",
                    },
                },
            },
            m_email: {
                validators: {
                    notEmpty: {
                        message: "E-mail is verplicht",
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

                        let _url = "/user_management/addUpdateUser";

                        let data = {
                            role: $("#m_user_role").val(),
                            parent_id: $("#m_user_parent").val(),
                            name: $("#m_name").val(),
                            first_name: $("#m_first_name").val(),
                            last_name: $("#m_last_name").val(),
                            email: $("#m_email").val(),
                            address: $("#m_address").val(),
                            post_code: $("#m_post_code").val(),
                            city: $("#m_city").val(),
                            num_add: $("#m_num_add").val(),
                            tel: $("#m_tel").val(),
                            action_type: $(".action-type").html(),
                        };

                        if ($(".action-type").html() == "Edit") {
                            data["id"] = $("#m_user_id").val();
                        }
                        $.ajax({
                            type: "POST",
                            url: _url,
                            data: data,
                            success: function (response) {
                                if (response.code == 200) {
                                    location.reload();
                                } else if (response.code == 422) {
                                    t.removeAttribute("data-kt-indicator");
                                    t.disabled = !1;
                                    Swal.fire({
                                        text: response.message,
                                        icon: "warning",
                                        buttonsStyling: !1,
                                        confirmButtonText: "OK ik snap het!",
                                        customClass: {
                                            confirmButton: "btn btn-success",
                                        },
                                    }).then(function (t) {
                                        // t.isConfirmed && o.hide();
                                    });
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
                            confirmButtonText: "OK ik snap het!",
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

    $("#m_user_role").on("change", function () {
        parent_set_flag = false;

        let role = $(this).val();
        if (role == "department") {
            $("#m_name_type").html("Afdeling");
            $(".coach-trainer").find("input").val("");

            $(".coach-trainer").css("display", "none");
            $(".department-program").css("display", "block");

            $(".parent-div").css("display", "none");
        } else if (role == "program") {
            $("#m_name_type").html("Opleidings");
            $(".coach-trainer").find("input").val("");

            $(".coach-trainer").css("display", "none");
            $(".department-program").css("display", "block");

            $(".parent-org-name").html("Afdelingnaam");
            $(".parent-div").css("display", "block");
        } else if (role == "coach" || role == "trainer" || role == "trainee") {
            $(".department-program").find("input").val("");

            $(".department-program").css("display", "none");
            $(".coach-trainer").css("display", "block");

            $(".parent-org-name").html("Opleidingsnaam");
            if (role == "trainee") {
                $(".parent-org-name").html("Coach / Trainer Naam");
            }
            $(".parent-div").css("display", "block");
        }

        let _url = "/user_management/getUserTreeByRole";

        let data = {
            role: role,
        };

        $.ajax({
            type: "POST",
            url: _url,
            data: data,
            success: function (response) {
                if (response.code == 200) {
                    let users = response.data;
                    console.log(users);
                    let html = "";
                    for (let index = 0; index < users.length; index++) {
                        arr_parentOrg_info.push(users[index]);

                        html += '<option value="' + users[index].id + '">';
                        if (role == "trainee") {
                            html +=
                                users[index].first_name +
                                " " +
                                users[index].last_name +
                                "(" +
                                users[index].role +
                                ")</option>";
                        } else {
                            html +=
                                users[index].name +
                                "(" +
                                users[index].role +
                                ")</option>";
                        }
                    }

                    $("#m_user_parent").html(html);
                    $("#m_user_parent").trigger("change");
                    // if target =  trainee then show department, program fields
                    $(".department-field").css("display", "none");
                    $(".program-field").css("display", "none");
                    if (role == "trainee") {
                        $(".department-field").css("display", "block");
                        $(".program-field").css("display", "block");
                    } else if (role == "trainer" || role == "coach") {
                        $(".department-field").css("display", "block");
                    }
                } else {
                    $("#m_user_parent").html("");
                }
                parent_set_flag = true;
            },
            error: function (data) {
                parent_set_flag = true;
            },
        });
    });

    $("#m_user_role").trigger("change");

    $("#m_user_parent").on("change", function () {
        $(".department-field input").val("");
        $(".program-field input").val("");

        let sel_parent_id = $(this).val();

        if (arr_parentOrg_info.length > 0) {
            for (index = 0; index < arr_parentOrg_info.length; index++) {
                let item = arr_parentOrg_info[index];
                if (item["id"] == sel_parent_id) {
                    if (item["department_name"]) {
                        $(".department-field input").val(
                            item["department_name"]
                        );
                    }
                    if (item["program_name"]) {
                        $(".program-field input").val(item["program_name"]);
                    }
                }
            }
        }
    });

    $(".delete-btn").on("click", function () {
        let user_id = $(this).parents("tr").attr("user_id");

        let _url = "/user_management/deleteUser/" + user_id;

        $.ajax({
            type: "DELETE",
            url: _url,
            success: function (response) {
                if (response.code == 200) {
                    location.reload();
                }
            },
            error: function (data) {},
        });
    });

    $(".edit-btn").on("click", function () {
        let id = $(this).parents("tr").attr("user_id");

        $("#m_user_id").val(id);

        let _url = "/user_management/userInfo/" + id;

        $.ajax({
            type: "GET",
            url: _url,
            success: function (response) {
                let data = response.data;
                $("#m_user_role").val(data.role).trigger("change");
                parent_set_flag = false;
                if (!nIntervId) {
                    nIntervId = setInterval(setParentId, 1000, data.parent_id);
                }

                $("#m_name").val(data.name);
                $("#m_first_name").val(data.first_name);
                $("#m_last_name").val(data.last_name);
                $("#m_email").val(data.email);
                $("#m_address").val(data.address);
                $("#m_post_code").val(data.post_code);
                $("#m_city").val(data.city);
                $("#m_num_add").val(data.num_add);
                $("#m_tel").val(data.tel);

                $(".action-type").html("Edit");
                $("#kt_modal_new_target_submit .indicator-label").html(
                    "Update"
                );
                o.show();
            },
            error: function (data) {
                console.log("Error:", data);
            },
        });
    });
});

function setParentId(parent_id) {
    if (parent_set_flag) {
        $("#m_user_parent").val(parent_id).trigger("change");
        clearInterval(nIntervId);
        nIntervId = null;
    }
}
