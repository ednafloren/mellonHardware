<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}

// Ensure $receiptData is set and not empty
if (!isset($_SESSION['receiptData']) || empty($_SESSION['receiptData'])) {
    header('location:addsale.php');
    exit;
}

// Access receipt data from session
$receiptData = $_SESSION['receiptData'];

// Unset the session variable to prevent displaying the receipt again on refresh
unset($_SESSION['receiptData']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="receipt.css"> <!-- Create a receipt.css file for styling -->
    <style>
        @media print {
            /* Hide sidebar, footer, and navbar when printing */
            .sidebar, .footer, .navbar, .submit {
                display: none;
            }
            
            /* Additional print styles */
            .container {
                max-width: 100%;
                margin: 0;
                padding: 0;
            }
            .col1 {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .card1 {
                margin: 20px;
                padding: 20px;
                border: 1px solid #ccc;
                background-color: #fff;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
        }
    </style>
</head>
<body>

<?php include "sidebar.php";?>

<div class="container">
    <div class="col1">
        <div class="card1">
            <h1>Receipt</h1>
            <p><strong>Sale Date:</strong> <?php echo $receiptData["Sale Date"]; ?></p>
            <p><strong>Product Name:</strong> <?php echo $receiptData["Product Name"]; ?></p>
            <p><strong>Quantity Sold:</strong> <?php echo $receiptData["Quantity Sold"]; ?></p>
            <p><strong>Unit Price:</strong> <?php echo $receiptData["Unit Price"]; ?></p>
            <p><strong>Total Price:</strong> <?php echo $receiptData["Total Price"]; ?></p>
            
            <!-- Print button -->
             <div class="submit">
            <button onclick="window.print()">Print Receipt</button>
    </div>
        </div>
    </div>
</div>

<?php include "footer.php";?> <!-- Include your footer -->
<?php include "navbar.php";?> <!-- Include your navbar -->

</body>
</html>
