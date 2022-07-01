$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#kt_modal_add_form").on("hidden.bs.modal", function (event) {
        $(this).find("form").trigger("reset");
        $(this).find("select").trigger("change");
        $(".action-type").html("Add");
        $("#kt_modal_new_target_submit .indicator-label").html("Submit");
        $("#m_form_id").val("");
    });

    var datatable = $("#kt_datatable").DataTable({
        info: !1,
        order: [],
        // pageLength: 5,
        // lengthChange: !1,
        columnDefs: [{ orderable: !1, targets: 3 }],
    });

    document
        .querySelector('[data-kt-permissions-table-filter="search"]')
        .addEventListener("keyup", function (e) {
            datatable.search(e.target.value).draw();
        });

    var t, e, n, a, o, i;
    i = document.querySelector("#kt_modal_add_form");
    o = new bootstrap.Modal(i);
    a = document.querySelector("#kt_modal_new_target_form");
    t = document.getElementById("kt_modal_new_target_submit");
    e = document.getElementById("kt_modal_new_target_cancel");
    n = FormValidation.formValidation(a, {
        fields: {
            m_form_name: {
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

    // edit mode
    $(".edit-form-btn").on("click", function () {
        let id = $(this).parents("tr").attr("form_id");

        $("#m_form_id").val(id);

        $("#m_form_name").val($(this).parents("tr").attr("form_name"));
        $("#m_active")
            .val($(this).parents("tr").attr("form_active"))
            .trigger("change");

        $(".action-type").html("Edit");
        $("#kt_modal_new_target_submit .indicator-label").html("Update");
        o.show();
    });

    // modal submit button
    t.addEventListener("click", function (e) {
        e.preventDefault(),
            n &&
                n.validate().then(function (e) {
                    if ("Valid" == e) {
                        t.setAttribute("data-kt-indicator", "on");
                        t.disabled = !0;

                        let _url = "/enquetes/addUpdateForm";

                        let data = {
                            form_name: $("#m_form_name").val(),
                            active: $("#m_active").val(),
                            action_type: $(".action-type").html(),
                        };

                        if ($(".action-type").html() == "Edit") {
                            data["id"] = $("#m_form_id").val();
                        }
                        $.ajax({
                            type: "POST",
                            url: _url,
                            data: data,
                            success: function (response) {
                                // console.log(response);
                                if (response.code == 200) {
                                    location.reload();
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

    // active button
    $(".active-form-btn").on("click", function () {
        let active;
        if ($(this).prop("checked") == true) {
            active = "active";
        } else {
            active = "inactive";
        }

        let id = $(this).parents("tr").attr("form_id");
        let _url = "/enquetes/changeActive";

        $.ajax({
            type: "POST",
            url: _url,
            data: {
                id: id,
                active: active,
            },
            success: function (data) {
                console.log(data);
            },
            error: function (data) {
                console.log("Error:", data);
            },
        });
    });

    // delete button -> user Delete
    $(".delete-form-btn").on("click", function () {
        let id = $(this).parents("tr").attr("form_id");
        let _url = "/enquetes/deleteForm/" + id;

        $.ajax({
            type: "DELETE",
            url: _url,
            success: function (data) {
                $("tr[form_id = " + id + "]").remove();
            },
            error: function (data) {
                console.log("Error:", data);
            },
        });
    });
});
