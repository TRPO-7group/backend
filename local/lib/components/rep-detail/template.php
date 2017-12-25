<div class="title">
    <h1><?php echo $arResult["repository_name"];?></h1>
</div>
<div class="detail-container">
    <div class="detail-description">
        <?php echo $arResult['repository_description'];?>
    </div>
    <form class="demo-example">
        <label for="mask">Фильтр:</label>
        <select id="mask" name="mask" multiple>
            <?php foreach ($arResult["masks_list"] as $category){
                foreach ($category as $mask) {
                ?>
                <option value="<?php echo $mask["value"]?>"> <?php echo $mask["name"]?> (<?php echo $mask["value"]?>)</option>
            <?php }
            }?>
        </select>
    </form>
<div class="tabs">
    <ul>
        <li><a href="/reposit-catalog/ajax/get-rep-stat.php?period=7&rep_id=<?php echo $arResult["repository_id"]?>">Неделя</a></li>
        <li><a href="/reposit-catalog/ajax/get-rep-stat.php?period=30&rep_id=<?php echo $arResult["repository_id"]?>"">Месяц</a></li>
        <li><a href="/reposit-catalog/ajax/get-rep-stat.php?period=90&rep_id=<?php echo $arResult["repository_id"]?>"">Три месяца</a></li>
    </ul>
</div>
</div>

<script>


    $( function() {
        var select =  $('#mask');
        var tabs = $( ".tabs" );
        tabs.tabs({
            beforeLoad: function( event, ui ) {
                if (select.val() != null)
                {
                    ui.ajaxSettings.url += "&mask=" + select.val().join(", ");
                }
                ui.jqXHR.fail(function() {
                    ui.panel.html(
                        "Загрузка..." );
                });
            }
        });

        $(document).on("change", "#mask", function () {
            var current_index =tabs.tabs("option","active");
            tabs.tabs("load", current_index);
        })

       select.multiSelect({
            noneText: 'Все файлы',
            presets: [

                {
                    name: 'Все файлы',
                    options: []
                },
                <?php foreach ($arResult["masks_list"] as $key => $category){?>
                {
                    name: '<?php echo $key?>',

                    options: [
                        <?php foreach ($category as $mask) {?>
                            <?php echo "'" . $mask["value"] . "',"?>
                        <?php }?>
                        ]
                },
                <?php }?>
            ]
        });

    } );

</script>