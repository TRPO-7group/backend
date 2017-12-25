<?php

require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/lib/php_init.php";

$repository = new Repository();

header('Content-Type: application/json');

switch ($_REQUEST["method"])
{
    case "commits_list":
        $repository->loadById($_REQUEST["args"]["id"]);
        echo json_encode($repository->getUserCommits());
        break;
    case "rep_list":
        $type = MainClass::$BOTH;
        $group = false;
        if ($_REQUEST["args"]["type"] == "individual")
            $type = MainClass::$INDIVIDUAL;
        if ($_REQUEST["args"]["type"] == "edu")
            $type = MainClass::$EDU;
        if ($_REQUEST["args"]["group"] == "Y")
            $group = true;
       $user_id = false;
        if ($type == MainClass::$BOTH || $type == MainClass::$INDIVIDUAL)
        {
            if (intval($_REQUEST["args"]["user_id"]) > 0)
                $user_id = $_REQUEST["args"]["user_id"];
            else if($_REQUEST["args"]["user_email"])
            {
                $user = MainClass::getUserByEmail($_REQUEST["args"]["user_email"]);
                $user_id = $user["user_id"];
            } else break;
        }
        echo json_encode(MainClass::getRepositoryList($type, $group,false, false,$exists,$user_id));
        break;
    case "commit_info_lines_list":
        if ($_REQUEST["args"]["id"])
        {
            $repository->loadById($_REQUEST["args"]["id"]);
            echo json_encode($repository->getCommitInfoLinesList());
        }
        break;
    case "commit_info_files_list":
        if ($_REQUEST["args"]["id"])
        {
            $repository->loadById($_REQUEST["args"]["id"]);
            echo json_encode($repository->getCommitInfoFilesList());
        }
        break;
    case "rep_info":
        if ($_REQUEST["args"]["id"])
        {
            $repository->loadById($_REQUEST["args"]["id"]);
            echo json_encode($repository->getRepInfo());
        }
        break;
    case "detail_rep_info":
        if ($_REQUEST["args"]["id"] && $_REQUEST["args"]["period"])
        {
            $file_mask = false;
            if ($_REQUEST["args"]["mask"])
                $file_mask = explode(", ", $_REQUEST["args"]["mask"]);
            $res = MainClass::getRepDetailInfo($_REQUEST["args"]["id"],$_REQUEST["args"]["period"], $file_mask);
            unset($res["all_commits_list"]);
            echo json_encode($res);
        }
        break;
    case "mask_list":
        echo json_encode(MainClass::getMasks());
        break;
    default: echo json_encode("Error");
}
