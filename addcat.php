<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}

// Step 1: Connect to the database
$servername = "localhost"; // Change this to your database server name
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "flexflow"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$categoryID = ""; // Initialize categoryID variable

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize
    $name = mysqli_real_escape_string($conn, $_POST["name"]);

    // Prepare an SQL statement for inserting the new record
    $sql = "INSERT INTO category (catName) VALUES ('$name')";

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        echo "New category added successfully";
    } else {
        echo "Error adding category: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="update.css">
</head>
<body>
    
<?php include "sidebar.php"; // Include your sidebar ?>
    
<div class="container">
    <div class="col1">
      
       
        <div class="cardadd">
        <h2>Add Category</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="flexcontainer"> 
                    <div class="flex-item">  
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name"><br>
                    </div>
                </div>
                <div class="button-container">
        <input type="submit" value="Add">
    </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; // Include your footer ?>
<?php include "navbar.php"; // Include your navbar ?>

</body>
</html>
