$(function () {

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $(".rating-star").on("click", function () {
        $(this)
            .parents("div.rating-wrap")
            .find("path")
            .map(function () {
                $(this).attr("fill", "gray");
            });
        let sel_value = $(this).attr("value");
        $(this).parents("div.rating-wrap").find("input").val(sel_value);

        $(this)
            .parents("div.rating-wrap")
            .find("svg")
            .map(function () {
                if ($(this).attr("value") <= sel_value)
                    $(this).find("path").attr("fill", "gold");
            });
    });

    $(".rating-star").hover(
        function () {
            let sel_value = $(this).attr("value");
            $(this).find("path").attr("fill", "#f8eaa2");
        },
        function () {
            let sel_value = $(this).attr("value");
            let curr_value = $(this)
                .parents("div.rating-wrap")
                .find("input")
                .val();
            if (sel_value <= curr_value)
                $(this).find("path").attr("fill", "gold");
            else $(this).find("path").attr("fill", "gray");
        }
    );

    $("#submit_btn").on("click", function () {
        let check_info = true;
        let data = $(".questions")
            .find(".question-item")
            .map(function () {
                let question_num = $(this).attr("question_number");
                let question_type = $(this)
                    .find("[name = 'question_type']")
                    .val();
                let value = "";
                if (question_type == "Option") {
                    value = $(this)
                        .find("[name='" + question_num + "']:checked")
                        .val();
                } else {
                    value = $(this)
                        .find("[name='" + question_num + "']")
                        .val();
                }

                if ($(this).hasClass("require") && !value) {
                    check_info = false;
                }

                let question_id = $(this).attr("question_id");

                return { question_id: question_id, answer: value };
            })
            .toArray();

        if (!check_info) {
            Swal.fire({
                text: "Sorry, het lijkt erop dat er enkele fouten zijn gedetecteerd, probeer het opnieuw.",
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Sluiten",
                customClass: {
                    confirmButton: "btn btn-success",
                },
            });
            return;
        }

        t = document.getElementById("submit_btn");
        t.setAttribute("data-kt-indicator", "on");
        t.disabled = !0;
        let _url = "/survey";
        $.ajax({
            type: "POST",
            url: _url,
            data: {
                data: data,
                trainee_id: $("#user_id").val(),
                form_id: $("#form_id").val(),
            },
            success: function (data) {
                location.href = "/thanks";
            },
            error: function (data) {
                console.log("Error:", data);
                Swal.fire({
                    text: "Sorry. Er is iets fout gegaan. Probeer het later opnieuw.",
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "Sluiten",
                    customClass: {
                        confirmButton: "btn btn-success",
                    },
                });
            },
        });
    });
});
