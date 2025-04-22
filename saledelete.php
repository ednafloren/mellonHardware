<?php
session_start();
include 'config.php'; // Include database connection configuration

// Check if product ID is provided and is a valid integer
if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $id = $_GET['id'];

    // Prepare and execute SQL statement to delete the product record
    $stmt = $conn->prepare("DELETE FROM sales WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        $_SESSION['success_message'] = "Sale record deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete product record. Product ID not found.";
    }

    // Close statement
    $stmt->close();
} else {
    $_SESSION['error_message'] = "Invalid product ID.";
}

// Redirect back to the product information page
header("Location: sales2.php");
exit();
?>
