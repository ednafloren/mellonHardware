<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORM</title>
</head>
<body>
  <form method='post' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
<input type="text" name='fname'>
<input type="submit">
</form>
<a href="testing.php?subject=php&class=s.6&school=Bootim">testing $GET from the query<br></a>

<?php
echo ($_POST['fname']).'<br>';
if($_SERVER['REQUEST_METHOD']=='POST'){
    // htmlspecialchars — Convert special characters to HTML entities/content

    $name=htmlspecialchars($_REQUEST['fname']);
    if(empty($name)){
    echo 'Name is empty';
    }
    else
    echo $name;
}
?>
</body>
</html>