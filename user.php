<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/main/header.php";?>
<?php
MainClass::includeComponent("lk","", array("id" => intval($_GET["id"])));
?>
<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/main/footer.php";?>