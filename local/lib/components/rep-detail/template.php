<div class="title">
    <h1><?php echo $arResult["repository_name"];?></h1>
</div>
<div class="detail-container">
    <div class="detail-description">
        <?php echo $arResult['repository_description'];?>
    </div>
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
        $( ".tabs" ).tabs({
            beforeLoad: function( event, ui ) {
                ui.jqXHR.fail(function() {
                    ui.panel.html(
                        "Загрузка..." );
                });
            }
        });
    } );


</script>