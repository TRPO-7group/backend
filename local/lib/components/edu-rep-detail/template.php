<script>
    <?php require "script.js";?>
    </script>
<div class="title">
    <h1><?php echo $arResult["repository_name"];?></h1>
    <div class="detail-description">
        <?php echo $arResult['repository_description'];?>
    </div>
    <?php if (count($arResult["child_reps"]) == 0)
        echo "В данном репозитории пока нет участников";?>
    <?php
    if ($arResult["show_button"])
    {
        ?>
        <button class="standart-button js-open-add-ind-rep">Принять участие</button>
    <?php
    }
    ?>

</div>

<?php

foreach ($arResult["child_reps"] as $rep)
{
    ?>
<div class="detail-container">
    <h3 data-user-id="#<?php echo $rep["user_id"]?>" class="detail-statistic"><?php echo  $rep["user_name"]?><span>[+]</span></h3>

    <?php
    MainClass::includeComponent("rep-detail","edu-template",array('id' => $rep['rep_id'], 'owner_name' => $rep['user_name'], "owner_id" => $rep["user_id"]));
?>
</div>
    <?php
}

if (count($arResult["child_reps"]) == 0)
{
    ?>
    <script>
        $(".back").removeClass("loader");
        $("body").removeClass("scroll-hidden");
        $("#loader").hide();
    </script>
    <?php
}
?>


<div id="add-ind-rep-form" class="dialog-form">
    <form>
        <p>Добавьте репозиторий</p>
        <fieldset>
            <label for="rep_url">URL:</label>
            <input type="text" name="rep_url" id="rep_url" value="" class="text ui-widget-content ui-corner-all" placeholder="Введите url...">
            <label for="rep_descr">Описание</label>
            <textarea name="rep_descr" id="rep_descr" class="text ui-widget-content ui-corner-all" placeholder="Введите описание"></textarea>

            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
        </fieldset>
        <p>или выберите существующий</p>
        <fieldset>
            <label for="rep">Выберите репозиторий</label>
            <select name="rep" id="rep">
                <option value="" disabled selected>Выберите</option>
                <?php foreach ($arResult["rep_ind_list"] as $rep){?>
                    <option value="<?php echo $rep["id"]?>"><?php echo $rep["name"] . " (" . $rep["url"] . ")"?></option>
                <?php }?>
            </select>

        </fieldset>
        <input type="hidden" name="edu_rep" value="<?php echo $params["id"]?>">
    </form>
</div>
