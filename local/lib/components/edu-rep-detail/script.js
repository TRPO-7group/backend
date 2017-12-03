
function loadListener() {
    loadingCount++;
    if (loadingCount >= $(".detail-container").length * 3) //количество диаграмм на странице
    {
        $(".detail-container > div").addClass("detail-commits-all");
        setTimeout(function () {
            $(".back").removeClass("loader");
            $("body").removeClass("scroll-hidden");
            $("#loader").hide();
            var hash = window.location.hash;
            if(hash) {
                var $container = $("[data-user-id=" + hash + "]");
                $container.click();
                $('html, body').animate({
                        scrollTop: $container.offset().top
                    }, 1000
                )
            }
        },100);
    }
}



