<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}

// Database connection and form submission handling
include 'config.php';

// Initialize variables
$receiptData = array();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $productName = $_POST["productNameInput"];
    $quantitySold = $_POST["quantity_sold"];
    $unitPrice = $_POST['unit_price'];
    $totalPrice = $_POST['total_price'];

    // Retrieve the current quantity of the selected product
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

        // Update the quantity of the product in the database
        $sql = "UPDATE pdt SET quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $updatedQuantity, $productId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Insert sale record
            $saleDate = date("Y/m/d h:i:sa");

            // Use the correct column names in the INSERT query
            $sql = "INSERT INTO sales (SaleDate, product_id, QuantitySold, TotalPrice) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sidd", $saleDate, $productId, $quantitySold, $totalPrice);
            if ($stmt->execute()) {
                // Sale recorded successfully, prepare receipt information
                $receiptData = array(
                    "Sale Date" => $saleDate,
                    "Product Name" => $productName,
                    "Quantity Sold" => $quantitySold,
                    "Unit Price" => $unitPrice,
                    "Total Price" => $totalPrice
                );
                // Store receipt data in session
                $_SESSION['receiptData'] = $receiptData;
                // Redirect to receipt page after successful sale
                header('Location: receipt.php');
                exit;
            } else {
                echo "Error recording sale: " . $stmt->error;
            }
        } else {
            echo "Failed to update quantity";
        }
    } else {
        echo "No rows returned";
    }
}

// Fetch product names for suggestions
$productNames = array();
$sql = "SELECT DISTINCT NAME FROM pdt";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $productNames[] = strtolower($row["NAME"]);
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
    <title>Add Sale</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="update.css">
    <link rel="stylesheet" href="product.css">

</head>
<body>
<?php include "sidebar.php";?>

<div class="container">
    <div class="col1">


        <div class="cardadd">
            <h2>Add Sale</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="disableSubmitButton()">
                <div class="flexcontainer">
                    <div class="flex-item">
                        <label for="productName">Product Name:</label>
                        <input type="text" id="productNameInput" name="productNameInput" list="productNameList" required placeholder="Enter or select product name" autocomplete="off">
                        <datalist id="productNameList">
                            <option value="">Select Product Name</option>
                            <?php
                            // Display product names as options
                            foreach ($productNames as $name) {
                                echo "<option value='" . htmlspecialchars($name) . "'>" . htmlspecialchars($name) . "</option>";
                            }
                            ?>
                        </datalist><br>
                    </div>
                    <div class="flex-item">
                        <label for="quantity_sold">Quantity Sold:</label>
                        <input type="number" id="quantity_sold" name="quantity_sold" min="1" required><br><br>
                    </div>
                    <div class="flex-item">
                        <label for="unit_price">Unit Price:</label>
                        <input type="text" id="unit_price" name="unit_price" readonly><br><br>
                    </div>
                    <div class="flex-item">
                        <label for="total_price">Total Price:</label>
                        <input type="text" id="total_price" name="total_price" readonly><br><br>
                    </div>
                </div>
                <div class="button-container">
                    <input type="submit" id="submit-button" name="submit" value="Add Sale">
                </div>
                       
            </form>
            </div>
        </div>
    </div>
</div>

<script>
function disableSubmitButton() {
    document.getElementById("submit-button").disabled = true;
}

// Function to populate unit price based on selected product name
function populateUnitPrice() {
    var unitPriceInput = document.getElementById("unit_price");
    var productName = document.getElementById("productNameInput").value.toLowerCase();
    var unitPrice = <?php echo json_encode($productUnitPrices); ?>[productName];
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
    populateUnitPrice();
});

// Event listener for input on quantity field
document.getElementById("quantity_sold").addEventListener("input", function() {
    calculateTotalPrice(); // Recalculate total price when quantity changes
});
</script>

<?php include "footer.php";?> <!-- Include your footer -->
<?php include "navbar.php";?> <!-- Include your navbar -->
</body>
</html>
