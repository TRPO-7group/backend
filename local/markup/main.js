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
