<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/main/header.php";?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
$rep = new Repository();
$rep->loadById($_GET['id']);
if ($rep->getIndividual()) {
    MainClass::includeComponent("rep-detail", "", array("id" => $_GET['id']));
}
else
{
    MainClass::includeComponent("edu-rep-detail", "", array("id" => $_GET['id']));
}
?>

<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/main/footer.php";?>
