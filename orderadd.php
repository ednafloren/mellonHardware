<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}

include 'config.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $customerName = $_POST["customer_name"];
    $customerPhone = $_POST["customer_phone"];
    $orderStatus = $_POST["order_status"];
    $productNames = $_POST["product_names"];
    $productQuantities = $_POST["product_quantities"];
    $productUnitPrices = $_POST["product_unit_prices"];

    // Check if the customer already exists
    $sql = "SELECT id, phone FROM customer WHERE Name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $customerName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Customer exists
        $row = $result->fetch_assoc();
        $customerId = $row['id'];
    } else {
        // Insert new customer
        $insertCustomerSql = "INSERT INTO customer (Name, phone) VALUES (?, ?)";
        $stmt = $conn->prepare($insertCustomerSql);
        $stmt->bind_param("ss", $customerName, $customerPhone);
        $stmt->execute();
        $customerId = $conn->insert_id;
    }

    // Insert the order into the database
    $insertOrderSql = "INSERT INTO orders (customer_id, status_id) VALUES (?, (SELECT status_id FROM order_statuses WHERE status_name = ?))";
    $stmt = $conn->prepare($insertOrderSql);
    $totalPrice = 0; // Initialize total price
    $stmt->bind_param("is", $customerId, $orderStatus);
    $stmt->execute();
       // Get the order ID
       $orderId = $conn->insert_id;

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
       }

    // Get the order ID
    $orderId = $conn->insert_id;

    // Insert each product into the order_pdt table and calculate the total price
    for ($i = 0; $i < count($productNames); $i++) {
        $productName = $productNames[$i];
        $quantity = $productQuantities[$i];

        // Fetch product ID and unit price from the products table
        $fetchProductDetailsSql = "SELECT id, UNITPRICE FROM pdt WHERE NAME = ?";
        $stmt = $conn->prepare($fetchProductDetailsSql);
        $stmt->bind_param("s", $productName);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $productId = $product['id'];
        $unitPrice = $product['UNITPRICE'];

        // Calculate total price for the order
        $totalPrice += $unitPrice * $quantity;

        // Insert product into the order_pdt table
        $insertOrderProductSql = "INSERT INTO order_pdt (order_id, pdt_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertOrderProductSql);
        $stmt->bind_param("iii", $orderId, $productId, $quantity);
        $stmt->execute();
    }

    // Update the total price for the order
    $updateOrderTotalPriceSql = "UPDATE orders SET price = ? WHERE order_id = ?";
    $stmt = $conn->prepare($updateOrderTotalPriceSql);
    $stmt->bind_param("di", $totalPrice, $orderId);
    $stmt->execute();

    // Redirect to the orders page
    header("Location: order.php");
    exit;
}

// Fetch product names for suggestions
$productNames = [];
$sql = "SELECT NAME FROM pdt";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
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

// Fetch statuses from the statuses table
$statuses = [];
$sql = "SELECT status_name FROM order_statuses";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $statuses[] = $row["status_name"];
    }
}

// Fetch customer names and phone numbers for suggestions
$customerData = [];
$sql = "SELECT Name, phone FROM customer";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customerData[] = ["name" => $row["Name"], "phone" => $row["phone"]];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Order</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="update.css">
    <script>
        // Preloaded product names fetched dynamically from the database
        var productNames = <?php echo json_encode($productNames); ?>;
        var productUnitPrices = <?php echo json_encode($productUnitPrices); ?>;

        // Customer data for autofill functionality
        var customerData = <?php echo json_encode($customerData); ?>;

        // Function to dynamically add product input fields
        function addProduct() {
            var container = document.getElementById("productContainer");
            var productDiv = document.createElement("div");
            var productIndex = container.children.length + 1;
            productDiv.innerHTML = `
             <div class="flexcontainer">
              <div class="flex-item">
                <label for="product_${productIndex}_name">Product ${productIndex}:</label>
                <input list="productNames" id="product_${productIndex}_name" name="product_names[]" oninput="autofillProductDetails(this, this.value)" required>
                   </div>
                <div class="flex-item">
                <label for="product_${productIndex}_quantity">Quantity:</label>
                <input type="number" id="product_${productIndex}_quantity" name="product_quantities[]" oninput="updateProductTotalPrice(this)" required>
                   </div>
                <div class="flex-item">
                <label for="product_${productIndex}_unit_price">Unit Price:</label>
                <input type="number" id="product_${productIndex}_unit_price" name="product_unit_prices[]" required readonly>
                   </div>
                <div class="flex-item">
                <label for="product_${productIndex}_total_price">Total Price:</label>
                <input type="number" id="product_${productIndex}_total_price" name="product_total_prices[]" readonly>
                </div><br><br>
                </div>`;
            container.appendChild(productDiv);
        }

        // Function to autofill unit price based on product name
        function autofillProductDetails(inputElement, productName) {
            var productIndex = inputElement.id.split('_')[1];
            var productUnitPriceInput = document.getElementById("product_" + productIndex + "_unit_price");
            productUnitPriceInput.value = productUnitPrices[productName.toLowerCase()] || "";
            updateProductTotalPrice(inputElement);
        }

        // Function to update total price for each product
        function updateProductTotalPrice(inputElement) {
            var productIndex = inputElement.id.split('_')[1];
            var productQuantityInput = document.getElementById("product_" + productIndex + "_quantity");
            var productUnitPriceInput = document.getElementById("product_" + productIndex + "_unit_price");
            var productTotalPriceInput = document.getElementById("product_" + productIndex + "_total_price");

            var quantity = parseFloat(productQuantityInput.value) || 0;
            var unitPrice = parseFloat(productUnitPriceInput.value) || 0;
            var totalPrice = quantity * unitPrice;

            productTotalPriceInput.value = totalPrice.toFixed(2);
            updateTotalPrice();
        }

        // Function to update total price
        function updateTotalPrice() {
            var totalPrice = 0;
            var container = document.getElementById("productContainer");
            for (var i = 0; i < container.children.length; i++) {
                var productDiv = container.children[i];
                var productTotalPriceInput = productDiv.querySelector("[id^='product_'][id$='_total_price']");
                var totalPriceValue = parseFloat(productTotalPriceInput.value) || 0;
                totalPrice += totalPriceValue;
            }
            document.getElementById("total_price").value = totalPrice.toFixed(2);
        }

        // Initialize customer name input listener for suggestions
        window.onload = function() {
            var customerNameInput = document.getElementById("customer_name");
            customerNameInput.oninput = function() {
                showSuggestions(customerNameInput, 'customer');
            };
        };

        // Function to show suggestions as the user types (for customers)
        function showSuggestions(inputElement, type) {
            var suggestions = [];
            var suggestionsListId = 'suggestions_' + inputElement.id;
            var suggestionsList = document.getElementById(suggestionsListId);
            suggestionsList.innerHTML = '';

            if (type === 'customer') {
                suggestions = customerData.filter(function(customer) {
                    return customer.name.toLowerCase().includes(inputElement.value.toLowerCase());
                }).map(function(customer) {
                    return customer.name;
                });

                suggestions.forEach(function(suggestion) {
                    var suggestionItem = document.createElement('li');
                    suggestionItem.textContent = suggestion;
                    suggestionItem.onclick = function() {
                        inputElement.value = suggestion;
                        suggestionsList.innerHTML = '';
                        autofillCustomerDetails(inputElement.value);
                    };
                    suggestionsList.appendChild(suggestionItem);
                });
            }
        }

        // Function to autofill phone number based on customer name
        function autofillCustomerDetails(customerName) {
            var customer = customerData.find(c => c.name.toLowerCase() === customerName.toLowerCase());
            var customerPhoneInput = document.getElementById("customer_phone");

            if (customer) {
                customerPhoneInput.value = customer.phone;
            } else {
                customerPhoneInput.value = "";
            }
        }
    </script>
    <style>
        .suggestions-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
            border: 1px solid #ccc;
            max-height: 100px;
            overflow-y: auto;
            background-color: #fff;
            position: absolute;
            z-index: 1000;
        }
        .suggestions-list li {
            padding: 5px;
            cursor: pointer;
        }
        .suggestions-list li:hover {
            background-color: #eee;
        }
        .productContainer{
            background-color: #eee;
        }
    </style>
</head>
<body>
    <?php include "sidebar.php"; ?>
    <div class="container">
        <div class="col1">
         
            <hr>
            <div class="cardadd">
            <h2>Add Order</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="flexcontainer">
                        <div class="flex-item">
                            <label for="customer_name">Customer Name:</label>
                            <input type="text" id="customer_name" name="customer_name" oninput="showSuggestions(this, 'customer')" required><br><br>
                            <ul id="suggestions_customer_name" class="suggestions-list"></ul>
    </div>
                            <div class="flex-item">
                            <label for="customer_phone">Customer Phone:</label>
                            <input type="text" id="customer_phone" name="customer_phone" required><br><br>
    </div>
                            <div class="flex-item">
                            <label for="order_status">Order Status:</label>
                            <select id="order_status" name="order_status">
                                <?php foreach ($statuses as $status) {
                                    $selected = ($status == "pending") ? "selected" : "";
                                    echo "<option value=\"$status\" $selected>$status</option>";
                                } ?>
                            </select><br><br>
                            </div>
                            <div id="productContainer" class="productContainer">
                                <!-- Product input fields will be added dynamically here -->
                            </div>
                            <div style="margin-top:20px;align-items:right">
                            <button type="button" class="add" onclick="addProduct()">Add Product</button><br><br>
                            </div>
                            <div class="flex-item">
                            <label for="total_price">Total Price:</label>
                            <input type="number" id="total_price" name="total_price" readonly><br><br>

                            </div>
                            </div>
        <div class="button-container">
        <input type="submit" value="Add Order">
    </div>
                        </div>
                   
                </form>
            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>
    <?php include "navbar.php"; ?>

    <!-- Datalist for product names -->
    <datalist id="productNames">
        <?php foreach ($productNames as $productName): ?>
            <option value="<?= htmlspecialchars($productName) ?>"></option>
        <?php endforeach; ?>
    </datalist>
</body>
</html>
