<?php
/*Filter helps to sanitize and validate inpuy
Validate ensures that input iput is proper
Sanitize is to remove illegal 
Filter extension has many functions*/
// filter_list() shows a list of filter functions in the filter extension
foreach(filter_list() as $filter){
    echo $filter."<br>".filter_id($filter)."<br>";
}
$str="<h1>hello3</h1>";
$int=3;
// filter_var()checks whether the date is valid and FILTER_VALIDATE_STRING is a 
//built in constant that defines the type of check to use
if(!filter_var($int,FILTER_VALIDATE_INT)=== false){
    echo "Number is valid<br>";
}else{
echo "string is invalid <br>";
}
// santizer the string to remove html characters
$NEWSTR=filter_var($str,FILTER_SANITIZE_STRING);
echo $NEWSTR."<br>";
// email vadilation
$email= "john.doe@example.>com";
$newemail=filter_var($email,FILTER_SANITIZE_EMAIL);
if(!filter_var($newemail,FILTER_VALIDATE_EMAIL)=== false){
    echo "email is valid<br>";
}else{
echo "email is invalid <br>";
}
echo $newemail."<br>";
//filters advanced 
 //finding a value with in  a range 
 $inte=44;
 $max=100;
 $min=20;
 if(filter_var($inte,FILTER_VALIDATE_INT,array('options'=>array("min_range"=>$min,"max_range"=>$max)))===false){
    echo("Variable value is not within the legal range");
} else {
  echo("Variable value is within the legal range");
} 
// querystring is a string stored in the url
$url = "https://www.w3schools.com";

if (!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED) === false) {
  echo("$url is a valid URL with a query string");
} else {
  echo("$url is not a valid URL with a query string <br>");
}
$ip = "2001:0db8:85a3:08d3:1319:8a2e:0370:7334";

if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
  echo("$ip is a valid IPv6 address");
} else {
  echo("$ip is not a valid IPv6 address");
}
?>

