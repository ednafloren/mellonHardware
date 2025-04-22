<?php
session_start();
include 'config.php'; // Include database connection configuration

// Check if order ID is provided and is a valid integer
if(isset($_GET['order_id']) && filter_var($_GET['order_id'], FILTER_VALIDATE_INT)) {
    $order_id = $_GET['order_id'];

    // Prepare and execute SQL statement to delete the order from orders table
    $sqlDeleteOrder = "DELETE FROM orders WHERE order_id = ?";
    $stmtDeleteOrder = $conn->prepare($sqlDeleteOrder);
    $stmtDeleteOrder->bind_param('i', $order_id);

    // Execute the deletion query
    if ($stmtDeleteOrder->execute()) {
        // Check if any rows were affected (order_id exists)
        if ($stmtDeleteOrder->affected_rows > 0) {
            $_SESSION['success_message'] = "Order deleted successfully.";

            // Since ON DELETE CASCADE is set on order_pdt table, related records will be deleted automatically
            $_SESSION['success_message'] .= " Related order products also deleted.";
        } else {
            $_SESSION['error_message'] = "Failed to delete order. Order ID not found.";
        }
    } else {
        $_SESSION['error_message'] = "Failed to execute deletion query.";
    }

    // Close statement
    $stmtDeleteOrder->close();
} else {
    $_SESSION['error_message'] = "Invalid order ID.";
}

// Redirect back to the order information page
header("Location: order.php");
exit();
?>
