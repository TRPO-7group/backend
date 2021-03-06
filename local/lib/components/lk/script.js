$(document).on('click', ".js-accept-invite", function (e) {
    var rep_id = $(this).data("rep-id");
    var user_id = $(this).data("user-id");
    var curr_user = $(this).data("curr-user");
    var user_rep_id = $(this).data("user-rep-id");
    var $container = $(this).closest(".reps-list-elem");
    $.ajax({
        url: "/reposit-catalog/ajax/update-invite.php",
        data: {rep_id: rep_id, user_id: user_id, curr_user: curr_user, user_rep: user_rep_id},
        type: "post",
        async: false
    })

    $.ajax({
        async: false,
        success: function (data) {
            $container.empty().append($(data).find(".reps-list-elem[data-id="+ rep_id +"]"));
            $container.find(".repos-list-elem-title-name").click();
            $(".back").click();
        }
    })
})



$(document).on('click', ".js-delete-invite", function (e) {
    var rep_id = $(this).data("rep-id");
    var user_id = $(this).data("user-id");
    var curr_user = $(this).data("curr-user");
    var user_rep_id = $(this).data("user-rep-id");
    var $container = $(this).closest(".reps-list-elem");
    $.ajax({
        url: "/reposit-catalog/ajax/delete-invite.php",
        data: {rep_id: rep_id, user_id: user_id, curr_user: curr_user, user_rep: user_rep_id},
        type: "post",
        async: false
    })

    $.ajax({
        async: false,
        success: function (data) {
            $container.empty().append($(data).find(".reps-list-elem[data-id="+ rep_id +"]"));
            $container.find(".repos-list-elem-title-name").click();
            $(".back").click();
        }
    })
})


$(document).on("click", ".repos-list-elem-invite-all", function (e) {
    var $container = $(this).closest(".reps-list-elem");
    var $invites = $container.find(".js-accept-invite");
    var rep_id = [];
    var user_id = [];
    var curr_user = $invites.eq(0).data("curr-user");
    var user_rep_id = [];
    $.each($invites, function (key, value) {;
        rep_id.push($(value).data("rep-id"));
        user_id.push($(value).data("user-id"));
        user_rep_id.push($(value).data("user-rep-id"));
    });

    $.ajax({
        url: "/reposit-catalog/ajax/update-invite.php",
        data: {rep_id: rep_id, user_id: user_id, curr_user: curr_user, user_rep: user_rep_id},
        type: "post",
        async: false
    })

    $.ajax({
        async: false,
        success: function (data) {
            $container.empty().append($(data).find(".reps-list-elem[data-id="+ rep_id[0] +"]"));
            $container.find(".repos-list-elem-title-name").click();
            $(".back").click();
        }
    })


})



$(window).on("load",function () {


    $( "#user-find-autocomplete" ).autocomplete({
        source: "/reposit-catalog/ajax/users-list.php",
        minLength: 2,
        select: function( event, ui ) {
            $(".popup-user-find").attr("data-user", ui.item.id);
            $(".popup-user-find").find(".popup-user-find-ok").prop("disabled", false);
        },
        response: function( event, ui ) {
            console.log(ui);
        }
    });
});

$(document).on("keyup", "#user-find-autocomplete", function () {
    $(".popup-user-find").find(".popup-user-find-ok").prop("disabled", true);
})



$(document).on("click", ".popup-user-find-ok", function () {
    var $popup = $(this).closest(".popup-user-find");
    var user = $popup.attr("data-user");
    var rep = $popup.attr("data-rep");
    if (!user)
        return false;
    $.ajax({
        url: "/reposit-catalog/ajax/add-invite.php",
        data: {rep_id: rep, user_id: user},
        type: "post",
        async: false
    });

    $.ajax({
        type: "get",
        async: false,
        success: function (data) {
            var selector = ".reps-list-elem[data-id="+ rep +"]";
            $(selector).empty().append($(data).find(selector));
            $(".back").click();
        }
    });



});



$(document).on("click", ".repos-list-elem-title-delete", function () {
    var rep = $(this).closest(".reps-list-elem").attr("data-id");
    var currBlock = $(this).closest(".reps-list-elem");
    if (confirm("Удалить репозиторий? ")) {
        $.ajax({
            url: "/reposit-catalog/ajax/delete-reposit.php",
            data: {rep_id: rep},
            type: "get",
            async: false,
            success: function (data) {
                currBlock.remove();
            }
        });
    }
})





//Попап создания
$(window).on("load", function () {

    var form = $("#add-edu-rep-form").find("form");
    var allFields =  $("#add-edu-rep-form").find("input");
    var urlField = form.find("#rep_url");


    function addIndRep() {
        form.submit();
    }


    $(document).on("submit", "#add-edu-rep-form form", function () {

        if (checkRegexp($(urlField), /^[\s\S]*\.git$/i)){
            var data = form.serialize();
            var url = "/reposit-catalog/ajax/add-edu-rep.php";
            if (/^https:\/\/github\.com\/(.*)\/(.*).git$/.test($(urlField).val()))
            {
                if (confirm("Добавить форки?"))
                {
                    url = "/reposit-catalog/ajax/add-rep-with-forks.php";
                }
            }
            $.ajax({
                url: url,
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



    dialog = $( "#add-edu-rep-form" ).dialog({
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


    $(document).on("click", ".js-open-add-edu-rep", function () {
        dialog.dialog("open");
    });

    $( "#disc" ).selectmenu();
});