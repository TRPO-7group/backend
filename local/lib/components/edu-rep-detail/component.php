<?php
$rep = new Repository();
if ($params['id'] <= 0 || !$rep->loadById($params['id']))
{
    header("HTTP/1.1 404 Not Found");
    die;
}

$arResult["child_reps"] = $rep->getChildReps();

require $template . ".php";