$(document).ready(function () {
    $("#popup-authorization").offset(function (i,val) {
        var link = $(".authorization-block.js-popup").offset();
        return {top:link.top + 60, left:link.left-30};

    })
})


$(document).on("click", ".js-popup", function (e) {
    e.preventDefault();
    console.log($(this).attr("href"));
    $($(this).attr("href")).show();
})

$(document).on("click", ".popup", function (e) {
  $(this).hide();
});

$(document).on("click", ".popup-content", function (e) {
    return false;
});
