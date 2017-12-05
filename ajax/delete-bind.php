<?php
require $_SERVER["DOCUMENT_ROOT"]. "/reposit-catalog/local/lib/php_init.php";

$user = MainClass::getUser();
$rep_id = $_POST["rep_id"];


DB::deleteRow("rep_user_status", "rep_id=? AND user_id=?", array($rep_id, $user["user_id"]));