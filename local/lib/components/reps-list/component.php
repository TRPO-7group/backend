<?php

$arResult["items"] = MainClass::getRepositoryList(MainClass::$INDIVIDUAL,false,$params["page"], $params["count_on_page"], $arResult["exist_next_page"]);
$arResult["next_page"] = $_SERVER['REQUEST_URI'] . "?page=" . ($params["page"] + 1);
foreach ($arResult["items"] as &$item)
{
    $item["tegs"] = implode(", ", $item["tegs"]);
}
require __DIR__ . "/" . $template . ".php";
