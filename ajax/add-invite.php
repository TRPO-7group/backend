<?php
require $_SERVER["DOCUMENT_ROOT"]. "/reposit-catalog/local/lib/php_init.php";
$rep_id = intval($_POST["rep_id"]);
$user_id = intval($_POST["user_id"]);
$current_user = intval($_POST["current_user"]);

if ($rep_id > 0 && $user_id > 0)
    DB::insertRow("rep_user_status",array("rep_id", "user_id" ,"status"), array($rep_id, $user_id, MainClass::$REP_USER_STATUS_TEACHER_INVITE));
