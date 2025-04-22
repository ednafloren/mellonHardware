
<?php
session_start();
// redirect the user to login
if(!isset($_SESSION['loggedin'])){
    header('location:login.php');
    exit;
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales and Purchases Reports</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="reportss.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">

<script>
    function toggleDetails(date) {
        var detailsRow = document.getElementById('details-' + date);
        if (detailsRow.style.display === 'none') {
            detailsRow.style.display = 'table-row';
        } else {
            detailsRow.style.display = 'none';
        }
    }

    
</script>

</head>
<body>
<?php include 'sidebar.php'?>
<div class="container">
<div class="right-section">
<div class="formrep"><?php include 'formrep.php';?></div>
<div class="printrep"><?php include 'printreport.html';?></div>
</div>
    <h1>Sales and Purchases Reports</h1>

 
    
    <?php
    include 'sales_report.php';
    



    include 'purchasesreport.php';?>
    </div>
    <?php include "footer.php";
   include "navbar.php"; ?>

</body>
</html>
