<?php
$rep = new Repository();
if ($params['id'] <= 0 || !$rep->loadById($params['id']))
{
    header("HTTP/1.1 404 Not Found");
    die;
}
$arResult["repository_name"] = $rep->getName();
$arResult["repository_description"] = $rep->getDescription();

$arResult["child_reps"] = $rep->getChildReps();

require $template . ".php";