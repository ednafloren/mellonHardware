<?php
session_start();

// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}

include "config.php"; // Include your database configuration

// Initialize variables
$name = "";
$id = "";

// Check if ID is provided in the URL
if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = "Category ID not provided.";
    header("Location: index.php");
    exit();
}

// Retrieve the ID parameter from the URL
$id = $_GET['id'];

// Fetch the record with the specified ID
$sql = "SELECT * FROM category WHERE ID='$id'";
$result = $conn->query($sql);

// Check if the record exists
if ($result->num_rows > 0) {
    // Retrieve data from the record
    $row = $result->fetch_assoc();
    $name = $row["catName"];
} else {
    $_SESSION['error_message'] = "Category not found.";
    header("Location: index.php"); // Redirect to category list page if category not found
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = mysqli_real_escape_string($conn, $_POST["name"]);

    // Prepare an SQL statement for updating the record
    $sql_update = "UPDATE category SET catName='$name' WHERE ID='$id'";

    if ($conn->query($sql_update) === TRUE) {
        $_SESSION['success_message'] = "Category updated successfully";
        header("Location: index.php"); // Redirect to category list page after successful update
        exit();
    } else {
        $_SESSION['error_message'] = "Error updating category: " . $conn->error;
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
    <title>Update Category</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="update.css">
</head>
<body>

<?php include "sidebar.php"; ?> <!-- Include your sidebar -->

<div class="container">
    <div class="col1">
        <h5>Update Category</h5>
        <hr>
        <div class="cardadd">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>">
                <div class="flexcontainer"> 
                    <div class="flex-item">  
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>"><br>
                    </div>
                </div>
                <div class="button-container">
                    <input type="submit" value="Update">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?> <!-- Include your footer -->
<?php include "navbar.php"; ?> <!-- Include your navbar -->

</body>
</html>
