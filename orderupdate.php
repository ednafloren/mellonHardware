<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('location: login.php');
    exit;
}

// Include database configuration
include 'config.php';

// Initialize variables
$orderId = null;
$order = [];
$products = [];
$statuses = [];
$productNameSuggestions = []; // Array to store product name suggestions

// Fetch order details if order_id is provided in the URL
if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Fetch order details
    $sql = "SELECT * FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();

        // Fetch products for the order
        $sql = "SELECT op.pdt_id AS product_id, pdt.name AS product_name, op.quantity, pdt.unitprice, op.quantity * pdt.unitprice AS total_price 
                FROM order_pdt op
                INNER JOIN pdt ON op.pdt_id = pdt.id
                WHERE op.order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $productsResult = $stmt->get_result();
        while ($row = $productsResult->fetch_assoc()) {
            $products[] = $row;
        }
    } else {
        // Redirect to orders page if no order found with the provided ID
        header("Location: order.php");
        exit;
    }
} else {
    // Redirect to orders page if no order ID is provided
    header("Location: order.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    // Retrieve and sanitize form inputs
    $orderId = $_POST["order_id"];
    $orderStatus = $_POST["order_status"];
    $productQuantities = $_POST["product_quantities"];
    $productIds = $_POST["product_ids"];
    $newProductNames = $_POST["new_product_names"];
    $newProductQuantities = $_POST["new_product_quantities"];
    $newProductPrices = $_POST["new_product_prices"];
    $deletedProductIds = isset($_POST["deleted_product_ids"]) ? json_decode($_POST["deleted_product_ids"], true) : [];

    // Update order status in the database
    $updateOrderSql = "UPDATE orders SET status_id = (SELECT status_id FROM order_statuses WHERE status_name = ?) WHERE order_id = ?";
    $stmtUpdateOrder = $conn->prepare($updateOrderSql);
    $stmtUpdateOrder->bind_param("si", $orderStatus, $orderId);
    if ($stmtUpdateOrder->execute()) {
        $stmtUpdateOrder->close();

        // Update existing product quantities
        $updateProductSql = "UPDATE order_pdt SET quantity = ? WHERE order_id = ? AND pdt_id = ?";
        $stmtUpdateProduct = $conn->prepare($updateProductSql);
        foreach ($productIds as $index => $productId) {
            $quantity = $productQuantities[$index];
            $stmtUpdateProduct->bind_param("iii", $quantity, $orderId, $productId);
            $stmtUpdateProduct->execute();
        }
        $stmtUpdateProduct->close();

        // Insert new products
        $insertProductSql = "INSERT INTO order_pdt (order_id, pdt_id, quantity) VALUES (?, ?, ?)";
        $stmtInsertProduct = $conn->prepare($insertProductSql);
        for ($i = 0; $i < count($newProductNames); $i++) {
            // Assuming new product names are entered and you retrieve product ID from another table
            // Here, you would normally query your product table for an exact match or a LIKE query.
            // For simplicity, let's assume direct insertion with user input.
            $productName = $newProductNames[$i];
            $productQuantity = $newProductQuantities[$i];
            $productPrice = $newProductPrices[$i];

            // Fetch or insert the product into 'pdt' table if not existing
            $fetchProductIdSql = "SELECT id FROM pdt WHERE name = ?";
            $stmtFetchProductId = $conn->prepare($fetchProductIdSql);
            $stmtFetchProductId->bind_param("s", $productName);
            $stmtFetchProductId->execute();
            $productIdResult = $stmtFetchProductId->get_result();

            if ($productIdResult->num_rows > 0) {
                $product = $productIdResult->fetch_assoc();
                $productId = $product['id'];
            } else {
                // Insert new product if not found
                $insertProductSql = "INSERT INTO pdt (name, unitprice) VALUES (?, ?)";
                $stmtInsertProduct = $conn->prepare($insertProductSql);
                $stmtInsertProduct->bind_param("sd", $productName, $productPrice);
                $stmtInsertProduct->execute();

                // Retrieve the inserted product ID
                $productId = $stmtInsertProduct->insert_id;
                $stmtInsertProduct->close();
            }
            $stmtFetchProductId->close();

            // Insert the new product into the order
            $stmtInsertProduct->bind_param("iii", $orderId, $productId, $productQuantity);
            $stmtInsertProduct->execute();
        }
        $stmtInsertProduct->close();
// Update timestamp based on status
        $updateTimestampSql = "";
        switch ($orderStatus) {
            case "delivered":
                $updateTimestampSql = "UPDATE orders SET delivered_at = NOW() WHERE order_id = ?";
                break;
            case "completed":
                $updateTimestampSql = "UPDATE orders SET completed_at = NOW() WHERE order_id = ?";
                break;
            case "cancelled":
                $updateTimestampSql = "UPDATE orders SET cancelled_at = NOW() WHERE order_id = ?";
                break;
            default:
                // No timestamp update needed for other statuses
                break;
        }

        // Execute the timestamp update query
        if (!empty($updateTimestampSql)) {
            $stmt = $conn->prepare($updateTimestampSql);
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $stmt->close();
        }


        // Delete products
        $deleteProductSql = "DELETE FROM order_pdt WHERE order_id = ? AND pdt_id = ?";
        $stmtDeleteProduct = $conn->prepare($deleteProductSql);
        foreach ($deletedProductIds as $productId) {
            $stmtDeleteProduct->bind_param("ii", $orderId, $productId);
            $stmtDeleteProduct->execute();
        }
        $stmtDeleteProduct->close();

        // Redirect back to the orders page after successful update
        header("Location: order.php");
        exit;
    } else {
        // Handle SQL execution error
        echo "Error updating order status: " . $stmtUpdateOrder->error;
    }
}

// Fetch unit prices for each product name and store them in an associative array
$productUnitPrices = array();
foreach ($productNameSuggestions as $productName) {
    $sql = "SELECT unitprice FROM pdt WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $productUnitPrices[$productName] = $row["unitprice"];
}

// Fetch order statuses from the database
$sqlStatuses = "SELECT status_id, status_name FROM order_statuses";
$resultStatuses = $conn->query($sqlStatuses);
if ($resultStatuses->num_rows > 0) {
    while ($row = $resultStatuses->fetch_assoc()) {
        $statuses[] = $row;
    }
}

// Fetch product name suggestions from the database
$sqlProductNameSuggestions = "SELECT DISTINCT name FROM pdt";
$resultProductNameSuggestions = $conn->query($sqlProductNameSuggestions);
if ($resultProductNameSuggestions->num_rows > 0) {
    while ($row = $resultProductNameSuggestions->fetch_assoc()) {
        $productNameSuggestions[] = $row['name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="update.css">

</head>
<body>
    <?php include "sidebar.php"; ?>
    <div class="container">
        <div class="col1">
     
      
            <div class="cardadd">
            <h2>Update Order</h2>
                <?php if (!empty($order)): ?>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?order_id=" . $orderId; ?>">
                        <!-- Add a hidden input field to store the order_id -->
                        <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
                        <div class="flex-item">
                        <label for="order_status">Order Status:</label>
                        <select id="order_status" name="order_status">
                            <?php foreach ($statuses as $status) {
                                $selected = ($status['status_id'] == $order['status_id']) ? "selected" : "";
                                echo "<option value=\"{$status['status_name']}\" $selected>{$status['status_name']}</option>";
                            } ?>
                        </select>
                        </div><br><br>

                        <!-- Display order products -->
                        <h3>Order Products</h3>
                        <table id="products_table">
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Action</th>
                            </tr>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo $product['product_name']; ?></td>
                                    <td><input type="number" name="product_quantities[]" value="<?php echo $product['quantity']; ?>" min="1"></td>
                                    <td><?php echo $product['unitprice']; ?></td>
                                    <td><?php echo $product['total_price']; ?></td>
                                    <td><button type="button" class="remove_product">Remove</button></td>
                                    <!-- Add a hidden input field to store product IDs -->
                                    <input type="hidden" name="product_ids[]" value="<?php echo $product['product_id']; ?>">
                                </tr>
                            <?php endforeach; ?>
                        </table><br>

                        <!-- Add New Products Section -->
                        <h3>New Products</h3>
                        <table id="new_products_table">
                            <!-- Table header -->
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Action</th>
                            </tr>
                            <!-- Table rows will be dynamically added here via JavaScript -->
                        </table><br>

                        <!-- Button to add a new row for a new product -->
                        <button type="button" class="add" id="add_product">Add Product</button><br><br>
                        <div class="button-container">
  <input type="submit" value="Update Order">
                            </div>
                      
                    </form>
                <?php else: ?>
                    <p>No order details found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
                </div>

    <script>
    // Function to handle product removal
    function handleProductRemoval(event) {
        if (event.target.classList.contains('remove_product')) {
            var row = event.target.closest('tr');
            row.parentNode.removeChild(row);
        }
    }

    // Event listener for dynamically added table
    document.addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('remove_product')) {
            handleProductRemoval(event);
        }
    });

    // Add product button click handler
    document.getElementById('add_product').addEventListener('click', function() {
        var table = document.getElementById('new_products_table');
        var row = table.insertRow(table.rows.length);
        row.innerHTML = `
            <tr>
                <td>
                    <input type="text" name="new_product_names[]" list="product_suggestions" autocomplete="off" oninput="fetchUnitPrice(this)">
                    <datalist id="product_suggestions">
                        <?php foreach ($productNameSuggestions as $name): ?>
                            <option value="<?php echo htmlspecialchars($name); ?>">
                        <?php endforeach; ?>
                    </datalist>
                </td>
                <td><input type="number" name="new_product_quantities[]" min="1" oninput="updateTotalPrice(this.parentNode.parentNode)"></td>
                <td><input type="number" name="new_product_prices[]" min="0.01" step="0.01" oninput="updateTotalPrice(this.parentNode.parentNode)"></td>
                <td class="total_price"></td>
                <td><button type="button" class="remove_product">Remove</button></td>
            </tr>
        `;
    });

    // Function to fetch unit price based on product name
    function fetchUnitPrice(inputElement) {
        var productName = inputElement.value.trim();
        var parentRow = inputElement.parentNode.parentNode;
        var unitPriceInput = parentRow.querySelector('input[name="new_product_prices[]"]');
        
        // Example code for fetching unit price from server
        // Replace with actual implementation using AJAX or server-side interaction
        // This example assumes PHP script interaction for simplicity
        // Here, we would fetch unit price via AJAX call to server
    }

    // Function to update total price based on quantity and unit price
    function updateTotalPrice(row) {
        var quantity = parseInt(row.querySelector('input[name="new_product_quantities[]"]').value) || 0;
        var unitPrice = parseFloat(row.querySelector('input[name="new_product_prices[]"]').value) || 0;
        var totalPriceCell = row.querySelector('.total_price');
        totalPriceCell.textContent = (quantity * unitPrice).toFixed(2);
    }
</script>

    <?php include "footer.php"; ?>
    <?php include "navbar.php"; ?>
</body>
</html>
