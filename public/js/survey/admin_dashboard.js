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
        let _url = "/admin_dashboard";

        $.ajax({
            type: "GET",
            url: _url + "/" + id,
            success: function (data) {
                let info = data.data;
                $("#m_company_name").val(info.name);
                $("#m_org_type").val(info.org_type);
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

    $("#kt_modal_cancel").on("click", function (t) {
        t.preventDefault();
        // a.reset(), o.hide();
        $("#kt_modal").modal("toggle");
    });
});
