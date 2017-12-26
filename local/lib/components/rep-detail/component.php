<?php
$rep = new Repository();
if ($params['id'] <= 0 || !$rep->loadById(intval($params['id'])))
{
    header("HTTP/1.1 404 Not Found");
    die;
}


    $arResult["repository_id"] = $rep->getId();
    $arResult["repository_name"] = $rep->getName();
    $arResult["repository_description"] = $rep->getDescription();
    $arResult["repository_url"] = $rep->getUrl();
    $arResult['commits_list'] = $rep->getUserCommits(false, $lastCommit);
    $arResult['commits_lines'] = $rep->getCommitInfoLinesList();
    $arResult['commits_files'] = $rep->getCommitInfoFilesList();
    $arResult["repository_owner"] = $rep->getOwner();
    $arResult["masks_list"] = MainClass::getMasks();
require $template . ".php";





