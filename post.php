<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post method</title>
</head>
<body>
    <h2>Posts from html forms</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
        lname:
        <input type="text" name='lname'>
        <input type="submit"value="login">
    </form>
    <?php
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $name=htmlspecialchars($_POST['lname']);
        if (empty($name)) {
            echo "Name is empty";
          } else {
            echo $name;
          }
    }
    ?>
</body>
</html>