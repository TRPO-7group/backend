
$(document).on("click", ".load-button button", function (e) {
    var url=$(this).data('url');
    $(this).attr("disabled",true);
    $.ajax({
        url: url,
        success: function(data) {
            if ($(".list-elem").length > 0)
            {
                $(".list").append($(data).find(".list-elem"));
            }
            if ($(data).find(".load-button").length == 0)
                $(".load-button").remove();
            $(this).removeAttr("disabled");
        }
    });

})






$(window).on("load", function () {

    var form = $("#add-ind-rep-form").find("form");
    var allFields =  $("#add-ind-rep-form").find("input");
    var urlField = form.find("#rep_url");


    function addIndRep() {
        form.submit();
    }


    $(document).on("submit", "#add-ind-rep-form form", function () {

        if (checkRegexp($(urlField), /^[\s\S]*\.git$/i)){
         var data = form.serialize();
            $.ajax({
                url: "/reposit-catalog/ajax/add-ind-rep.php",
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
        height: 400,
        width: 350,
        modal: true,
        buttons: {
            "Добавить репозиторий": addIndRep,
            Cancel: function() {
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
//language=JQuery-CSS


