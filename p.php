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
    <title>Add Product</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="update.css">    
</head>
<body>
    <?php
// Establish database connection

include "config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Prepare SQL statement to retrieve product name from Product table
$product_variation_id = $_POST['product_variation_id'];
$product_name_query = "SELECT p.Name FROM Product p INNER JOIN productvariations pv ON p.ID = pv.productId WHERE pv.id = ?";
$stmt_product_name = $conn->prepare($product_name_query);
$stmt_product_name->bind_param("i", $product_variation_id);
$stmt_product_name->execute();
$result_product_name = $stmt_product_name->get_result();
$row_product_name = $result_product_name->fetch_assoc();
$product_name = $row_product_name['Name'];

// Prepare SQL statement to calculate total price
$price = $_POST['unitCost'];
$quantity = $_POST['quantity'];
$total_price = $price * $quantity;

// Prepare SQL statement to calculate total amount
$expenses = $_POST['expenses'];
$total_amount = $total_price + $expenses;

// Prepare SQL statement to insert data into the Purchase table
$stmt = $conn->prepare("INSERT INTO purchase ( product_variation_id, payment_status, payment_date, delivery_status, delivery_date,unit_price, total_price, quantity, expenses, total_amount, supplier, purchase_date) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if($stmt === false) {
    die('Error in preparing the SQL statement: ' . $conn->error);
}

// Bind parameters
$stmt->bind_param("issssssiidsd",  $product_variation_id, $payment_status, $payment_date, $delivery_status, $delivery_date, $price, $total_price, $quantity, $expenses, $total_amount, $supplier, $purchase_date);

// Set parameters from form data

$purchase_date = !empty($_POST['purchasedate']) ? $_POST['purchasedate'] : null; // Check if payment date is set
$payment_date = !empty($_POST['payment_date']) ? $_POST['payment_date'] : null; // Check if payment date is set
$delivery_date = !empty($_POST['delivery_date']) ? $_POST['delivery_date'] : null; // Check if delivery date is set
$payment_status = $_POST['payment_status'];

$delivery_status = $_POST['delivery_status'];

$supplier = $_POST['supplier'];

// Execute the prepared statement
if ($stmt->execute()) {
    echo "Purchase added successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// Close statements and connection
$stmt_product_name->close();
$stmt->close();
$conn->close();
}
?>

    
    <?php include "sidebar.php"; // Include your sidebar ?>

    <div class="container">
        <div class="col1">
            <h5>Add Product</h5>
            <hr>
            <div class="card1">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          
                 
                 <div class="flex-item">
                 <label for="product_variation_id">Product Variation ID:</label>
        <input type="text" id="product_variation_id" name="product_variation_id"><br><br>
        
                    </div> 
                    <div class="flex-item">
    <label for="productName">Product Name:</label>
    <select id="productName" name="productName">
        <?php
        // Fetch existing product names and IDs from the database
        $product_query = "SELECT ID, Name FROM Product";
        $result_product = $conn->query($product_query);
        while ($row_product = $result_product->fetch_assoc()) {
            echo '<option value="' . $row_product['ID'] . '">' . $row_product['Name'] . '</option>';
        }
        ?>
        <option value="new">New Product</option> <!-- Option for adding a new product -->
    </select>
</div>

<!-- Fields for entering new product details -->
<div id="newProductFields" style="display: none;">
    <div class="flex-item">
        <label for="newProductName">New Product Name:</label>
        <input type="text" id="newProductName" name="newProductName">
    </div>
    <div class="flex-item">
        <label for="newProductSize">New Product Size:</label>
        <input type="text" id="newProductSize" name="newProductSize">
    </div>
</div>



                    <div class="flex-item">
                    <label for="payment_date">Payment Date:</label>
        <input type="date" id="payment_date" name="payment_date"><br><br>
</div>
                 <div class="flex-item">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" required>
                    <br>
                    </div> 
                 
                 <div class="flex-item">
                    <label for="unitCost">Unit Cost:</label>
                    <input type="number" id="unitCost" name="unitCost" required>
                    <br>
                    </div> 
                 
                 <div class="flex-item">
                    
                    </div> 
                 
                 <div class="flex-item">
                 <label for="payment_status">Payment Status:</label>
        <input type="text" id="payment_status" name="payment_status"><br><br>
        <label for="purchasedate">Purchase Date:</label>
        <input type="date" id="purchasedate" name="purchasedate"><br><br>
        
        <label for="delivery_status">Delivery Status:</label>
        <input type="text" id="delivery_status" name="delivery_status"><br><br>
        
        <label for="delivery_date">Delivery Date:</label>
        <input type="date" id="delivery_date" name="delivery_date"><br><br>
        <label for="expenses">Expenses:</label>
        <input type="text" id="expenses" name="expenses"><br><br>
        
        <label for="supplier">Supplier :</label>
        <input type="text" id="supplier" name="supplier"><br><br>
                    
                    </div> 
                    </div>

                 <div class="button-container">
                    <input type="submit" value="Add Product">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include "footer.php"; // Include your footer ?>
    <?php include "navbar.php"; // Include your navbar ?>
    <script>
document.getElementById("productName").addEventListener("change", function() {
    var selectedOption = this.value;
    if (selectedOption === "new") {
        // Show fields for entering new product details
        document.getElementById("newProductFields").style.display = "block";
    } else {
        // Hide fields for entering new product details
        document.getElementById("newProductFields").style.display = "none";
    }
});
</script>
    <script>
    // Autofill payment status based on payment date
    document.getElementById("payment_date").addEventListener("change", function() {
        var paymentStatusInput = document.getElementById("payment_status");
        if (this.value !== "") {
            paymentStatusInput.value = "Paid";
        } else {
            paymentStatusInput.value = "Pending";
        }
    });

    // Autofill delivery status based on delivery date
    document.getElementById("delivery_date").addEventListener("change", function() {
        var deliveryStatusInput = document.getElementById("delivery_status");
        if (this.value !== "") {
            deliveryStatusInput.value = "Delivered";
        } else {
            deliveryStatusInput.value = "Pending";
        }
    });

    // Initially set the status fields when the page loads
    window.addEventListener("DOMContentLoaded", function() {
        var paymentDateInput = document.getElementById("payment_date");
        var deliveryDateInput = document.getElementById("delivery_date");
        var paymentStatusInput = document.getElementById("payment_status");
        var deliveryStatusInput = document.getElementById("delivery_status");

        // Autofill payment status
        if (paymentDateInput.value !== "") {
            paymentStatusInput.value = "Paid";
        } else {
            paymentStatusInput.value = "Pending";
        }

        // Autofill delivery status
        if (deliveryDateInput.value !== "") {
            deliveryStatusInput.value = "Delivered";
        } else {
            deliveryStatusInput.value = "Pending";
        }
    });
</script>

</body>
</html>



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
    <title>Add Product</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="update.css">    
</head>
<body>
    <?php
    // Establish database connection
    include "config.php";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get product details from the form
        $productName = isset($_POST['productName']) ? $_POST['productName'] : null;
        $productSize = isset($_POST['productSize']) ? $_POST['productSize'] : null;
        $newProductName = isset($_POST['newProductName']) ? $_POST['newProductName'] : null;
        $newProductSize = isset($_POST['newProductSize']) ? $_POST['newProductSize'] : null;
        
        if ($productName === "new") {
            // If "New Product" option is selected, use the new product details
            $productName = $newProductName;
            $productSize = $newProductSize;
        }
        
        // Prepare SQL statement to calculate total price
        $price = $_POST['unitCost'];
        $quantity = $_POST['quantity'];
        $total_price = $price * $quantity;

        // Prepare SQL statement to calculate total amount
        $expenses = $_POST['expenses'];
        $total_amount = $total_price + $expenses;

        // Prepare SQL statement to insert data into the Purchase table
        $stmt = $conn->prepare("INSERT INTO purchase (  unit_price, total_price, quantity, expenses, total_amount, supplier, purchase_date) VALUES ( ?, ?, ?, ?, ?, ?, ?)");

        if($stmt === false) {
            die('Error in preparing the SQL statement: ' . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("iidddss", $price, $total_price, $quantity, $expenses, $total_amount, $supplier, $purchase_date);

        // Set parameters from form data
        $purchase_date = isset($_POST['purchasedate']) ? $_POST['purchasedate'] : null; // Check if payment date is set
        $supplier = $_POST['supplier'];

        // Execute the prepared statement
        if ($stmt->execute()) {
            echo "Purchase added successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
// Check if the delivery status is "Delivered"
if ($deliveryStatusInput === "Delivered") {

  // Check if the product exists in the Product table
$product_query = $conn->prepare("SELECT p.ID FROM product p 
INNER JOIN productvariations pv ON p.ID = pv.productId
WHERE p.Name = ? AND pv.size = ?");
$product_query->bind_param("ss", $productName, $productSize);
$product_query->execute();
$product_result = $product_query->get_result();

if ($product_result->num_rows === 0) {
// If the product does not exist, insert it into the Product table
$insert_product = $conn->prepare("INSERT INTO product (Name) VALUES (?)");
$insert_product->bind_param("s", $productName);
$insert_product->execute();

// Get the ID of the newly inserted product
$product_id = $insert_product->insert_id;
} else {
// If the product exists, fetch its ID
$product_row = $product_result->fetch_assoc();
$product_id = $product_row['ID'];
}

// Close the product query statement
$product_query->close();

// Update the ProductVariations table
$update_variation = $conn->prepare("INSERT INTO productvariations (productId, Size) VALUES (?, ?)");
$update_variation->bind_param("is", $product_id, $productSize);
$update_variation->execute();

// Close the prepared statements
$insert_product->close();
$update_variation->close();


// The rest of your code goes here...

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}
    ?>

    <?php include "sidebar.php"; // Include your sidebar ?>

    <div class="container">
        <div class="col1">
            <h5>Add Product</h5>
            <hr>
            <div class="card1">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="flexcontainer"> 
                    <div class="flex-item">
                        <label for="purchasedate">Purchase Date:</label>
                        <input type="date" id="purchasedate" name="purchasedate" class="ht">
                    </div> 

                        <div class="flex-item">
                            <label for="productName">Product Name:</label>
                            <select id="productName" name="productName"class="ht">
                                <?php
                                // Fetch existing product names and IDs from the database
                                $product_query = "SELECT ID, Name FROM Product";
                                $result_product = $conn->query($product_query);
                                while ($row_product = $result_product->fetch_assoc()) {
                                    echo '<option value="' . $row_product['Name'] . '">' . $row_product['Name'] . '</option>';
                                }
                                ?>
                                <option value="new">New Product</option> <!-- Option for adding a new product -->
                            </select>
                        </div>

                        <!-- Fields for entering new product details -->
                        <div id="newProductFields" style="display: none;"class="flex-item">
                            <div class="flex-item">
                                <label for="newProductName">New Product Name:</label>
                                <input type="text" id="newProductName" name="newProductName">
                            </div>
                            <div class="flex-item">
                                <label for="newProductSize">New Product Size:</label>
                                <input type="text" id="newProductSize" name="newProductSize">
                            </div>
                        </div>
                    

                    <!-- Product Size dropdown -->
                    <div class="flex-item">
                        <label for="productSize">Product Size:</label>
                        <select id="productSize" name="productSize"class="ht">
                            <?php
                            // Fetch all product sizes from the database
                            $size_query = "SELECT DISTINCT size FROM productvariations";
                            $result_sizes = $conn->query($size_query);
                            while ($row_size = $result_sizes->fetch_assoc()) {
                                echo '<option value="' . $row_size['size'] . '">' . $row_size['size'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
              
                 
                 <div class="flex-item">
                 <label for="payment_date">Payment Date:</label>
        <input type="date" id="payment_date" name="payment_date"class="ht"><br><br>
                 </div>
                 <div class="flex-item">
                 <label for="payment_status">Payment Status:</label>
        <input type="text" id="payment_status" name="payment_status"><br><br>
                 </div> 
                 <div class="flex-item">
                 <label for="delivery_date">Delivery Date:</label>
        <input type="date" id="delivery_date" name="delivery_date"class="ht">
                 </div> 
                 <div class="flex-item">
                 <label for="delivery_status">Delivery Status:</label>
        <input type="text" id="delivery_status" name="delivery_status">
                 </div> 
                    <div class="flex-item">
                        <label for="unitCost">Unit Cost:</label>
                        <input type="number" id="unitCost" name="unitCost" required>
                        <br>
                    </div> 

                    <div class="flex-item">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" required>
                        <br>
                    </div> 

                    <div class="flex-item">
                        <label for="expenses">Expenses:</label>
                        <input type="text" id="expenses" name="expenses"><br><br>
                    </div> 

                    <div class="flex-item">
                        <label for="supplier">Supplier :</label>
                        <input type="text" id="supplier" name="supplier"><br><br>
                    </div> 
                        </div>
                 
                    <div class="button-container">
                        <input type="submit" value="Add Product">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include "footer.php"; // Include your footer ?>
    <?php include "navbar.php"; // Include your navbar ?>

    <script>
        // Toggle visibility of new product fields based on product selection
        document.getElementById("productName").addEventListener("change", function() {
            var selectedOption = this.value;
            if (selectedOption === "new") {
                document.getElementById("newProductFields").style.display = "block";
            } else {
                document.getElementById("newProductFields").style.display = "none";
            }
        });
    </script>
   <script>
    // Autofill payment status based on payment date
    document.getElementById("payment_date").addEventListener("change", function() {
        var paymentStatusInput = document.getElementById("payment_status");
        if (this.value !== "") {
            paymentStatusInput.value = "Paid";
        } else {
            paymentStatusInput.value = "Pending";
        }
    });

    // Autofill delivery status based on delivery date
    document.getElementById("delivery_date").addEventListener("change", function() {
        var deliveryStatusInput = document.getElementById("delivery_status");
        if (this.value !== "") {
            deliveryStatusInput.value = "Delivered";
        } else {
            deliveryStatusInput.value = "Pending";
        }
    });

    // Initially set the status fields when the page loads
    window.addEventListener("DOMContentLoaded", function() {
        var paymentDateInput = document.getElementById("payment_date");
        var deliveryDateInput = document.getElementById("delivery_date");
        var paymentStatusInput = document.getElementById("payment_status");
        var deliveryStatusInput = document.getElementById("delivery_status");

        // Autofill payment status
        if (paymentDateInput.value !== "") {
            paymentStatusInput.value = "Paid";
        } else {
            paymentStatusInput.value = "Pending";
        }

        // Autofill delivery status
        if (deliveryDateInput.value !== "") {
            deliveryStatusInput.value = "Delivered";
        } else {
            deliveryStatusInput.value = "Pending";
        }
    });
</script>

</body>
</html>
