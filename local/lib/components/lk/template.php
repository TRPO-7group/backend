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
                <?php if ($arResult["user_info"]){?>
                    <span><?php echo $arResult["user_info"]["name"]?></span>
                <?php } else {?>
                <input type="text" name="name" value="<?php echo $_SESSION["auth_info"]["name"]?>" required>
            <?php }?>
            </div>
            <div class="user-info-section">
                <label class="user-info-label">
                    Кафедра:
                </label>
                <?php if ($arResult["user_info"]){?>
                    <span><?php echo $arResult["user_info"]["pulpit"]?></span>
                <?php } else {?>
                <input type="text" name="pulpit" value="<?php echo $_SESSION["auth_info"]["pulpit"]?>">
            <?php }?>
            </div>
            <div class="user-info-section">
                <label class="user-info-label">
                    Почта:
                </label>
                <?php if ($arResult["user_info"]){?>
                    <span><?php echo $arResult["user_info"]["user_mail"]?></span>
                <?php } else {?>
                <input type="email" name="email" value="<?php echo $_SESSION["auth_info"]["user_mail"]?>">
            <?php }?>
            </div>
            <div class="user-info-section">
                <label class="user-info-label">
                    Статус:
                </label>
                <?php if ($arResult["user_info"]){?>
                    <?php echo$arResult["user_info"]["user_type"] == 0 ? "Ученик" : "Учитель"?>
                <?php } else {?>
                <?php echo $_SESSION["auth_info"]["user_type"] == 0 ? "Ученик" : "Учитель"?>
                <?php }?>
            </div>
            <?php if (!$arResult["user_info"]){?>
            <div class="lk-submit-button">
                <button type="submit">Сохранить</button>
            </div>
            <?php }?>

        </div>
        <div class="user-avatar">
            <img src="<?php echo $_SESSION["auth_info"]["preview_img"]?>" id="user-avatar-loader">
            <?php if (!$arResult["user_info"]){?>
            <label class="user-avatar-uploader">

                Загрузить новое фото
                <input type="file" name="foto">
            </label>
            <?php }?>
        </div>
    </form>
    <?php if (!$arResult["user_info"]){?>
    <div class="reps-list">
        <span class="rep-list-title">Список учебных репозиториев</span>
        <button class="rep-list-add js-open-add-edu-rep">Добавить репозиторий</button>

        <?php

        $date = new DateTime();
         foreach ($arResult["rep_list"] as $rep){
            $invCount = $arResult["status"][$rep["info"]["id"]]["invited_count"];

            ?>
        <div class="reps-list-elem" data-id="<?php echo $rep["info"]["id"]?>">
            <div class="repos-list-elem-title">
                <div class="repos-list-elem-title-name"><?php echo $rep["info"]["name"]?> <?php if ($invCount > 0)
                    echo "( " . $invCount . " " . MainClass::getWord($invCount, array("новый", "новых", "новых")) . " " .MainClass::getWord($invCount, array("запрос", "запроса", "запросов")) . ")"?></div>
                <div class="repos-list-elem-title-invite" >Пригласить</div>
                <div class="repos-list-elem-title-delete">Удалить</div>
            </div>
            <?php if ($invCount > 0) {?>
            <button class="repos-list-elem-invite-all">Добавить всех</button>
                <?php foreach ($rep["child_reps"] as $childRep) {
                    if ($arResult["status"][$rep["info"]["id"]][$childRep["rep_id"]][$childRep["user_id"]] == MainClass::$REP_USER_STATUS_INVITED) {
                        ?>
                        <div class="repos-list-elem-user">
                            <img class="repos-list-elem-user-menu-icon">
                            <div class="repos-list-elem-user-menu-popup">
                                <ul>
                                    <li><a href="#" class="js-accept-invite" data-field-id="<?php echo $childRep["id"]?>" data-user-rep-id="<?php echo $childRep["rep_id"];?>" data-rep-id="<?php echo $rep["info"]["id"]?>" data-user-id="<?php echo $childRep["user_id"]?>" data-curr-user="<?php echo $_SESSION["auth_info"]["user_id"]?>">Добавить</a>
                                    <li><a href="<?php echo MainClass::getUserLink($childRep["user_id"])?>">Профиль пользователя</a>
                                </ul>

                            </div>
                            <a href="<?php echo MainClass::getUserLink($childRep["user_id"])?>" class="repos-list-elem-user-name"><?php echo $childRep["user_name"] ?></a>
                        </div>
                    <?php }
                }?>
            <hr>
            <?php }?>
            <?php foreach ($rep["child_reps"] as $childRep) {
            if ($arResult["status"][$rep["info"]["id"]][$childRep["rep_id"]][$childRep["user_id"]] != MainClass::$REP_USER_STATUS_INVITED) {
            $date->setTimestamp($childRep["last_commit"]);

                ?>
            <div class="repos-list-elem-user">
                <img class="repos-list-elem-user-menu-icon">
                <div class="repos-list-elem-user-menu-popup">
                    <ul>
                        <li><a  class="js-delete-invite" href="#" data-field-id="<?php echo $childRep["id"]?>"  data-user-rep-id="<?php echo $childRep["rep_id"];?>" data-rep-id="<?php echo $rep["info"]["id"]?>" data-user-id="<?php echo $childRep["user_id"]?>" data-curr-user="<?php echo $_SESSION["auth_info"]["user_id"]?>">Исключить</a>
                        <li><a href="<?php echo MainClass::getUserLink($childRep["user_id"])?>">Профиль пользователя</a>
                        <li><a href="<?php echo "/reposit-catalog/detail.php?id=" . $rep["info"]["id"] . "#" . $childRep["user_id"]?>">На странице репозитория</a>
                    </ul>

                </div>
                <a href="<?php echo MainClass::getUserLink($childRep["user_id"])?>" class="repos-list-elem-user-name"><?php echo $childRep["user_name"]?></a>
                <div class="repos-list-elem-user-date">Последнее обновление: <?php echo $date->format("Y.m.d")?></div>
            </div>
            <?php }
            }?>
        </div>
        <?php }?>

    </div>
    <?php }?>
</div>



<?php if (!$arResult["user_info"]){?>
<div id="add-edu-rep-form" class="dialog-form">
    <form action="/reposit-catalog/ajax/add-ind-rep.php">
        <fieldset>
            <label for="rep_url">URL</label>
            <input type="text" name="rep_url" id="rep_url" value="" class="text ui-widget-content ui-corner-all" placeholder="Введите url...">

            <label for="disc">Выберите дисциплину</label>
            <select name="disc" id="disc">
                <?php foreach ($arResult["desciplines"] as $disc){?>
                    <option value="<?php echo $disc["id"]?>"><?php echo $disc["name"]?></option>
                <?php }?>
            </select>

            <label for="rep_descr">Описание</label>
            <textarea name="rep_descr" id="rep_descr" class="text ui-widget-content ui-corner-all" placeholder="Введите описание"></textarea>

            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type="submit" t abindex="-1" style="position:absolute; top:-1000px">
        </fieldset>
    </form>
</div>
<?php }?>