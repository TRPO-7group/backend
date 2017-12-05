<script>
    <?php require "script.js";?>
</script>


<div class="title-center">
    <h1>Личный кабинет</h1>
</div>

<div class="lk-container">

    <form class="lk-load" method="post" enctype='multipart/form-data'>
        <input type="hidden" name="save" value="Y">

        <div class="user-info">

            <div class="user-info-section">
                <label class="user-info-label">
                    ФИО:
                </label>
                <input type="text" name="name" value="<?php echo $_SESSION["auth_info"]["name"]?>" required>
            </div>
            <div class="user-info-section">
                <label class="user-info-label">
                    Группа:
                </label>
                <input type="text" name="group" value="<?php echo $_SESSION["auth_info"]["group_num"]?>">
            </div>
            <div class="user-info-section">
                <label class="user-info-label">
                    Почта:
                </label>
                <input type="email" name="email" value="<?php echo $_SESSION["auth_info"]["user_mail"]?>">

            </div>
            <div class="user-info-section">
                <label class="user-info-label">
                    Статус:
                </label>
                <?php echo $_SESSION["auth_info"]["user_type"] == 0 ? "Студент" : "Учитель"?>
            </div>

            <div class="lk-submit-button">
                <button type="submit">Сохранить</button>
            </div>

        </div>
        <div class="user-avatar">
            <img src="<?php echo $_SESSION["auth_info"]["preview_img"]?>" id="user-avatar-loader">
            <label class="user-avatar-uploader">
                Загрузить новое фото
                <input type="file" name="foto">
            </label>
        </div>
    </form>

    <div class="reps-list">
        <span class="rep-list-title">Список учебных репозиториев <?php if ($arResult["teacher_inv_cnt"]) echo "(" . $arResult["teacher_inv_cnt"] . " " . MainClass::getWord($arResult["teacher_inv_cnt"], array("новое", "новых", "новых")) . " " .MainClass::getWord($arResult["teacher_inv_cnt"], array("приглашение", "приглашения", "приглашений")) . ")"?></span>

        <?php
$user = MainClass::getUser();
        $date = new DateTime();
         foreach ($arResult["status"]["list"] as $rep){

            ?>
            <div class="rep-list-lk-student-elem">
                <div class="rep-list-lk-student-elem-title">
                    <a href="<?php echo $arResult["rep_list"][$rep]["link"]?>#<?php echo $user["user_id"]?>"><?php echo $arResult["rep_list"][$rep]["name"]?></a>
                </div>
                <div>
                    <?php if ($arResult["status"][$rep["rep_id"]] == MainClass::$REP_USER_STATUS_TEACHER_INVITE){?>
                    <button class="standart-button js-open-add-ind-rep" data-edu-rep="<?php echo $rep?>">
                    Принять приглашение
                    </button>
                    <?php } else {?>
                    <button class="standart-button js-delete-rep-inv" data-rep-id="<?php echo $rep?>">
                        Выйти из репозитория
                    </button>
                    <?php }?>
                </div>
            </div>
        <?php }?>

    </div>
</div>


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
        <input type="hidden" name="edu_rep">
    </form>
</div>


