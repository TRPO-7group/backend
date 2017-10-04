<?php
/*
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/
require __DIR__ . "/../local/php-github-api/vendor/autoload.php";
require __DIR__ . "/../local/MainClass.php";

$client = new \Github\Client();
header('Content-Type: application/json');

switch ($_REQUEST["method"])
{
    case "user_info":
        $repositories = $client->api('user')->show($_REQUEST["args"]["login"]);
        echo json_encode($repositories);
        break;
    case "commits_list":
        $commits = $client->api("repos")->commits()->all($_REQUEST["args"]["login"],$_REQUEST["args"]["repository_name"], array());
        echo json_encode($commits);
        break;
    case "commit_info":
        $commit = $client->api("repos")->commits()->show($_REQUEST["args"]["login"],$_REQUEST["args"]["repository_name"], $_REQUEST["args"]["sha"]);
        echo json_encode($commit);
        break;
    case "events_list":
        $limit = intval($_REQUEST["args"]["limit"]);
        $idFrom = $_REQUEST["args"]["id_from"] ? $_REQUEST["args"]["id_from"] : false;
        $events = MainClass::getEventsList($client,$_REQUEST["args"]["login"], $limit, $idFrom, $_REQUEST["args"]["compare"]);
        echo json_encode($events);
        break;
    default: json_encode(array("unknown method"));
}
