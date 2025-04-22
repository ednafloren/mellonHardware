User
<?php
$conn=new mysqli("localhost","ed","ed","flexflow");
if ($conn->connect_error) {
die("connection failed: " . $conn->connect_error);	
	
	
}
echo "connection";
$conn->close();
	
	
?>


<!DOCTYPE html>
<html>
<head>
<title>THIS IS DOCUMENTS TITLE</title>
<link rel="stylesheet"  href="./css/style.css"/>
<link rel="stylesheet"  href="./css/all.min.css" />

<style>

</style>
</head>
<body>
<div class= "container">


<div  class="heading"> 
<div class="titles">
<div class="julie" ><h1 >Messanger</h1>
</div>
<div class="symbols">

<span><i class="fa fa-search"></i></span>
<span><i class="fa fa-camera"></i></span>
<span><i class="fa fa-ellipsis-v"></i></span>

</div>
</div>
<div class ="links">

<li>Groups</li>
<li>Chats</li>
<li>posts</li>
<li>Notices</li>

</div>
</div>
<div class="charts">
<div class ="row">
<div >
<img src ="naiga.jpg" class="image"></div>
<div class ="text">
<p>MUMPE GEOFREY<br>I can't get a clear picture</p>

</div>
<div class=" status">
<p>Today</p>
</div>


</div>
<div class ="row">
<div >
<img src ="naiga.jpg" class="image"></div>
<div class ="text">
<p>MUMPE GEOFREY<br>I can't get a clear picture</p>

</div>
<div class=" status">
<p>Yesterday</p>
</div>


</div>
<div class ="row">
<div >
<img src ="naiga.jpg" class="image"></div>
<div class ="text">
<p>MUMPE GEOFREY<br>I can't get a clear picture</p>

</div>
<div class=" status">
<p>23/01/2024</p>
</div>


</div>
<div class ="row">
<div >
<img src ="naiga.jpg" class="image"></div>
<div class ="text">
<p>MUMPE GEOFREY<br>I can't get a clear picture</p>

</div>
<div class=" status">
<p>23/02/2024</p>
</div>


</div>
<div class ="row">
<div >
<img src ="naiga.jpg" class="image"></div>
<div class ="text">
<p>MUMPE GEOFREY<br>I can't get a clear picture</p>

</div>
<div class=" status">
<p>Yesterday</p>
</div>


</div>
<div class ="row">
<div >
<img src ="naiga.jpg" class="image"></div>
<div class ="text">
<p>MUMPE GEOFREY<br>I can't get a clear picture</p>

</div>
<div class=" status">
<p>Yesterday</p>
</div>


</div>
</div>
</div>
</body>
</html>