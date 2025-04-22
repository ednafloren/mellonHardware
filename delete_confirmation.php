<?php
session_start();
include 'config.php'; // Include database connection configuration

// Check if product ID is provided and is a valid integer
if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $product_id = $_GET['id'];
} else {
    $_SESSION['error_message'] = "Invalid product ID.";
    header("Location: pdt.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Confirmation</title>
</head>
<body>
    <h2>Delete Confirmation</h2>
    <p>Are you sure you want to delete this product?</p>
    <form action="delete_product.php" method="GET">
        <input type="hidden" name="id" value="<?php echo $product_id; ?>">
        <button type="submit">Confirm Deletion</button>
        <a href="product_info.php">Cancel</a>
    </form>
</body>
</html>
