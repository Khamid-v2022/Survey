$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
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
            success: function (data) {
                console.log(data);
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
});
