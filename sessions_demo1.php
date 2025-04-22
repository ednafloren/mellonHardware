<!-- SESSIONS -->
<!-- Asession is when a user opens up to when he closes an application -->
<!-- the server users a session variable to stores user info so that it can be used on
different pages in the applications eg user login info -->
<?php
// starting a session
session_start();
// creating session variables

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $_SESSION["favcolor"]="blue";
    $_SESSION['name']='john';
    ?>
</body>
</html>