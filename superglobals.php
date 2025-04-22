<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>superglobals</title>
</head>
<body>
    <h2>Superglobals</h2>
    <p>These are inbuilt variables that can accessed anywhere</p></body>
<p>$GLOBALS is an array that contains all global variables.</p>
<?php
// refering a global variable inside a function
$x=5;
function myfunction(){
    echo $GLOBALS["x"]."<br>";
    // or it can be defined by
    global $x;

}
myfunction();
// creating a global variable inside a function
function createglobal(){
$GLOBALS["y"]=6;

}
createglobal();
echo $y;

?>
<p>$_SERVER is a PHP super global variable which holds information about headers, paths, and script locations.</p>
<?php
echo $_SERVER['PHP_SELF'];
echo "<br>";
echo $_SERVER['SERVER_NAME'];
echo "<br>";
echo $_SERVER['HTTP_HOST'];
echo "<br>";
echo $_SERVER['HTTP_REFERER'];
echo "<br>";
echo $_SERVER['HTTP_USER_AGENT'];
echo "<br>";
echo $_SERVER['SCRIPT_NAME'];
echo "<br>";
echo $_SERVER['SERVER_PORT'];
echo "<br>";
echo $_SERVER['SERVER_ADDR'];
echo "<br>";
echo $_SERVER['REMOTE_ADDR'];
echo "<br>";
echo("REQUEST is a PHP super global variable which contains submitted form data, and all cookie data."."<br>".
" REQUEST is an array containing data from GET, POST, and COOKIE.");
?>
    </html>