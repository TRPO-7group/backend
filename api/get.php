<?php
/*
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/
require __DIR__ . "/../local/php-github-api/vendor/autoload.php";
require __DIR__ . "/../local/lib/Repository.php";

$repository = new Repository();

header('Content-Type: application/json');

switch ($_REQUEST["method"])
{
    case "commits_list":
        $repository->loadById($_REQUEST["args"]["id"]);
        echo json_encode($repository->getUserCommits());
        break;
    default: echo json_encode("Error");
}
