<script>
<?php
    require "script.js";
    ?>
</script>
<div class="list">
    <div class="all">
        Всего <?php echo count($arResult['items'])?> <?php echo MainClass::getWord(count($arResult['items']), array("репозиторий", "репозитория", "репозиториев"));?>
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
    <button data-url="<?php echo $arResult['next_page']?>"><img src="/reposit-catalog/local/markup/img/update.png">Загрузить еще</button>
</div>
<?php }?>