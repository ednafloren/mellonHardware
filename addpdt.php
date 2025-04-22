<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location: login.php');
    exit;
}
// Connect to the database
include 'config.php'; // Assuming you have a file for database connection

// Fetch data from PDT table
$sql = "SELECT p.ID, p.NAME, p.UNITPRICE, p.COSTPRICE, p.QUANTITY, c.catName AS CATEGORY_NAME, p.CREATED_AT, p.UPDATED_AT, p.TOTAL_COST_PRICE, p.PROFIT
        FROM pdt p
        INNER JOIN category c ON p.CATEGORY_ID = c.ID";
$result = $conn->query($sql);

// Initialize variables to hold form data
$name = $category = $unitprice = $costprice = $quantity = "";
$name_err = $category_err = $unitprice_err = $costprice_err = $quantity_err = "";

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter a name for the product.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate category
    if (empty(trim($_POST["category"]))) {
        $category_err = "Please select a category.";
    } else {
        $category = trim($_POST["category"]);
    }

    // Validate unit price
    if (empty(trim($_POST["unitprice"]))) {
        $unitprice_err = "Please enter the unit price.";
    } else {
        $unitprice = trim($_POST["unitprice"]);
    }

    // Validate cost price
    if (empty(trim($_POST["costprice"]))) {
        $costprice_err = "Please enter the cost price.";
    } else {
        $costprice = trim($_POST["costprice"]);
    }

    // Validate quantity
    if (empty(trim($_POST["quantity"]))) {
        $quantity_err = "Please enter the quantity.";
    } else {
        $quantity = trim($_POST["quantity"]);
    }

    // Check input errors before inserting into database
    if (empty($name_err) && empty($category_err) && empty($unitprice_err) && empty($costprice_err) && empty($quantity_err)) {
        // Calculate total cost price
        $total_cost_price = $costprice * $quantity;
        
        // Calculate profit
        $profit = ($unitprice - $costprice) * $quantity;

        // Prepare an INSERT statement
        $sql = "INSERT INTO pdt (NAME, CATEGORY_ID, UNITPRICE, COSTPRICE, QUANTITY, TOTAL_COST_PRICE, PROFIT) VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("siiddii", $param_name, $param_category, $param_unitprice, $param_costprice, $param_quantity, $param_total_cost_price, $param_profit);

            // Set parameters
            $param_name = $name;
            $param_category = $category;
            $param_unitprice = $unitprice;
            $param_costprice = $costprice;
            $param_quantity = $quantity;
            $param_total_cost_price = $total_cost_price;
            $param_profit = $profit;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to product information page
                header("location: pdt.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="update.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
</head>
<body>
<?php include "sidebar.php"?>
<div class="container">
    <div class="col1">

    <div class="cardadd">
    <h2>Add Product</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="flexcontainer"> 
    <div class="flex-item"> 
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $name; ?>">
            <span class="error"><?php echo $name_err; ?></span>
        </div>
        <div class="flex-item"> 
            <label>Category:</label>
            <select name="category">
                <option value="">Select a category</option>
                <?php
                $sql = "SELECT ID, catName FROM category";
                $categories = $conn->query($sql);
                while ($row = $categories->fetch_assoc()) {
                    echo "<option value='" . $row['ID'] . "'>" . $row['catName'] . "</option>";
                }
                ?>
            </select>
            <span class="error"><?php echo $category_err; ?></span>
        </div>
        <div class="flex-item"> 
            <label>Unit Price:</label>
            <input type="text" name="unitprice" value="<?php echo $unitprice; ?>">
            <span class="error"><?php echo $unitprice_err; ?></span>
        </div>
        <div class="flex-item"> 
            <label>Cost Price:</label>
            <input type="text" name="costprice" value="<?php echo $costprice; ?>">
            <span class="error"><?php echo $costprice_err; ?></span>
        </div>
        <div class="flex-item"> 
            <label>Quantity:</label>
            <input type="text" name="quantity" value="<?php echo $quantity; ?>">
            <span class="error"><?php echo $quantity_err; ?></span>
        </div>
            </div>
        <div class="button-container">
        <input type="submit" value="Add">
    </div>
          
            
    </form>
</div>
            </div>
            </div>
            </div>
<?php include "footer.php"?> <!-- Include your footer -->
<?php include "navbar.php"?> 
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
