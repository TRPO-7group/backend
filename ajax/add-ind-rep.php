<?php
require $_SERVER["DOCUMENT_ROOT"]. "/reposit-catalog/local/lib/php_init.php";
$user = MainClass::getUser();

if ($user["user_id"]) {
    $url = $_POST["rep_url"];
    $descr = $_POST["rep_descr"];
    $list = DB::getList("rep", "rep_id", false, "rep_url='" . $url . "'");
    if (!count($list))
        DB::insertRow("rep", array("rep_url", "rep_description", "rep_owner", "is_ind"), array($url, $descr, $user["user_id"], MainClass::$INDIVIDUAL));
}