<?php
require $_SERVER["DOCUMENT_ROOT"]. "/reposit-catalog/local/lib/php_init.php";

if (!is_array($_POST["rep_id"]) && !is_array($_POST["user_id"]) && !is_array($_POST["user_rep"])) {
    $ar_rep_id = array(intval($_POST["rep_id"]));
    $ar_user_id = array(intval($_POST["user_id"]));
    //$ar_curr_user = array(intval($_POST["curr_user"]));
    $ar_user_rep = array(intval($_POST["user_rep"]));
}
else
{
    $ar_rep_id = ($_POST["rep_id"]);
    $ar_user_id = ($_POST["user_id"]);
    //$ar_curr_user = intval($_POST["curr_user"]);
    $ar_user_rep = ($_POST["user_rep"]);
}


for($i = 0; $i < count($ar_rep_id); $i++) {
    $rep_id = intval($ar_rep_id[$i]);
    $user_id = intval($ar_user_id[$i]);
   // $curr_user = intval($);
    $user_rep = intval($ar_user_rep[$i]);


    DB::updateRow("rep_user_status",
        "status=?",
        "rep_id=? AND user_id=? AND user_rep=?",
        array(MainClass::$REP_USER_STATUS_ACCEPTED, $rep_id, $user_id, $user_rep));
}