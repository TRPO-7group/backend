<?php
require $_SERVER["DOCUMENT_ROOT"]. "/reposit-catalog/local/lib/php_init.php";
$user = MainClass::getUser();
if ($user["user_id"]) {
    $term = ($_GET["term"]);
    $list = DB::getList("user", "user_id, name, group_num", false, "(name LIKE '%" . $term . "%' OR group_num LIKE '%" . $term . "%') AND user_id <> " . $user["user_id"]);
    $result = array();
    foreach ($list as $item) {
        $result[] = array(
            "id" => $item["user_id"],
            "value" => $item["name"] . ($item["group_num"] ? " (" . $item["group_num"] . ")" : "")
        );
    }

    echo json_encode($result);
}