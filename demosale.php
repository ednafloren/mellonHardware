<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}

// Database connection and form submission handling
include 'config.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $productName = $_POST["productNameInput"];
    $selectedSize = $_POST["size"];
    $quantitySold = $_POST["quantity_sold"];
   
    $unitPrice = $_POST['unit_price'];
    $totalPrice = $_POST['total_price'];

    
// Retrieve the current quantity of the selected product and size
$sql = "SELECT quantity FROM productvariations WHERE productId IN (SELECT ID FROM product WHERE Name = ?) AND size = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $productName, $selectedSize);
$stmt->execute();
$result = $stmt->get_result();

// Check if $result is not null and if there are rows returned
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentQuantity = $row["quantity"];
    
   

    
    // Calculate the updated quantity after sale
    $updatedQuantity = $currentQuantity - $quantitySold;
    
    // Update the quantity of the product in the database
    $sql = "UPDATE productvariations SET quantity = ? WHERE productId IN (SELECT ID FROM product WHERE Name = ?) AND size = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $updatedQuantity, $productName, $selectedSize);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo "Quantity updated successfully";
        
        $sql = "INSERT INTO sales (productId, size, QuantitySold, unitprice, TotalPrice) VALUES ('$productId', '$selectedSize', '$quantitySold', '$unitPrice', '$totalPrice')";
        if ($conn->query($sql) === TRUE) {
            echo "Sale recorded successfully";
        } else {
            echo "Error recording sale: " . $conn->error;
        }
    } else {
        echo "Product variations not found";
    }
    

}}

// Fetch product names for suggestions
$productNames = array();
$sql = "SELECT DISTINCT Name FROM product";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $productNames[] = strtolower($row["Name"]);
    }
}
// Fetch all sizes and unit prices for each product name and store them in an associative array
$productSizesAndPrices = array();
foreach ($productNames as $productName) {
    $sizesAndPrices = array();
    $sql = "SELECT size, unitsellingprice FROM productvariations WHERE productId IN (SELECT ID FROM product WHERE Name = ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Prepare failed: " . $conn->error;
        exit; // Terminate script execution
    }
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result === false) {
        echo "Execute failed: " . $stmt->error;
        exit; // Terminate script execution
    }
    while ($row = $result->fetch_assoc()) {
        $sizesAndPrices[$row["size"]] = $row["unitsellingprice"];
    }
    $productSizesAndPrices[$productName] = $sizesAndPrices;
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sale</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="update.css">   
</head>
<body>
    
<?php include "sidebar.php";?>

<div class="container">
    <div class="col1">
        <h5>Add Product</h5>
        <hr>
        <div class="card1">
            <h1>Add Sale</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="flex-item">
                    <label for="productName">Product Name:</label>
                    <input type="text" id="productNameInput" name="productNameInput" list="productNameList" required placeholder="Enter or select product name">
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

                <select id="size" name="size" required onchange="populateUnitPrice()">
                    <option value="">Select Size</option>
                    <!-- Size options will be populated dynamically -->
                </select><br><br>
                
                <label for="quantity_sold">Quantity Sold:</label>
                <input type="number" id="quantity_sold" name="quantity_sold" min="1" required><br><br>
                
                <label for="unit_price">Unit Price:</label>
                <input type="text" id="unit_price" name="unit_price" readonly><br><br>
                
                <label for="total_price">Total Price:</label>
                <input type="text" id="total_price" name="total_price" readonly><br><br>
                
                <input type="submit" name="submit" value="Add Sale">
            </form>
        </div>
    </div>
</div>
   
<script>
// Function to populate size dropdown based on selected product name
function populateSizes(productName) {
    var sizeDropdown = document.getElementById("size");
    sizeDropdown.innerHTML = "<option value=''>Select Size</option>";
    var sizesAndPrices = <?php echo json_encode($productSizesAndPrices); ?>;
    var sizes = sizesAndPrices[productName];
    for (var size in sizes) {
        sizeDropdown.innerHTML += "<option value='" + size + "'>" + size + "</option>";
    }
}

// Function to populate unit price based on selected size
function populateUnitPrice() {
    var sizeDropdown = document.getElementById("size");
    var selectedSize = sizeDropdown.value;
    var unitPriceInput = document.getElementById("unit_price");
    var productName = document.getElementById("productNameInput").value;
    var sizesAndPrices = <?php echo json_encode($productSizesAndPrices); ?>;
    var unitPrice = sizesAndPrices[productName][selectedSize];
    unitPriceInput.value = unitPrice || "";
    calculateTotalPrice();
}

function calculateTotalPrice() {
    var quantitySold = document.getElementById("quantity_sold").value;
    var unitPrice = document.getElementById("unit_price").value;
    var totalPriceInput = document.getElementById("total_price");
    if (quantitySold !== "" && unitPrice !== "") {
        var totalPrice = parseFloat(quantitySold) * parseFloat(unitPrice);
        totalPriceInput.value = totalPrice.toFixed(2);
    } else {
        totalPriceInput.value = ""; // Clear total price if quantity or unit price is empty
    }
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
    populateSizesAndUnitPrice(input);
});

// Function to populate sizes dropdown and unit price based on selected product name
function populateSizesAndUnitPrice(productName) {
    populateSizes(productName); // Populate sizes first
    populateUnitPrice(); // Then populate unit price
}

// Event listener for input on quantity field
document.getElementById("quantity_sold").addEventListener("input", function() {
    calculateTotalPrice(); // Recalculate total price when quantity changes
});
</script>
    
<?php include "footer.php";?> <!-- Include your footer -->
<?php include "navbar.php";?> <!-- Include your navbar -->
</body>
</html>
