<?php
require $_SERVER["DOCUMENT_ROOT"]. "/reposit-catalog/local/lib/php_init.php";
$rep_id = intval($_POST["rep_id"]);
$user_id = intval($_POST["user_id"]);
$curr_user = intval($_POST["curr_user"]);
$user_rep = intval($_POST["user_rep"]);

DB::deleteRow("rep_user_status",
    "rep_id=? AND user_id=? AND user_rep=?",
    array( $rep_id, $user_id, $user_rep)
);
