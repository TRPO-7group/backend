
<div class="list">
    <div class="all">
Всего <?php echo $arResult["count_items"]?>  <?php echo MainClass::getWord($arResult['count_items'],array("репозиторий","репозитория","репозиториев"))?>
</div>
    <hr>
    <?php foreach ($arResult["items"] as $key => $group){?>
    <div class="list-group">
        <span><?php echo $key?></span><span>[<?php echo count($group)?>]</span>
        <?php foreach ($group as $item){
            $date = new DateTime();
            $date->setTimestamp($item['last_commit']);
            ?>
            <div class="list-elem">
        <div class="list-elem-name">
            <a href="<?php echo $item["link"]?>">
                <?php echo $item["name"]?>
</a>
        </div>
                <?php if ($item["language"]) {?>
        <span class="list-elem-lang">Язык: <?php echo $item["language"]?></span><?php }?><span class="list-elem-refresh">Последнее обновление:  <?php echo $date->format("Y.m.d")?></span>
        <div class="list-elem-tags"><?php echo $item["tegs"]?></div>
        <hr>
    </div>
        <?php }?>

    </div>
    <?php }?>

</div>