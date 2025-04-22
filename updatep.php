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
    <title>Update Product and Variations</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="update.css">
</head>
<body>
<?php include "sidebar.php";

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flexflow";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the ID parameter is provided in the URL
if (!isset($_GET['id'])) {
    echo "ID parameter is missing";
    exit;
}

// Retrieve the ID parameter from the URL
$id = $_GET['id'];
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['productName'];
    $category = $_POST['category'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'];
    $unitCostPrice = $_POST['unitCostPrice'];
    $unitSellingPrice = $_POST['unitSellingPrice'];

    // Update product variations for the specific variation ID and product ID
    $sql = "UPDATE productvariations v
            INNER JOIN product p ON v.productId = p.ID
            SET v.size = '$size', v.quantity = '$quantity', v.unitcostprice = '$unitCostPrice', v.unitsellingprice = '$unitSellingPrice'
            WHERE v.id = $id AND p.ID = (SELECT productId FROM productvariations WHERE id = $id)";

    if ($conn->query($sql) === TRUE) {
        echo "Product variation updated successfully";

        // Update the product's UpdatedAt field only if the variation was updated
        $sqlUpdateProduct = "UPDATE product SET Name='$name', CategoryID='$category', UpdatedAt = CURRENT_TIMESTAMP WHERE id = (SELECT productId FROM productvariations WHERE id = $id)";
        if ($conn->query($sqlUpdateProduct) !== TRUE) {
            echo "Error updating product's UpdatedAt field: " . $conn->error;
        }
    } else {
        echo "Error updating product variation: " . $conn->error;
    }
}


// Retrieve initial data for product and variations from the database
$sql = "SELECT p.Name AS product_name, p.CategoryID, v.size, v.quantity, v.unitcostprice, v.unitsellingprice
        FROM product p
        INNER JOIN productvariations v ON p.id = v.productId
        WHERE v.id = $id";

$result = $conn->query($sql);

// Check if the record exists
if ($result->num_rows > 0) {
    // Fetch the product and variation data
    $row = $result->fetch_assoc();
    $productName = $row["product_name"];
    $categoryID = $row["CategoryID"];
    $size = $row["size"];
    $quantity = $row["quantity"];
    $unitCostPrice = $row["unitcostprice"];
    $unitSellingPrice = $row["unitsellingprice"];
} else {
    echo "Product variation not found";
    exit;
}

// Close the database connection
// $conn->close();
?>

<div class="container">
    <div class="col1">
        <h5>Update Purchase</h5>
        <hr>
        <div class="card1">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>">
            <div class="flexcontainer"> 
                 
                 <div class="flex-item">      
            <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="productName" value="<?php echo $productName; ?>"><br>
                 </div>

                 <div class="flex-item">  
                <!-- Add a dropdown to select category -->
                <label for="category">Category:</label>
                <select class="ht" id="category" name="category" required >
                    <option value=""></option>
                    <?php
                    // Retrieve categories from the database
                    $sql = "SELECT id, catName FROM category";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $selected = ($row["id"] == $categoryID) ? "selected" : "";
                            echo "<option value='" . $row["id"] . "' $selected>" . $row["catName"] . "</option>";
                        }
                    }
                    ?>
                </select><br>
                </div>
                      
                      <div class="flex-item"> 
                <label for="size">Size:</label>
                <input type="text" id="size" name="size" value="<?php echo $size; ?>"><br>
                </div>
                      
                      <div class="flex-item"> 
                <label for="quantity">Quantity:</label>
                <input type="text" id="quantity" name="quantity" value="<?php echo $quantity; ?>"><br>
                </div>
                      
                      <div class="flex-item"> 
                <label for="unitCostPrice">Unit Cost Price:</label>
                <input type="text" id="unitCostPrice" name="unitCostPrice" value="<?php echo $unitCostPrice; ?>"><br>
                </div>
                      
                      <div class="flex-item"> 
                <label for="unitSellingPrice">Unit Selling Price:</label>
                <input type="text" id="unitSellingPrice" name="unitSellingPrice" value="<?php echo $unitSellingPrice; ?>"><br>
                </div>
                </div>   
                      <div class="button-container"> 
                <input type="submit" value="Update">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
<?php include "navbar.php"; ?>
</body>
</html>
