<?php
/*
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/
require_once __DIR__ . "/../local/php-github-api/vendor/autoload.php";
require_once __DIR__ . "/../local/lib/Repository.php";
require_once __DIR__ . "/../local/lib/MainClass.php";

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
        echo json_encode(MainClass::getRepositoryList($type, $group));
        break;
    case "commit_info_files":
        if ($_REQUEST["args"]["id"] && $_REQUEST["args"]["sha"]) {
            $repository->loadById($_REQUEST["args"]["id"]);
            echo json_encode($repository->getCommitInfoFiles($_REQUEST["args"]["sha"]));
        }
        break;
    case "commit_info_files_list":
        if ($_REQUEST["args"]["id"])
        {
            $repository->loadById($_REQUEST["args"]["id"]);
            echo json_encode($repository->getCommitInfoFilesList());
        }
        break;
    default: echo json_encode("Error");
}
