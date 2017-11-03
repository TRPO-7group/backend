
<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define("SERVER_PATH_ROOT", $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog");
define("COMPONENT_PATH", SERVER_PATH_ROOT . "/local/lib/components");
require_once "dbconf.php";
require_once "MainClass.php";
require_once "Repository.php";
require_once "Cache.php";
