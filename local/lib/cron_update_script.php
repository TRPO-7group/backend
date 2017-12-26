<?php
require "php_init.php";
$list = DB::getList("rep", "rep_id");
$repository = new Repository();
foreach ($list as $rep)
{
    $repository->loadById($rep["rep_id"]);
    $repository->updateReposit();
}

$cahchePath = array(SERVER_PATH_ROOT . "/cache/info_files", SERVER_PATH_ROOT . "/cache/info_lines");
// Удалим невалидные кэши
foreach ($cahchePath as $path) {
    if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (file_exists($file)) {
                    $data = unserialize(file_get_contents($file));
                    if (time() > ($data['time'] + $data['ttl'])) {
                        unlink($path . "/" . $file);
                    }
                }
            }
        }
        closedir($handle);
    }
}
file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/log.txt","CRON_WORK");