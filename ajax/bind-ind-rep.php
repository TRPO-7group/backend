<?php
require $_SERVER["DOCUMENT_ROOT"]. "/reposit-catalog/local/lib/php_init.php";

$user = MainClass::getUser();

if ($user["user_id"]) {
    $url = $_POST["rep_url"];
    $descr = $_POST["rep_descr"];
    $edu_rep = $_POST["edu_rep"];
    $id_rep = $_POST["rep"];
    if (!$id_rep) {
        $list = DB::getList("rep", "rep_id", false, "rep_url='" . $url ."'");
        if (!count($list))
            DB::insertRow("rep", array("rep_url", "rep_description", "rep_owner", "is_ind"), array($url, $descr, $user["user_id"], MainClass::$INDIVIDUAL));

        $list = DB::getList("rep", "rep_id", false, "rep_url='" . $url ."'");
        DB::updateRow("rep_user_status", "user_rep=?, status=?", "rep_id=? AND user_id=?",
            array($list[0]["rep_id"], MainClass::$REP_USER_STATUS_ACCEPTED, $edu_rep, $user["user_id"]));
    }
    else
    {
        DB::updateRow("rep_user_status", "user_rep=?, status=?", "rep_id=? AND user_id=?",
            array($id_rep, MainClass::$REP_USER_STATUS_ACCEPTED, $edu_rep, $user["user_id"]));
    }
}