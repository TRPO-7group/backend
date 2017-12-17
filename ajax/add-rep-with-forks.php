<?php
require $_SERVER["DOCUMENT_ROOT"]. "/reposit-catalog/local/lib/php_init.php";

$user = MainClass::getUser();

if ($user["user_id"]) {
    $url = $_POST["rep_url"];
    $descr = $_POST["rep_descr"];
    $disc = $_POST["disc"];
    MainClass::addRepWithForks($url, $descr, $disc, $user["user_id"]);
}