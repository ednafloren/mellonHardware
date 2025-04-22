<?php
session_start();
include 'config.php'; // Include database connection configuration

// Check if product ID is provided and is a valid integer
if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $purchase_id = $_GET['id'];

    // Prepare and execute SQL statement to delete the product record
    $stmt = $conn->prepare("DELETE FROM purchase WHERE id = ?");
    $stmt->bind_param("i", $purchase_id);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        $_SESSION['success_message'] = "purchase record deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete product record. purchase ID not found.";
    }

    // Close statement
    $stmt->close();
} else {
    $_SESSION['error_message'] = "Invalid product ID.";
}

// Redirect back to the product information page
header("Location: purchases.php");
exit();
?>
