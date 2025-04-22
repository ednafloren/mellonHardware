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
</head>
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
        $date = mysqli_real_escape_string($conn, $_POST["date"]);
        $productID = mysqli_real_escape_string($conn, $_POST["productID"]);
        $unitPrice = mysqli_real_escape_string($conn, $_POST["unitPrice"]);
        $quantity = mysqli_real_escape_string($conn, $_POST["quantity"]);
        $totalPrice = mysqli_real_escape_string($conn, $_POST["totalPrice"]);
        
        // Prepare an SQL statement for updating the record
        $sql = "UPDATE sales SET SaleDate='$date', ProductID='$productID', UnitPrice='$unitPrice', Quantity='$quantity', TotalPrice='$totalPrice' WHERE ID='$id'";
        
        // Execute the SQL statement
        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
    
    // Fetch sales record for the given ID
    $sql = "SELECT * FROM sales WHERE ID='$id'";
    $result = $conn->query($sql);
    
    // Check if the record exists
    if ($result->num_rows > 0) {
        // Retrieve data from the record
        $row = $result->fetch_assoc();
        $date = $row["SaleDate"];
        $productID = $row["ProductID"];
        $unitPrice = $row["UnitPrice"];
        $quantity = $row["Quantity"];
        $totalPrice = $row["TotalPrice"];
    } else {
        echo "Record not found";
        exit;
    }
    
    // Fetch products for dropdown list
    $productOptions = "";
    $sql = "SELECT ID, Name FROM product";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Populate dropdown options
            $selected = ($row['ID'] == $productID) ? "selected" : "";
            $productOptions .= "<option value='{$row['ID']}' $selected>{$row['Name']}</option>";
        }
    } else {
        echo "No products found";
    }
    
    // Fetch sizes for dropdown list
    $sizeOptions = "";
    $sql = "SELECT DISTINCT Size FROM product";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Populate dropdown options
            $sizeOptions .= "<option value='{$row['Size']}'>{$row['Size']}</option>";
        }
    } else {
        echo "No sizes found";
    }
    
    // Close the database connection
    $conn->close();
?>
    
<div class="container">
    <div class="col1">
      
        
        <div class="cardadd">
        <h2>Update Sale</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>">
                <div class="flexcontainer"> 
                    <div class="flex-item">  
                        <label for="date">Date:</label>
                        <input type="date"class="ht" id="date" name="date" value="<?php echo $date; ?>"><br>
                    </div>
                    <div class="flex-item">  
                        <label for="productID">Product:</label>
                        <select class="ht" id="productID" name="productID">
                            <?php echo $productOptions; ?>
                        </select><br>
                    </div>
                    <div class="flex-item"> 
                        <label for="size">Size:</label>
                        <select class="ht" id="size" name="size">
                            <?php echo $sizeOptions; ?>
                        </select><br>
                    </div>
                    <div class="flex-item">  
                        <label for="unitPrice">Unit Price:</label>
                        <input type="text" id="unitPrice" name="unitPrice" value="<?php echo $unitPrice; ?>"><br>
                    </div>
                    <div class="flex-item"> 
                        <label for="quantity">Quantity:</label>
                        <input type="text" id="quantity" name="quantity" value="<?php echo $quantity; ?>"><br>
                    </div>
                    <div class="flex-item"> 
                        <label for="totalPrice">Total Price:</label>
                        <input type="text" id="totalPrice" name="totalPrice" value="<?php echo $totalPrice; ?>"><br>
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
