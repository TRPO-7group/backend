<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/main/header.php";?>
<?php
$user =DB::getList("user", "*", false, "user_id = " . intval($_GET["id"]));
if ($user[0]["user_type"] == MainClass::$USER_TYPE_TEACHER)
    MainClass::includeComponent("lk","", array("id" => intval($_GET["id"])));
else
    MainClass::includeComponent("lk-student","", array("id" => intval($_GET["id"])));
?>
<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/main/footer.php";?>