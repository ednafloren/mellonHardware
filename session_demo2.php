<!-- display session variable -->
<?php
session_start();

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
echo "Favorite color is " . $_SESSION["favcolor"] . ".<br>";
echo "Name is " . $_SESSION["name"] . ".";
// or
print_r($_SESSION)
?>
</body>
</html>