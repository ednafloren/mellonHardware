<?php
$emailErr=$passwordErr=$nameErr="";
    // variable definition set to empty
$email=$password=$name="";
    // variables for storing error  messages
    
    function input_test($data){
       ///removes whitespaces
        $data=stripslashes($data);// removes backslashes
        $data=htmlspecialchars($data);//converts special chars to html entities
        return $data;
    }

    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        if(empty($_POST['username'])){
            $nameErr="Name is required";
        } else {
            $name = input_test($_POST['username']);
        }
        if(!preg_match("/^[a-zA-Z' ]*$/",$name)){
            $nameErr="Name must only contain letters whitespaces and appostropes";
        }

        $email=input_test($_POST['email']);
        if(empty($_POST['email'])){
            $emailErr="Email is required";

        }
        else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $emailErr="Invalid email format";
        }
        else {
            $email=input_test($_POST["email"]);

        }
        $password=input_test($_POST['password']);
        if(empty($_POST['password'])){
            $passwordErr = "Password is required";
        } else if (strlen($password) < 8) {
            $passwordErr = "Password must be 8 characters or more";
        }
        else if(!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[^\w\s]).+$/',$password)){
            $passwordErr="Password must  contain letters,symbols, and numbers";
        }
       
       
        else{
        $password=input_test($_POST['password']);
        }
     
    }
    
    if ($_SERVER["REQUEST_METHOD"]=="POST"&& empty($emailErr)&&empty($passwordErr)&&empty($nameErr)){
        $email=$password=$name="";
    }
    // creating input error class
    $emailerrClass=$emailErr? 'error-input':'';
    $passworderrClass=$passwordErr? 'error-input':'';
    $nameerrClass=$nameErr? 'error-input':'';

    // echo ($name);
    ?>
   <?php
// Database connection
$conn = new mysqli('localhost', 'micheal', 'micheal', 'flexflow');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// v
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($emailErr) && empty($passwordErr) && empty($nameErr)) {
    // Only execute database insertion query if form fields are not empty
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        // Retrieve form data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Insert user data into database
        $sql = "INSERT INTO register (username, email, password) VALUES ('$username', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
     
    header("Location: home.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Form fields cannot be empty.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="forms.css">
    <title>FORM</title>
</head>
<body>
    <div class="main">
<h1 class="loginheading"><b>Register</b></h1>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" novalidate class="loginform">

Username: </span> &nbsp; <input type="text" name="username"  value="<?php echo $name;?>"class="<?php echo $nameerrClass;?>"oninput="clearErrors(this)"/>
<br/>
<span class="error"id="nameErr"> <?php echo $nameErr; ?></span>
<br />
Email: </span> &nbsp; <input type="email" name="email" id="email" value="<?php echo $email;?>"class="<?php echo $emailerrClass;?>"oninput="clearErrors(this)"/>
<br/>
<span class="error" id="emailErr"> <?php echo $emailErr; ?></span>
<br />
Password:&nbsp;

<input type="password" name="password" value="<?php echo $password;?>"class="<?php echo $passworderrClass;?>"oninput="clearErrors(this)"><br/>
<!-- /value for keep input values in input after a user clicks submit -->
<span class="error" id="passwordErr"> <?php echo $passwordErr; ?></span>
<br>

<input type="submit" value="Create" class="loginbtn">
<div class="redirect">
<p>Already Have An Account?|<a href="login.php">LogIn</a></p>

</div> 
</form>
</div>
<script>
        // Function to clear all error messages
        function clearErrors(inputField) {
            inputField.classList.remove('error-input');
            document.getElementById('nameErr').textContent = '';
            document.getElementById('passwordErr').textContent = '';
            document.getElementById('emailErr').textContent = '';
            document.getElementById('error').textContent = '';
        }
    </script>
</body>
</html>