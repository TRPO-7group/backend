<script>
<?php
    require "script.js";
    ?>
</script>
<div class="list">
    <div class="all">
        <div>
        <?php if ($arResult["cnt"]) {?>
        Всего <?php echo $arResult["cnt"]?> <?php echo MainClass::getWord($arResult["cnt"], array("репозиторий", "репозитория", "репозиториев"));?>
        <?php } else echo "У вас еще нет личных репозиториев"?></span>
        </div>
        <div class="standart-button js-open-add-ind-rep">
            Добавить
        </div>
    </div>
    <hr>
    <?php
    foreach ($arResult['items'] as $reposit){
        $date = new DateTime();
        $date->setTimestamp($reposit['last_commit']);
        ?>
        <div class="list-elem">
        <div class="list-elem-name">
            <a href="<?php echo $reposit["link"]?>">
                <?php echo $reposit['name']?>
            </a>
        </div>
        <span class="list-elem-lang">Язык: php</span><span class="list-elem-refresh">Последнее обновление: <?php echo $date->format("Y.m.d")?></span>
            <div class="list-elem-tags"><?php echo $reposit["tegs"];?></div>
        <hr>
    </div>
    <?php }?>

</div>
<?php
if ($arResult["exist_next_page"]){
?>
<div class="load-button">
    <button data-url="<?php echo $arResult['next_page']?>"><img src="/reposit-catalog/local/markup/build/img/update.png">Загрузить еще</button>
</div>
<?php }?>




<div id="add-ind-rep-form" class="dialog-form">
    <form action="/reposit-catalog/ajax/add-ind-rep.php">
        <fieldset>
            <label for="rep_url">URL:</label>
            <input type="text" name="rep_url" id="rep_url" value="" class="text ui-widget-content ui-corner-all" placeholder="Введите url...">
            <label for="rep_descr">Описание</label>
            <textarea name="rep_descr" id="rep_descr" class="text ui-widget-content ui-corner-all" placeholder="Введите описание"></textarea>

            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
        </fieldset>
    </form>
</div>
