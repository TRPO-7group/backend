<?php
require $_SERVER["DOCUMENT_ROOT"]. "/reposit-catalog/local/lib/php_init.php";
$id_rep = intval($_GET["rep_id"]);
DB::deleteRow("rep","rep_id=?",array($id_rep));
DB::deleteRow("reptegs", "repid=?", array($id_rep));
DB::deleteRow("rep_user_status", "rep_id=?", array($id_rep));
