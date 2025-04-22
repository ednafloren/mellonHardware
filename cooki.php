<?php
// a cookie is a file embedded in a users computer by the server to identify the user
$cookie_name="user";
$cookie_value="john";
// creating a cookie
setcookie($cookie_name,$cookie_value,time()+ (86400 * 30), "/");//86400=1day
// deleting a cookie
// setcookie("user","john",time()+100);
//checking a cookie whethere is enabled
setcookie("test_cookie", "test", time() + 3600, '/');

?>
<html>
    <body>
        <?php
        // retrireving a cookie
        if(!isset($_COOKIE[$cookie_name])){
            echo "Cookie named '" . $cookie_name . "' is not set!<br>";
        } else {
          echo "Cookie '" . $cookie_name . "' is set!<br>";
          echo"Value is ".$_COOKIE[$cookie_name]." of user<br>";


        }
        echo "Cookie 'user' is deleted.<br>";
        // checking for enabled by counting the number of the $_COOKIE array variable:
        if(count($_COOKIE) > 0) {
            echo "Cookies are enabled.";
          } else {
            echo "Cookies are disabled.";
          }
        ?>
    </body>
</html>