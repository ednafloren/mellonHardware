<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}

?>
<?php
include "sidebar.php"; // Include your sidebar

// Database connection and form submission handling
include 'config.php';
            // Check if purchaseId is provided
        //     if (isset($_GET['id'])) {
        //         echo "purchase ID not provided";
        //         exit;
        // }
                // Retrieve purchase details based on purchaseId
                $purchaseId = $_GET['id'];
                $purchase_query = "SELECT  pur.* ,p.NAME
                FROM purchase pur 
                INNER JOIN pdt p ON pur.product_id = p.ID WHERE pur.id = '$purchaseId'";
                $result_purchase = $conn->query($purchase_query);
                    // Check if the record exists
    if ($result_purchase->num_rows === 0) {
        echo "purchase not found";
        exit;
    }
                
                    $row = $result_purchase->fetch_assoc();
            // Check if the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize
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
 $product_query = "SELECT id FROM pdt WHERE Name = '$productName'";
 $result_product = $conn->query($product_query);

 if ($result_product->num_rows > 0) {
     // Product exists, retrieve its ID
     $row = $result_product->fetch_assoc();
     $productId = $row['id'];
 }
   // Calculate total price
   $totalPrice = $unitCost * $quantity;

   // Calculate total amount
   $totalAmount = $totalPrice + $expenses;


    // Prepare an SQL statement for updating the record
    $sql = "UPDATE purchase SET purchase_date ='$purchaseDate', product_id='$productId', payment_date='$paymentDate', payment_status='$paymentStatus', delivery_date='$deliveryDate', delivery_status='$deliveryStatus', unit_price='$unitCost', quantity='$quantity', expenses='$expenses', supplier= '$supplier', total_price='$totalPrice'
      WHERE id='$purchaseId'";
// Fetch the record with the specified ID
if ($conn->query($sql) === TRUE) {
    header('location:purchases.php');
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}
}
                


            ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Purchase</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="update.css">
</head>
<body>
<div class="container">
    <div class="col1">
       
      
            
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]. '?id=' . $purchaseId); ?>">
        <div class="cardadd"> 
        <h2>Update Purchase</h2>
          
                <div class="flexcontainer"> 
                    <div class="flex-item">
                        <label for="purchasedate">Purchase Date:</label>
                        <input type="datetime" id="purchasedate" name="purchasedate" class="ht" value="<?php echo $row['purchase_date']; ?>">
                    </div> 

                    <div class="flex-item">
                        <label for="productName">Product Name:</label>
                        <input type="text" id="productName" name="productName" class="ht" readonly list="productList" value="<?php echo $row['NAME']; ?>">
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
                        <input type="datetime" id="payment_date" name="payment_date" class="ht" value="<?php echo $row['payment_date']; ?>">
                    </div>
                    <div class="flex-item">
                        <label for="payment_status">Payment Status:</label>
                        <input type="text" id="payment_status" name="payment_status" value="<?php echo $row['payment_status']; ?>">
                    </div> 
                    <div class="flex-item">
                        <label for="delivery_date">Delivery Date:</label>
                        <input type="datetime" id="delivery_date" name="delivery_date" class="ht" value="<?php echo $row['delivery_date']; ?>">
                    </div> 
                    <div class="flex-item">
                        <label for="delivery_status">Delivery Status:</label>
                        <input type="text" id="delivery_status" name="delivery_status" value="<?php echo $row['delivery_status']; ?>">
                    </div> 
                    <div class="flex-item">
                        <label for="unitCost">Unit Cost:</label>
                        <input type="number" id="unitCost" name="unitCost" required value="<?php echo $row['unit_price']; ?>">
                    </div> 
                    <div class="flex-item">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" required value="<?php echo $row['quantity']; ?>">
                    </div> 
                   
                    <div class="flex-item">
                        <label for="supplier">Supplier:</label>
                        <input type="text" id="supplier" name="supplier" value="<?php echo $row['supplier']; ?>">
                    </div>
                    <div class="flex-item">
                        <label for="totalPrice">Total Price:</label>
                        <input type="text" id="totalPrice" name="totalPrice" readonly value="<?php echo $row['total_price']; ?>">
                    </div>  
                 
                </div>
                <div class="button-container">
                    <input type="submit" value="Update Purchase">
                </div>
            </form>
            
            
        </div>
    </div>
</div>
<?php include "footer.php";?> 
<?php include "navbar.php";?>
<script>
// Calculate total price when unit cost, quantity, or expenses change
document.getElementById("unitCost").addEventListener("input", function() {
    calculateTotalPrice();
    calculateTotalAmount();
});
document.getElementById("quantity").addEventListener("input", function() {
    calculateTotalPrice();
    calculateTotalAmount();
});
document.getElementById("expenses").addEventListener("input", function() {
    calculateTotalPrice();
    calculateTotalAmount();
});

// Function to calculate total price
function calculateTotalPrice() {
    var unitCost = parseFloat(document.getElementById("unitCost").value);
    var quantity = parseInt(document.getElementById("quantity").value);
    var expenses = parseFloat(document.getElementById("expenses").value || 0);
    var totalPrice = (unitCost * quantity).toFixed(2);

    document.getElementById("totalPrice").value = totalPrice;
}


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

    // Calculate initial total price and total amount
    calculateTotalPrice();
    calculateTotalAmount();
});
</script>
</body>
</html>
