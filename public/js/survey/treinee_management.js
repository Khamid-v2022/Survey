var parent_set_flag = false;

let nIntervId;

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
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
});
