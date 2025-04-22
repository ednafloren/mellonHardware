<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}
include "config.php";

if (!isset($_GET['order_id'])) {
    echo "No order ID provided.";
    exit;
}

$order_id = $_GET['order_id'];

// Fetch order details
$sql = "SELECT o.*, s.status_name, c.Name AS customer_name
        FROM orders o
        INNER JOIN customer c ON o.customer_id = c.id
        INNER JOIN order_statuses s ON o.status_id = s.status_id
        WHERE o.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows == 0) {
    echo "Order not found.";
    exit;
}

$order = $order_result->fetch_assoc();

// Fetch order products
$sql = "SELECT po.*, p.NAME, p.UNITPRICE
        FROM order_pdt po
        INNER JOIN pdt p ON po.pdt_id = p.id
        WHERE po.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$products_result = $stmt->get_result();
$order_total_price = 0; // Initialize total price for the order
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="reports.css">
    <link rel="stylesheet" href="product.css">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="container">
<div class="printrep" style="text-align:right"><?php include 'printreport.html';?></div>


    <div class="col1">
   
        <div class="card1">
        <div class="card8">
            <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
            <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status_name']); ?></p>
</div>
            <h2>Products</h2>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
                <?php while ($product = $products_result->fetch_assoc()) : 
                    $product_total_price = $product['quantity'] * $product['UNITPRICE'];
                    $order_total_price += $product_total_price;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['NAME']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($product['UNITPRICE']); ?></td>
                        <td><?php echo htmlspecialchars($product_total_price); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <h3 style="text-align:right">Order Total: <?php echo htmlspecialchars($order_total_price); ?></h3>
            <hr>
        </div>
      
    </div>
</div>

<?php include "footer.php"; ?>
<?php include "navbar.php"; ?>
</body>
</html>