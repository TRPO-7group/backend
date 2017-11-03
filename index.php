<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/header.php";?>
<div class="title">
    <h1>Список личных репозиториев</h1>
</div>

<?php
MainClass::includeComponent("reps-list","", array("count_on_page" => 5, "page" => $_GET["page"] ? $_GET["page"] : 1));
?>
<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/footer.php";?>