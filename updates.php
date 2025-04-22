<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}

// Database connection and form submission handling
include 'config.php';

    // Check if sale ID is provided
    if (!isset($_GET['id'])) {
        echo "Sale ID not provided";
        exit;
    }

    // Retrieve sale details from the database
    $saleId = $_GET['id'];
    $sql = "SELECT s.*, p.Name AS productName, p.UNITPRICE
            FROM sales s 
            INNER JOIN pdt p ON s.product_id = p.id 
            WHERE s.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $saleId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Sale not found";
        exit;
    }

    $sale = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $unitPrice = $_POST["unit_price"];
    $quantitySold = $_POST["quantity_sold"];
    $totalPrice = $_POST['total_price'];
    $productName = $_POST["productNameInput"];

    // Retrieve the current quantity of the selected product and size
    $sql = "SELECT p.id, p.quantity FROM pdt p WHERE p.Name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if $result is not null and if there are rows returned
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $productId = $row["id"];
        $currentQuantity = $row["quantity"]; 

        // Calculate the updated quantity after sale
        $updatedQuantity = $currentQuantity - $quantitySold;

        /// Update the quantity of the product in the database
$sql_update_quantity = "UPDATE pdt SET quantity = ? WHERE id = ?";
$stmt_update_quantity = $conn->prepare($sql_update_quantity);

if (!$stmt_update_quantity) {
    echo "Error preparing statement: " . $conn->error;
    exit; // Terminate script execution
}

$stmt_update_quantity->bind_param("ii", $updatedQuantity, $productId);
$stmt_update_quantity->execute();

if ($stmt_update_quantity->affected_rows > 0) {
    // Update the sale in the sales table
    $sql_update_sale = "UPDATE sales s
                        SET  s.QuantitySold = ?, s.TotalPrice = ?, s.product_id = ?
                        WHERE s.id = ?";
    $stmt_update_sale = $conn->prepare($sql_update_sale);
    if (!$stmt_update_sale) {
        echo "Error preparing statement: " . $conn->error;
        exit; // Terminate script execution
    }
    $stmt_update_sale->bind_param("diii",   $quantitySold, $totalPrice, $productId, $saleId);
    $stmt_update_sale->execute();

    if ($stmt_update_sale->affected_rows > 0) {
        echo "Sale updated successfully";
    } else {
        echo "Error updating sale: " . $stmt_update_sale->error;
    }
    $stmt_update_sale->close();
} else {
    echo "Error updating quantity: " . $stmt_update_quantity->error;
}
$stmt_update_quantity->close();

}
}

// Fetch product names for suggestions
$productNames = array();
$sql = "SELECT DISTINCT Name FROM pdt";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productNames[] = strtolower($row["Name"]);
    }
}
// Fetch unit prices for each product name and store them in an associative array
$productUnitPrices = array();
foreach ($productNames as $productName) {
    $sql = "SELECT UNITPRICE FROM pdt WHERE NAME = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $productUnitPrices[$productName] = $row["UNITPRICE"];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Sale</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="update.css">
</head>
<body>
<?php include "sidebar.php";?>
<div class="container">
    <div class="col1">

    
        <div class="cardadd">
            <h2>Update Sale</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?id=' . $saleId); ?>">
                <div class="flexcontainer">
                    <div class="flex-item">
                        <label for="productName">Product Name:</label>
                        <input type="text" id="productNameInput" name="productNameInput" list="productNameList" value="<?php echo $sale['productName']; ?>" required placeholder="Enter or select product name">
                        <datalist id="productNameList">
                            <option value="">Select Product Name</option>
                            <?php
                            // Display product names as options
                            foreach ($productNames as $name) {
                                echo "<option value='" . strtolower($name) . "'>" . strtolower($name)  . "</option>";
                            }
                            ?>
                        </datalist><br>
                    </div>
              
                    <div class="flex-item">
                        <label for="unit_price">Unit Price:</label>
                        <input type="number" id="unit_price" name="unit_price" value="<?php echo $sale['UNITPRICE']; ?>" required><br><br>
                    </div>
                    <div class="flex-item">
                        <label for="quantity_sold">Quantity Sold:</label>
                        <input type="number" id="quantity_sold" name="quantity_sold" value="<?php echo $sale['QuantitySold']; ?>" required><br><br>
                    </div>
                    <div class="flex-item">
                        <label for="total_price">Total Price:</label>
                        <input type="number" id="total_price" name="total_price" value="<?php echo $sale['TotalPrice']; ?>" required><br><br>
                    </div>
                </div>
                <div class="button-container">
                    <input type="submit" value="Update Sale">
                </div>
            </form>
        </div>
    </div>
</div>
<?php include "footer.php";?>
<?php include "navbar.php";?>
<script>
// Function to calculate total price
function calculateTotalPrice() {
    var quantitySold = document.getElementById("quantity_sold").value;
    var unitPrice = document.getElementById("unit_price").value;
    var totalPriceInput = document.getElementById("total_price");
    if (quantitySold !== "" && unitPrice !== "") {
        var totalPrice = parseFloat(quantitySold) * parseFloat(unitPrice);
        totalPriceInput.value = totalPrice.toFixed(2);
    } else {
        totalPriceInput.value = "";
    }
}

// Function to populate unit price based on selected product name
function populateUnitPrice() {
    var productName = document.getElementById("productNameInput").value.toLowerCase();
    var unitPriceInput = document.getElementById("unit_price");
    if (productName in <?php echo json_encode($productUnitPrices); ?>) {
        unitPriceInput.value = <?php echo json_encode($productUnitPrices); ?>[productName];
    } else {
        unitPriceInput.value = "";
    }
    calculateTotalPrice(); // Recalculate total price
}

// Event listener for input on product name field
document.getElementById("productNameInput").addEventListener("input", function() {
    var input = this.value.toLowerCase(); // Get input value in lowercase
    var options = document.getElementById("productNameList").getElementsByTagName("option");
    for (var i = 0; i < options.length; i++) {
        var option = options[i];
        if (option.value.toLowerCase().startsWith(input)) { // Check if option starts with input
            option.style.display = ""; // Show option
        } else {
            option.style.display = "none"; // Hide option
        }
    }
    populateUnitPrice(); // Populate unit price
});

// Event listener for input on quantity field
document.getElementById("quantity_sold").addEventListener("input", function() {
    calculateTotalPrice(); // Recalculate total price when quantity changes
});
</script>
</body>
</html>
