<?php
    require $_SERVER["DOCUMENT_ROOT"]. "/reposit-catalog/local/lib/php_init.php";
    $rep_id = intval($_GET["rep_id"]);
    $period = intval($_GET["period"]);
    $mask=false;
    if ($_GET["mask"])
    {
        $mask = explode(", ", $_GET["mask"]);
    }
    $template=$_GET["template"] ? $_GET["template"] : "individual";
    $arResult = MainClass::getRepDetailInfo($rep_id, $period, $mask);
    $arResult["js_commits"] = array();
    $arResult["js_lines"] = array();
    $arResult["js_files"] = array();
    foreach ($arResult["commit_chart"] as $date => $commits) {
        $arResult["js_commits"][] = "[\"$date\", $commits]";
    }
    foreach ($arResult["lines_chart"] as $date => $commits) {
        $arResult["js_lines"][] = "[\"$date\", " . $commits['add'] . ", " . $commits['delete'] . "]";
    }

    foreach ($arResult["files_chart"] as $date => $commits) {
        $arResult["js_files"][] = "[\"$date\", " . $commits['add'] . ", " . $commits['modifed'] . ", " . $commits["delete"] . "]";
    }


    $arResult["js_commits"] = implode(",", $arResult["js_commits"]);
    $arResult["js_lines"] = implode(",", $arResult["js_lines"]);
    $arResult["js_files"] = implode(",", $arResult["js_files"]);
if ($template == "individual")
    require "templates/individual.php";
else
    require "templates/edu.php";




