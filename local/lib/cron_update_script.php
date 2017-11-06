<?php
require "php_init.php";
$list = MainClass::getRepositoryList(MainClass::$BOTH,false);
$repository = new Repository();
foreach ($list as $rep)
{
    $repository->loadById($rep["id"]);
    $repository->updateReposit();
}

file_put_contents("log.txt","CRON_WORK");