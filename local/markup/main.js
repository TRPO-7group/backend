$(document).on("click", ".js-popup-search", function () {
    if ($("#filter-popup").is(":visible")) {
        $("#filter-popup").hide();
        $(".back").hide();
    }
    else {
        $("#filter-popup").fadeIn(150);
        $(".back").show();
    }
    })

$(document).on("click", ".back", function () {
    $("#filter-popup").hide();
    $(this).hide();
})