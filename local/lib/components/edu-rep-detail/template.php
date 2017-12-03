<script>
    <?php require "script.js";?>
    </script>
<div class="title">
    <h1><?php echo $arResult["repository_name"];?></h1>
    <div class="detail-description">
        <?php echo $arResult['repository_description'];?>
    </div>
</div>

<?php

foreach ($arResult["child_reps"] as $rep)
{
    MainClass::includeComponent("rep-detail","edu-template",array('id' => $rep['rep_id'], 'owner_name' => $rep['user_name'], "owner_id" => $rep["user_id"]));
}

if (count($arResult["child_reps"]) == 0)
{
    ?>
<script>
    setTimeout(function () {
        $(".back").removeClass("loader");
        $("#loader").hide();
    },100);

    </script>
    <?php
}
?>