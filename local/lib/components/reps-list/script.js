$(document).on("click", ".load-button button", function (e) {
    var url=$(this).data('url');
    console.log(url);
    $.ajax({
        url: url,
        success: function(data) {
            if ($(".list-elem").length > 0)
            {
                $(".list").append($(data).find(".list-elem"));
            }
            if ($(data).find(".load-button").length == 0)
                $(".load-button").remove();
        }
    });

})