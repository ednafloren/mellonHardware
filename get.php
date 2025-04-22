<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GET</title>
</head>
<body>
    <H2>Get method</H2>
    <h3>$_GET in HTML Forms</h3>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="get">

Name<input type="text"name='name'><br>
Email:<input type="email" name="email" id="email"><br>
Age:
<input type="number" name="age" id="age"><br>
<input type="submit">
    </form>
    Welcome <?php echo $_GET["name"]; ?><br>
Your email address is: <?php echo $_GET["email"]; ?><br>
<h3>Query string in the URL</h3>

    <?php
if ($_SERVER['REQUEST_METHOD']=='GET'){
    $myage=$_GET['age'];
    if (empty($myage)) {
        echo "Age is empty";
      } else {
        echo $myage."<br>";
      }
}
    ?>
<a href="testing.php?subject=php&class=s.6&school=Bootim"><br>testing $GET from the query<br></a>

</body>
</html>