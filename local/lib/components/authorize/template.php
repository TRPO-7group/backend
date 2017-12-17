<?php
if ($arResult["result"])
{
    ?>
    <div class="header-user">
        <div class="header-user-info">
        <div class="user-info-name">
            <a href="<?php echo "/reposit-catalog/user.php?id=" . $arResult["result"]["user_id"]?>">
            <?php echo $arResult["result"]["name"]?>
            </a>
        </div>
        <div class="user-info-position">
            <?php if ($arResult["result"]["user_type"] == MainClass::$USER_TYPE_TEACHER)
                echo "Учитель"; else
                    echo "Студент";
                ?>
        </div>
        <div class="user-info-profile">
            <a href=<?php echo  "/reposit-catalog/?logout=Y"?>>Выйти</a>
        </div>
    </div>
        <div class="header-user-logo">
        <img src="<?php echo $arResult["result"]["preview_img"]?>">

    </div>

    </div>

<?php
} else if($arResult["auth_url"]){

?>

<div class="header-user authorize-link">
    <div>
        <a href="<?php echo $arResult["auth_url"] . "&auth_from=google"?>">
            Авторизация с помощью Google
        </a>
    </div>
    <div>
        <a href="<?php echo $arResult["auth_url"] . "&auth_from=github"?>">
            Авторизация с помощью GitHub
        </a>
    </div>
</div>
<?php }?>