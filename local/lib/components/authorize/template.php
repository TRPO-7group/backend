<?php
if ($arResult["result"])
{
    ?>
    <div class="header-user">
        <div class="header-user-info">
        <div class="user-info-name">
            <a href="#">
            <?php echo $arResult["result"]["name"]?>
            </a>
        </div>
        <div class="user-info-position">
            Teacher
        </div>
        <div class="user-info-profile">
            <a href=<?php echo $_SESSION["SCRIPT_NAME"] . "?logout=Y"?>>Выйти</a>
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

    <a href="<?php echo $arResult["auth_url"]?>">
        Авторизация с помощью Google
    </a>
</div>
<?php }?>