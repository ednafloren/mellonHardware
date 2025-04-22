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
    $categoryID = mysqli_real_escape_string($conn, $_POST["categoryID"]);
    $unitCostPrice = mysqli_real_escape_string($conn, $_POST["unitCostPrice"]);
    $sellingPrice = mysqli_real_escape_string($conn, $_POST["sellingPrice"]);
    $unitProfit = mysqli_real_escape_string($conn, $_POST["unitProfit"]);
    $quantity = mysqli_real_escape_string($conn, $_POST["quantity"]);
    $size = mysqli_real_escape_string($conn, $_POST["size"]);
    $color = mysqli_real_escape_string($conn, $_POST["color"]);
    $totalCostPrice = mysqli_real_escape_string($conn, $_POST["totalCostPrice"]);

    // Prepare an SQL statement for inserting the new record
    $sql = "INSERT INTO product (Name, CategoryID, UnitCostPrice, SellingPrice, UnitProfit, Quantity, Size, Color, TotalCostPrice) VALUES ('$name', '$categoryID', '$unitCostPrice', '$sellingPrice', '$unitProfit', '$quantity', '$size', '$color', '$totalCostPrice')";

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully";
    } else {
        echo "Error adding product: " . $conn->error;
    }
}

    
    // Fetch categories for dropdown list
    $categoryOptions = "";
    $sql = "SELECT ID, catName FROM category";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Populate dropdown options
            $selected = ($row['ID'] == $categoryID) ? "selected" : "";
            $categoryOptions .= "<option value='{$row['ID']}' $selected>{$row['catName']}</option>";
        }
    } else {
        echo "No categories found";
    }


// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="update.css">
</head>
<body>
    
<?php 
    include "sidebar.php"; // Include your sidebar
?>
    
    
    <div class="container">
    <div class="col1">
        <h5>Update Product</h5>
        <hr>
        <div class=".cardadd">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])  ?>">
            <div class="flexcontainer"> 
                 
                <div class="flex-item">  
            <label for="name" >Name:</label>
                <input type="text" id="name" name="name" ><br>
                </div>
                <div class="flex-item">  
                <label for="categoryID">Category:</label>
                <select class="ht" id="categoryID" name="categoryID">
                    <?php echo $categoryOptions; ?>
                </select><br>
                
                </div>
                <div class="flex-item">  
                <label for="unitCostPrice">Unit Cost Price:</label>
                <input type="text" id="unitCostPrice" name="unitCostPrice" ><br>
</div>
                <div class="flex-item">  
                <label for="sellingPrice">Selling Price:</label>
                <input type="text" id="sellingPrice" name="sellingPrice"><br>
                </div>
                <!-- <div class="flex-item"> 
                <label for="unitProfit">Unit Profit:</label>
                <input type="text" id="unitProfit" name="unitProfit"><br>
                
                </div> -->
                <div class="flex-item"> <label for="quantity">Quantity:</label>
                <input type="text" id="quantity" name="quantity" ><br>
                </div>
                <div class="flex-item"> 
                <label for="size">Size:</label>
                <input type="text" id="size" name="size" ><br>
                
                </div>
                <div class="flex-item"> <label for="color">Color:</label>
                <input type="text" id="color" name="color" ><br>
                </div>
                <!-- <div class="flex-item"> 
                <label for="totalCostPrice">Total Cost Price:</label>
                <input type="text" id="totalCostPrice" name="totalCostPrice" ><br>
</div> -->
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
