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

                url: "/reposit-catalog/ajax/bind-ind-rep.php",
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
        form.find("input[name=edu_rep]").val($(this).data("edu-rep"))
        dialog.dialog("open");
    })

    $(document).on("click",".js-delete-rep-inv", function () {
        if (confirm("Выйти из репозитория?"))
        {
            var rep_id=$(this).data("rep-id");
            $.ajax({

                url: "/reposit-catalog/ajax/delete-bind.php",
                type: "post",
                data: {rep_id: rep_id},
                async: false,
                success: function () {
                    window.location.reload();
                }

            });
        }
    })

});


