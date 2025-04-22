<!DOCTYPE html>
 <html>
    <head>
        <title>arrays</title>
    </head>
<?php
$myarr=array("books","pens","chalk");
#changing values
$myarr[0]="colors";
echo $myarr[0]."<br>";
echo count($myarr);
var_dump($myarr);
#looping through the array
foreach($myarr as $x){
    echo($x."<br>");
}
// adding a new item using array_push .it is put at the back
array_push($myarr,"books");
var_dump($myarr);
// associative arrays have keys and values,
$student=array("name"=>"john","age"=>10);
var_dump($student);
echo($student["name"]."<br>");
/*changing the value  */
$student["name"]="mark";
echo($student["name"]<"br>");


//*looping
foreach($student as $k=>$v){
    echo"$k:$v<br>";
}
/*executing a function item ina n array*/
function myfunc(){
    echo("this is a function in an array");
}
$myarray=["cup",15,"myfunc"];
// calling the function
$myarray[2]();

?>
</html>