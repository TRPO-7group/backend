<?php
require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/php-github-api/vendor/autoload.php";
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$client = new \Github\Client();

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
    default: json_encode(array("unknown method"));
}
