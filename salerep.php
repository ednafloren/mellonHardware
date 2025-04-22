<?php
session_start();
// redirect the user to login
if(!isset($_SESSION['loggedin'])){
    header('location:login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="reports.css">
    <title class="title">SalesReport</title>
    
    
</head>
<body> 

<?php include "sidebar.php";?>
<div class="container">


<div class="right-section">
<div class="formrep"><?php include 'formrep.php';?></div>
<div class="printrep"><?php include 'printreport.html';?></div>
</div>
<?php include 'sales_report.php'?>
    <?php include "footer.php"?> <!-- Include your footer -->
    <?php include "navbar.php"?> 
</body>
    </html>
    