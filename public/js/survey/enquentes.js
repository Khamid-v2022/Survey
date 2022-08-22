$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#kt_modal_add_form").on("hidden.bs.modal", function (event) {
        $(this).find("form").trigger("reset");
        $(this).find("select").trigger("change");
        $(".action-type").html("Toevoegen");
        $("#action_type").val("Add");
        $("#kt_modal_new_target_submit .indicator-label").html("Submit");
        $("#m_form_id").val("");
    });

    var datatable = $("#kt_datatable").DataTable({
        info: !1,
        order: [],
        // pageLength: 5,
        // lengthChange: !1,
        columnDefs: [{ orderable: !1, targets: 5 }],
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
                        message: "Formuliernaam is vereist",
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

                        let _url = "/enquetes/addUpdateForm";

                        let data = {
                            form_name: $("#m_form_name").val(),
                            active: $("#m_active").val(),
                            action_type: $("#action_type").val(),
                        };

                        if ($("#action_type").val() == "Edit") {
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
                            text: "Sorry, het lijkt erop dat er enkele fouten zijn gedetecteerd, probeer het opnieuw.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Sluiten",
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

    // active button
    $("#kt_datatable").on("click", ".active-form-btn", function () {
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

    // edit mode
    $("#kt_datatable").on("click", ".edit-form-btn", function () {
        let id = $(this).parents("tr").attr("form_id");

        $("#m_form_id").val(id);

        $("#m_form_name").val($(this).parents("tr").attr("form_name"));
        $("#m_active")
            .val($(this).parents("tr").attr("form_active"))
            .trigger("change");

        $(".action-type").html("Edit");
        $("#action_type").val("Edit");
        $("#kt_modal_new_target_submit .indicator-label").html("Update");
        o.show();
    });

    // delete button -> user Delete
    $("#kt_datatable").on("click", ".delete-form-btn", function () {
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

    // csv export
    $("#kt_datatable").on("click", ".excel-btn", function () {
        let id = $(this).parents("tr").attr("form_id");
        let _url = "/enquetes/exportCSV/" + id;
        window.location.href = _url;
        // $.ajax({
        //     type: "get",
        //     url: _url,
        //     success: function (data) {
        //         // $("tr[form_id = " + id + "]").remove();
        //     },
        //     error: function (data) {
        //         console.log("Error:", data);
        //     },
        // });
    });
    
});
