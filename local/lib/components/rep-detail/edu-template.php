<form class="demo-example" style="display: none;">
    <label for="mask">Фильтр:</label>
    <select class="mask" name="mask_<?php echo  $arResult["repository_id"]?>" multiple id="mask_<?php echo  $arResult["repository_id"]?>">
        <?php foreach ($arResult["masks_list"] as $category){
            foreach ($category as $mask) {
                ?>
                <option value="<?php echo $mask["value"]?>"> <?php echo $mask["name"]?> (<?php echo $mask["value"]?>) </option>
            <?php }
        }?>
    </select>
</form>
<div class="tabs"  id="tabs_<?php echo  $arResult["repository_id"]?>">
    <ul>
        <li><a href="/reposit-catalog/ajax/get-rep-stat.php?template=edu&period=7&rep_id=<?php echo $arResult["repository_id"]?>">Неделя</a></li>
        <li><a href="/reposit-catalog/ajax/get-rep-stat.php?template=edu&period=30&rep_id=<?php echo $arResult["repository_id"]?>"">Месяц</a></li>
        <li><a href="/reposit-catalog/ajax/get-rep-stat.php?template=edu&period=90&rep_id=<?php echo $arResult["repository_id"]?>"">Три месяца</a></li>
    </ul>
</div>

<script>


    $( function() {
        var select =  $('#mask_<?php echo  $arResult["repository_id"]?>');
        var tabs = $( "#tabs_<?php echo  $arResult["repository_id"]?>" );
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

        $(document).on("change", "#mask_<?php echo  $arResult["repository_id"]?>", function () {
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
<hr>