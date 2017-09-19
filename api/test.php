<?php
/*if (!mysql_connect("localhost", "id2965992_root", "12345678")) {
    echo "Ошибка подключения к серверу MySQL";
    exit;
}
// Соединились, теперь выбираем базу данных:
mysql_select_db("id2965992_main_db");
*/

$mysqli = new mysqli("localhost", "id2965992_root", "12345678", "id2965992_main_db");
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT * FROM repositories");

echo json_encode($result->fetch_all(MYSQLI_ASSOC));