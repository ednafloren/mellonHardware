<?php
 $nameErr=$emailErr=$ageErr="";
    // variable definition set to empty
    $name=$email=$age="";
    // variables for storing error  messages
    
    function input_test($data){
       ///removes whitespaces
        $data=stripslashes($data);// removes backslashes
        $data=htmlspecialchars($data);//converts special chars to html entities
        return $data;
    }

    if (isset($_POST['name'])){
        if(empty($_POST['name'])){
            $nameErr="Name is required";
        } else {
            $name = input_test($_POST['name']);
        }
        if(!preg_match("/^[a-zA-Z' ]*$/",$name)){
            $nameErr="Name must only contain letters whitespaces and appostropes";
        }
        $email=input_test($_POST['email']);
        if(empty($_POST['email'])){
            $emailErr="Email is required";

        }
        else {
            $email=input_test($_POST["email"]);

        }
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $emailErr="Invalid email format";
        }


    
    }
    if($_SERVER['REQUEST_METHOD']=="POST" && (!$nameErr) && (!$emailErr)){
$email=$name='';
    }
    // echo ($name);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORM</title>
    <style>
        .error{
            color:red;
        }
    </style>

    
</head>
<body>
<h3> Login</h3>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" novalidate>
Name:<span class="error"> * </span> &nbsp; <input type="text" name='name' value="<?php echo $name?>"/> <br />
<span class="error">  <?php echo $nameErr; ?></span>
 <br />
 Email:<span class="error"> * </span> &nbsp; <input type="email" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>"/> <br/>
        <span class="error"> <?php echo isset($emailErr) ? $emailErr : ''; ?></span>
        <br />

        


<input type="submit" value ="Login">
    </form>
</body>
</html>
