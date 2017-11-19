<?php
$rep = new Repository();
if ($params['id'] <= 0 || !$rep->loadById(intval($params['id'])))
{
    header("HTTP/1.1 404 Not Found");
    die;
}
$cache = new Cache();
$arResult = $cache->load("detail_" . $params['id']);
if (!$arResult) {
    $arResult["repository_id"] = $rep->getId();
    $arResult["repository_name"] = $rep->getName();
    $arResult["repository_description"] = $rep->getDescription();
    $arResult["repository_url"] = $rep->getUrl();
    $arResult['commits_list'] = $rep->getUserCommits();
    $arResult['commits_lines'] = $rep->getCommitInfoLinesList();
    $arResult['commits_files'] = $rep->getCommitInfoFilesList();
    $arResult["repository_owner"] = $rep->getOwner();

    $dateNow = new DateTime();

    $arResult['dates'] = array();
    foreach ($arResult['commits_list'] as $commit) {
        $dateNow->setTimestamp($commit["date"]);
        $arResult['dates'][$dateNow->format("d.m")][] = $commit["sha"];
    }

    $arResult['commit_chart'] = array();
    $arResult['lines_chart'] = array();
    $arResult['files_chart'] = array();

    $arResult["all_lines_add"] = 0;
    $arResult["all_lines_delete"] = 0;

    $arResult["all_files_add"] = 0;
    $arResult["all_files_delete"] = 0;
    $arResult["all_files_modified"] = 0;
    $dateNow = new DateTime();

    for ($i = 0; $i < 30; $i++) {
        $formated = $dateNow->format("d.m");
        $arResult['commit_chart'][$formated] = 0;
        $arResult['lines_chart'][$formated] = array("add" => 0, "delete" => 0);
        $arResult["files_chart"][$formated] = array("add" => 0, "modifed" => 0, "delete" => 0);
        if (array_key_exists($formated, $arResult["dates"])) {
            foreach ($arResult["dates"][$formated] as $sha) {
                $arResult['commit_chart'][$formated]++;
                foreach ($arResult["commits_lines"][$sha] as $commit) {
                    $arResult["all_lines_add"] += $commit['add'];
                    $arResult["all_lines_delete"] += $commit['delete'];

                    $arResult['lines_chart'][$formated]["add"] += $commit['add'];
                    $arResult['lines_chart'][$formated]["delete"] += $commit['delete'];
                }

                foreach ($arResult["commits_files"][$sha]["A"] as $file) {
                    $filesAddForPopup[$formated][] = "+ " . $file;
                }

                foreach ($arResult["commits_files"][$sha]["M"] as $file) {
                    $filesModifiedForPopup[$formated][] = $file;
                }

                foreach ($arResult["commits_files"][$sha]["D"] as $file) {
                    $filesDeleteForPopup[$formated][] = "- " . $file;
                }
                $arResult["all_files_add"] += count($arResult["commits_files"][$sha]["A"]);
                $arResult["all_files_modified"] += count($arResult["commits_files"][$sha]["M"]);
                $arResult["all_files_delete"] += count($arResult["commits_files"][$sha]["D"]);

                $arResult["files_chart"][$formated]['add'] += count($arResult["commits_files"][$sha]["A"]);
                $arResult["files_chart"][$formated]['modifed'] += count($arResult["commits_files"][$sha]["M"]);
                $arResult["files_chart"][$formated]['delete'] += count($arResult["commits_files"][$sha]["D"]);
            }
        }
        $dateNow->sub(new DateInterval("P1D"));
    }

    /*
    foreach ($filesAddForPopup as $key => $date){
        if (count($date))
        {
            $arResult["files_add_for_popup"][] = "<b>$key</b>";
            foreach ($date as $file)
            {
                $arResult["files_add_for_popup"][] = "+ $file";
            }
        }
    }
    */
    $arResult["files_add_for_popup"] = implode("\n", $arResult["files_add_for_popup"]);
    $arResult["commit_chart"] = array_reverse($arResult["commit_chart"]);
    $arResult["files_chart"] = array_reverse($arResult["files_chart"]);
    $arResult["lines_chart"] = array_reverse($arResult["lines_chart"]);
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
    $cache->save("detail_" . $params['id'],$arResult,30*60);
}

require $template . ".php";





