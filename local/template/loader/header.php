<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/lib/php_init.php";?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Title</title>
    <link rel="stylesheet" href="/reposit-catalog/local/markup/build/default.css" type="text/css"/>
    <script src="/reposit-catalog/local/markup/build/js/jquery.js"></script>

    <script src="/reposit-catalog/local/markup/build/js/jquery-ui.js"></script>

    <script src="/reposit-catalog/local/markup/build/js/main.js"></script>
</head>
<div class="back loader">
</div>
<div id="loader"></div>
<header>
    <div class="header-section">

        <div class="header-logo">
            <img src="/reposit-catalog/local/markup/build/img/Git-Icon-White.png">
        </div>
        <?php
        MainClass::includeComponent("authorize","", array());
        ?>
        <div class="header-bottom-section">
            <div class="header-menu">
                <a href="/reposit-catalog/">Личные репозитории</a>
                <a href="/reposit-catalog/edu.php">Учебные репозитории</a>
                <img src="/reposit-catalog//local/markup/build/img/search.png" class="js-popup-search">
            </div>
        </div>
    </div>
</header>
<div class="popup" id="filter-popup">
    <div class="popup-content">
        <div class="popup-title">Фильтр</div>
        <form method="get">

            <div class="field">
                <div class="field-name">Язык</div>
                <select>
                    <option>Любой</option>
                    <option>php</option>
                    <option>C++</option>
                    <option>Java</option>
                </select>
            </div>

            <div class="field">
                <div class="field-name">Теги</div>
                <input type="text" placeholder="Введите, через запятую">
            </div>


            <div class="field">
                <div class="field-name">Сортировка</div>
                <select>
                    <option>По Названию</option>
                    <option>По дате изменения</option>
                </select>
            </div>
            <div class="submit-form">
                <input type="submit" value="Найти">
            </div>
        </form>
    </div>
</div>
<body class="scroll-hidden">