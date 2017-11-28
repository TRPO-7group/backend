/**
 * Created by Алексей on 07.11.2017.
 */
function loadListener() {
    loadingCount++;
    if (loadingCount >= $(".detail-container").length * 3) //количество диаграмм на странице
    {
        $(".detail-container > div").addClass("detail-commits-all");
        setTimeout(function () {
            $(".back").removeClass("loader");
            $("body").removeClass("scroll-hidden");
            $("#loader").hide();
        },100);
    }
}


