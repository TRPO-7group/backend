<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/header.php";?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
MainClass::includeComponent("rep-detail","",array("id" => $_GET['id']));
?>

<?php require $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/local/template/footer.php";?>
