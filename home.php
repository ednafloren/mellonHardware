<!-- <?php
session_start();
// redirect the user to login
if(!isset($_SESSION['loggedin'])){
    header('location:login.php');
    exit;
}
?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    
    <title>Home Page</title>
</head>
<body>
    
    <?php include "dashboard.php"?> <!-- Include your sidebar -->
    <?php include "sidebar.php"?> <!-- Include your sidebar -->
 <!-- <?php include "footer.php"?> -->

    <?php include "navbar.php"?> <!-- Include your navbar -->


</body>
</html>
