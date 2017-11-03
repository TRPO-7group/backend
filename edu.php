<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/header.php";?>
    <div class="title">
        <h1>Список учебных репозиториев</h1>
    </div>

<?php
MainClass::includeComponent("edu-reps-list","",array());
?>
<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/footer.php";?>