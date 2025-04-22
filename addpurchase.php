<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data (Sanitize input if needed)
    $purchaseDate = $_POST['purchasedate'];
    $productName = $_POST['productName'];
    $paymentDate = $_POST['payment_date'];
    $paymentStatus = $_POST['payment_status'];
    $deliveryDate = $_POST['delivery_date'];
    $deliveryStatus = $_POST['delivery_status'];
    $unitCost = $_POST['unitCost'];
    $quantity = $_POST['quantity'];
    $supplier = $_POST['supplier'];

    // Check if the product name already exists
    $product_query = "SELECT id, quantity FROM pdt WHERE Name = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result_product = $stmt->get_result();

    if ($result_product->num_rows > 0) {
        // Product exists, retrieve its ID and current quantity
        $row = $result_product->fetch_assoc();
        $productId = $row['id'];
        $currentQuantity = $row['quantity'];
    } else {
        // Product doesn't exist, insert it into the products table
        $insertProductQuery = "INSERT INTO pdt (Name) VALUES (?)";
        $stmt = $conn->prepare($insertProductQuery);
        $stmt->bind_param("s", $productName);
        if ($stmt->execute()) {
            // Get the ID of the newly inserted product
            $productId = $conn->insert_id;
            $currentQuantity = 0;
            $_SESSION['newProduct'] = $productName;
        } else {
            echo "Error inserting product: " . $conn->error;
            exit;
        }
    }

    // Calculate total price
    $totalPrice = $unitCost * $quantity;

    // Update product quantity
    $newQuantity = $currentQuantity + $quantity;
    $updateProductQuery = "UPDATE pdt SET quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($updateProductQuery);
    $stmt->bind_param("ii", $newQuantity, $productId);
    if (!$stmt->execute()) {
        echo "Error updating product quantity: " . $conn->error;
        exit;
    }

    // Insert purchase details into the database
    $insertQuery = "INSERT INTO purchase (purchase_date, product_id, payment_date, payment_status, delivery_date, delivery_status, unit_price, quantity, supplier, total_price) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    if ($stmt->execute()) {
        // Set session variables to trigger JavaScript alert
        $_SESSION['purchaseSuccess'] = true;
        $_SESSION['newProduct'] = $productName; // Correctly set the new product name session variable
        $_SESSION['productId'] = $productId;
    
        // Redirect to purchases.php to show the alert
        header("Location: purchases.php");
        exit();
    }
    

    // Close prepared statements and database connection
    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Purchase</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="update.css">
    <style>
    /* CSS for custom alert */
    .custom-alert {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(0, 0, 0, 0.7);
        width: 100%;
        height: 100%;
        z-index: 1000;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .alert-box {
        background-color: #fff;
        width: 300px;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    .button-container {
        margin-top: 15px;
    }

    .button-container button {
        margin: 0 10px;
        padding: 8px 15px;
        border: none;
        cursor: pointer;
        border-radius: 3px;
    }

    .button-container button:hover {
        background-color: #f0f0f0;
    }
    </style>
</head>
<body>
<?php include "sidebar.php"; // Include your sidebar ?>
<div class="container">
    <div class="col1">
      
        <div class="cardadd">
        <h2>Add Purchase</h2>
       
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="flexcontainer"> 
                    <div class="flex-item">
                        <label for="purchasedate">Purchase Date:</label>
                        <input type="date" id="purchasedate" name="purchasedate" class="ht">
                    </div> 

                    <div class="flex-item">
                        <label for="productName">Product Name:</label>
                        <input type="text" id="productName" name="productName" class="ht" list="productList">
                        <datalist id="productList">
                            <?php
                            // Fetch existing product names from the database
                            $product_query = "SELECT DISTINCT Name FROM pdt";
                            $result_product = $conn->query($product_query);
                            while ($row_product = $result_product->fetch_assoc()) {
                                echo '<option value="' . $row_product['Name'] . '">' . $row_product['Name'] . '</option>';
                            }
                            ?>
                        </datalist>
                    </div>

                    <!-- Other input fields -->
                    <div class="flex-item">
                        <label for="payment_date">Payment Date:</label>
                        <input type="date" id="payment_date" name="payment_date" class="ht">
                    </div>
                    <div class="flex-item">
                        <label for="payment_status">Payment Status:</label>
                        <input type="text" id="payment_status" name="payment_status">
                    </div> 
                    <div class="flex-item">
                        <label for="delivery_date">Delivery Date:</label>
                        <input type="date" id="delivery_date" name="delivery_date" class="ht">
                    </div> 
                    <div class="flex-item">
                        <label for="delivery_status">Delivery Status:</label>
                        <input type="text" id="delivery_status" name="delivery_status">
                    </div> 
                    <div class="flex-item">
                        <label for="unitCost">Unit Cost:</label>
                        <input type="number" id="unitCost" name="unitCost" required>
                    </div> 
                    <div class="flex-item">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" required>
                    </div> 
            
                    <div class="flex-item">
                        <label for="supplier">Supplier:</label>
                        <input type="text" id="supplier" name="supplier">
                    </div>
                    <div class="flex-item">
                        <label for="totalPrice">Total Price:</label>
                        <input type="text" id="totalPrice" name="totalPrice" readonly>
                    </div>  
                 
                </div>
                <div class="button-container">
                    <input type="submit" value="Add Purchase">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; // Include your footer ?>
<?php include "navbar.php"; // Include your navbar ?>

<?php
// JavaScript to show alert immediately after purchase is added
if (isset($_SESSION['purchaseSuccess']) && $_SESSION['purchaseSuccess'] === true) {
    echo '<script>
            window.onload = function() {
                showCustomAlert("New product added: ' . $_SESSION['newProduct'] . '. Do you want to update the product?", function() {
                    window.location.href = "pdtupdate.php?id=' . $_SESSION['productId'] . '";
                }, function() {
                    // Optional: Handle cancel action if needed
                });
            };
          </script>';

    // Unset session variables after displaying alert
    unset($_SESSION['purchaseSuccess']);
    unset($_SESSION['newProduct']);
    unset($_SESSION['productId']);
}
?>

<script>
// Calculate total price when unit cost or quantity change
document.getElementById("unitCost").addEventListener("input", function() {
    calculateTotalPrice();
});

document.getElementById("quantity").addEventListener("input", function() {
    calculateTotalPrice();
});

// Function to calculate total price
function calculateTotalPrice() {
    var unitCost = document.getElementById("unitCost").value;
    var quantity = document.getElementById("quantity").value;
    var totalPrice = unitCost * quantity;
    document.getElementById("totalPrice").value = totalPrice;
}

// Custom alert function
function showCustomAlert(message, updateCallback, stayCallback) {
    var alertBox = document.createElement("div");
    alertBox.className = "custom-alert";
    var alertContent = '<div class="alert-box">';
    alertContent += '<p>' + message + '</p>';
    alertContent += '<div class="button-container">';
    alertContent += '<button onclick="updateCallback()">Update</button>';
    alertContent += '<button onclick="stayCallback()">Stay</button>';
    alertContent += '</div></div>';
    alertBox.innerHTML = alertContent;
    document.body.appendChild(alertBox);
}
</script>

</body>
</html>


