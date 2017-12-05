<?php
$user = MainClass::getUser();

if ($user["user_id"]) {
    $list = DB::getList("rep", "count(rep_id) as cnt", false, "is_ind=" . MainClass::$INDIVIDUAL . " AND rep_owner=" . $user["user_id"]);
    $arResult["cnt"] = $list[0]["cnt"];

    $list = DB::getList("rep", "*", array("disc" => array("rep.rep_disc", "disc.id")), "is_ind=" . MainClass::$INDIVIDUAL . " AND rep_owner=" . $user["user_id"] , $params["page"], $params["count_on_page"], $arResult["exist_next_page"]);


    $res = array();
    foreach ($list as $row) {
        $rep = new Repository();
        $rep->loadById($row["rep_id"]);
        $commits = $rep->getUserCommits();
        $tegsList = DB::getList("reptegs","*",
            array("teg" => array("reptegs.tegid", "teg.teg_id")),
            "repid=".$row["rep_id"]
        );
        $tegs = array();
        foreach ($tegsList as $item) {
            $tegs[] = $item["teg_name"];
        }
        $newRep = array(
            "id" => $row["rep_id"],
            "name" => MainClass::getRepositoryName($row["rep_url"]),
            "url" => $row["rep_url"],
            "description" => $row["rep_description"],
            "is_ind" => $row["is_ind"],
            "parent_rep" => $row["pater_rep"],
            "owner" => $row["rep_owner"],
            "discipline" => $row['name'],
            "last_commit" => $commits[0]["date"],
            "tegs" => $tegs,
            "link" => $rep->getLink()
        );
            $res[] = $newRep;
    }

    $arResult["items"] = $res;

    $arResult["next_page"] = $_SERVER['SCRIPT_NAME'] . "?page=" . ($params["page"] + 1);
    foreach ($arResult["items"] as &$item) {
        $item["tegs"] = implode(", ", $item["tegs"]);
    }
}
require __DIR__ . "/" . $template . ".php";
