<?php
session_start();
include 'config.php'; // Include database connection configuration

// Check if product ID is provided and is a valid integer
if(isset($_GET['expense_id']) && filter_var($_GET['expense_id'], FILTER_VALIDATE_INT)) {
    $id = $_GET['expense_id'];

    // Prepare and execute SQL statement to delete the product record
    $stmt = $conn->prepare("DELETE FROM expenses WHERE expense_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        $_SESSION['success_message'] = "Expense record deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete product record. Product ID not found.";
    }

    // Close statement
    $stmt->close();
} else {
    $_SESSION['error_message'] = "Invalid product ID.";
}

// Redirect back to the product information page
header("Location: expenses.php");
exit();
?>
