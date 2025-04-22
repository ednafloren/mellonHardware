<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="update.css">
<body>
    
<?php 
    include "sidebar.php"; // Include your sidebar
    
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
    
    if (!isset($_GET['id'])) {
        die("ID parameter missing");
    }
    
    // Retrieve the ID parameter from the URL
    $id = $_GET['id'];
    
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
    
        // Prepare an SQL statement for updating the record
        $sql = "UPDATE product SET Name='$name', CategoryID='$categoryID', UnitCostPrice='$unitCostPrice', SellingPrice='$sellingPrice', UnitProfit='$unitProfit', Quantity='$quantity', 
        Size='$size', Color='$color', TotalCostPrice='$totalCostPrice' WHERE ID='$id'";
    // Fetch the record with the specified ID
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
    $sql = "SELECT * FROM product WHERE ID='$id'";
    $result = $conn->query($sql);
    
    // Check if the record exists
    if ($result->num_rows > 0) {
        // Retrieve data from the record
        $row = $result->fetch_assoc();
        $name = $row["Name"];
        $categoryID = $row["CategoryID"];
        $unitCostPrice = $row["UnitCostPrice"];
        $sellingPrice = $row["SellingPrice"];
        $unitProfit = $row["UnitProfit"];
        $quantity = $row["Quantity"];
        $size = $row["Size"];
        $color = $row["Color"];
        $totalCostPrice = $row["TotalCostPrice"];
    } else {
        echo "Record not found";
        exit;
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
    
<div class="container">
    <div class="col1">
        <h5>Update Product</h5>
        <hr>
        <div class="card1">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>">
            <div class="flexcontainer"> 
                 
                <div class="flex-item">  
            <label for="name" >Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>"><br>
                </div>
                <div class="flex-item">  
                <label for="categoryID">Category:</label>
                <select class="ht" id="categoryID" name="categoryID">
                    <?php echo $categoryOptions; ?>
                </select><br>
                
                </div>
                <div class="flex-item">  
                <label for="unitCostPrice">Unit Cost Price:</label>
                <input type="text" id="unitCostPrice" name="unitCostPrice" value="<?php echo $unitCostPrice; ?>"><br>
</div>
                <div class="flex-item">  
                <label for="sellingPrice">Selling Price:</label>
                <input type="text" id="sellingPrice" name="sellingPrice" value="<?php echo $sellingPrice; ?>"><br>
                </div>
                <div class="flex-item"> 
                <label for="unitProfit">Unit Profit:</label>
                <input type="text" id="unitProfit" name="unitProfit" value="<?php echo $unitProfit; ?>"><br>
                
                </div>
                <div class="flex-item"> <label for="quantity">Quantity:</label>
                <input type="text" id="quantity" name="quantity" value="<?php echo $quantity; ?>"><br>
                </div>
                <div class="flex-item"> 
                <label for="size">Size:</label>
                <input type="text" id="size" name="size" value="<?php echo $size; ?>"><br>
                
                </div>
                <div class="flex-item"> <label for="color">Color:</label>
                <input type="text" id="color" name="color" value="<?php echo $color; ?>"><br>
                </div>
                <div class="flex-item"> 
                <label for="totalCostPrice">Total Cost Price:</label>
                <input type="text" id="totalCostPrice" name="totalCostPrice" value="<?php echo $totalCostPrice; ?>"><br>
</div>
</div>
<div class="button-container">
        <input type="submit" value="Submit">
    </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; // Include your footer ?>
<?php include "navbar.php"; // Include your navbar ?>

</body>
</html>
