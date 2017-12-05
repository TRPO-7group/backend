
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


$(window).on("load", function () {

    var form = $("#add-ind-rep-form").find("form");
    var allFields =  $("#add-ind-rep-form").find("input");
    var urlField = form.find("#rep_url");
    var repField = form.find("#rep");
    var descField = form.find("#rep_descr");

    function addIndRep() {
        form.submit();
    }

    $(urlField).keypress(function () {
        $(repField).val("").change();
    });

    $(repField).change(function () {
        if ($(repField).val() != "" && $(repField).val() != null) {
            urlField.val("");
            descField.val("");
        }
    });

    $(document).on("submit", "#add-ind-rep-form form", function () {
        if (checkRegexp($(urlField), /^[\s\S]*\.git$/i) || repField.val() !== null){
            var data = form.serialize();
            $.ajax({

                url: "/reposit-catalog/ajax/add-student-invite.php",
                type: "post",
                data: data,
                success: function () {
                    window.location.reload();
                }

            });
        }
        return false;
    })


    function checkRegexp( o, regexp ) {
        if ( !( regexp.test( o.val() ) ) ) {
            o.addClass( "ui-state-error" );
            return false;
        } else {
            return true;
        }
    }



    dialog = $( "#add-ind-rep-form" ).dialog({
        autoOpen: false,
        height: 600,
        width: 350,
        modal: true,
        buttons: {
            "Добавить репозиторий": addIndRep,
            "Закрыть": function() {
                dialog.dialog( "close" );
            }
        },
        close: function() {
            $(form).trigger("reset");
            allFields.removeClass( "ui-state-error" );
        }
    });


    $(document).on("click", ".js-open-add-ind-rep", function () {
        dialog.dialog("open");
    })


});


