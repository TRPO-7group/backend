<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/lib/php_init.php";?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="WINDOWS-1251">
    <title>Title</title>
    <link rel="stylesheet" href="/reposit-catalog/local/markup/main.css" type="text/css"/>
    <script src="http://code.jquery.com/jquery-2.0.2.min.js"></script>
    <script src="/reposit-catalog/local/markup/main.js"></script>
</head>
<div class="back">
</div>
<header>
    <div class="header-section">

        <div class="header-logo">
            <img src="/reposit-catalog/local/markup/img/Git-Icon-Black.png">
        </div>
        <div class="header-user">
            <div class="header-user-info">
                <div class="user-info-name">
                    Ivanov Ivan
                </div>
                <div class="user-info-position">
                    Teacher
                </div>
                <div class="user-info-profile">
                    <a href="#">Профиль пользователя</a>
                </div>
            </div>
            <div class="header-user-logo">
                <img src="/reposit-catalog//local/markup/img/blank-person-390x390.png">
            </div>
        </div>
        <div class="header-bottom-section">
            <div class="header-menu">
                <a href="#">Личные репозитории</a>
                <a href="#">Учебные репозитории</a>
                <img src="/reposit-catalog//local/markup/img/search.png" class="js-popup-search">
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
<body>
<div class="title">
    <h1>Список личных репозиториев</h1>
</div>

<?php
MainClass::includeComponent("reps-list","", array("count_on_page" => 5, "page" => $_GET["page"] ? $_GET["page"] : 1));
?>

</body>


</html>