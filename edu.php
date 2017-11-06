<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/main/header.php";?>
    <div class="title">
        <h1>Список учебных репозиториев</h1>
    </div>

<?php
MainClass::includeComponent("edu-reps-list","",array());
?>
<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/main/footer.php";?>