<?php

session_start();

$conn = new mysqli('localhost', 'micheal', 'micheal', 'flexflow');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = array(); // Store all error messages
$username="";
$password="";
// Define variables for error classes
$nameerrClass = "";
$passworderrClass = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure form data exists
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        // Prepare our SQL statement to prevent SQL injection
        if ($stmt = $conn->prepare('SELECT id, password FROM register WHERE username = ?')) {
            // Bind parameters
            $stmt->bind_param('s', $_POST['username']);
            $stmt->execute();
            // Store the result
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $password);
                $stmt->fetch();
                // Verify the password
                if (password_verify($_POST['password'], $password)) {
                    // Verification success! User has logged-in!
                    // Create sessions
                    session_regenerate_id();
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['name'] = $_POST['username'];
                    $_SESSION['id'] = $id;
                    header('Location: home.php');
                    exit;
                } else {
                    // Incorrect password
                    $errors[] = 'Incorrect password!';
                    $passworderrClass = 'error-input';
                }
            } else {
                // Incorrect username
                $errors[] = 'Incorrect username or password!';
                $nameerrClass = 'error-input';
            }
            $stmt->close();
        }
    } else {
        // Form fields are empty
        $errors[] = 'Both username and password are required!';
        $nameerrClass = 'error-input';
        $passworderrClass = 'error-input';
    }

    // Assign input values
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
    $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';

}

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
        <h1 class="loginheading"><b>LogIn</b></h1>
        <span class="error" id="nameErr">
                <?php echo isset($errors[0]) ? $errors[0] : ''; ?>
            </span><br />
            <span class="error" id="passwordErr">
                <?php echo isset($errors[1]) ? $errors[1] : ''; ?></span><br/>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" novalidate class="loginform">
            Username: <input type="text" name="username" value="<?php echo $username; ?>" class="<?php echo $nameerrClass; ?>" oninput="clearErrors(this)"/><br/><br/>
       
            Password: <input type="password" name="password" value="<?php echo $password; ?>" class="<?php echo $passworderrClass; ?>" oninput="clearErrors(this)"/><br/><br/>
         
            <input type="submit" value="login" class="loginbtn">
            <div class="redirect">
                <p>Don't Have An Account? | <a href="register.php">Create One</a></p>
                <a href="#">Forgot Password?</a>  
            </div> 
        </form>
        <span class="error"><?php echo isset($errors[2]) ? $errors[2] : ''; ?></span>
    </div>
    <script>
        // Function to clear all error messages
        function clearErrors(inputField) {
            inputField.classList.remove('error-input');
            document.getElementById('nameErr').textContent = '';
            document.getElementById('passwordErr').textContent = '';
            document.getElementById('error').textContent = '';
        }
    </script>
</body>
</html>
