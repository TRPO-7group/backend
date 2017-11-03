<?php
$arResult["items"] = MainClass::getRepositoryList(MainClass::$EDU,true);
$arResult["count_items"] = 0;
foreach ($arResult["items"] as &$groups)
{
    foreach ($groups as &$item) {
        $item["tegs"] = implode(", ", $item["tegs"]);
        $arResult["count_items"]++;
    }
}
require __DIR__ . "/" . $template . ".php";