<?php
session_start();
include 'config.php'; // Include database connection configuration

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $quantity = !empty($_POST['quantity']) ? $_POST['quantity'] : null;
    $unit = $_POST['unit'];
    $expense_date = $_POST['expense_date'];

    // Validate input
    if (!empty($name) && !empty($amount) && !empty($description) && !empty($expense_date)) {
        // Set the correct time zone
        date_default_timezone_set('Africa/Nairobi'); // Replace 'YOUR_TIMEZONE' with the appropriate time zone, e.g., 'America/New_York'

        // Add current time to date
        $expense_date .= ' ' . date('H:i:s');

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO expenses (Name, amount, description, quantity, unit, expense_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsiss", $name, $amount, $description, $quantity, $unit, $expense_date);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Expense added successfully.";
        } else {
            $_SESSION['error_message'] = "Error adding expense.";
        }

        $stmt->close();
    } else {
        $_SESSION['error_message'] = "All fields are required.";
    }

    // Redirect back to the form
    header("Location: expenses.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="update.css"> 
</head>
<body>
<?php include "sidebar.php"?>
<div class="container">
    <div class="col1">


    <!-- Success message -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success-message">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <!-- Error message -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error-message">
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="cardadd">
    <h2>Add Expense</h2>
    <div class="flexcontainer"> 
   
    <div class="flex-item">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
    </div>
    <div class="flex-item">
        <label for="description">Description:</label>
        <input type="text" id="description" name="description" required><br><br>
        </div>
        <div class="flex-item">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" ><br><br>
        </div>
        <div class="flex-item">
        <label for="unit">Unit:</label>
        <input type="text" id="unit" name="unit" ><br><br>
        </div>
        <div class="flex-item">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" step="0.01" required><br><br>
        </div>
     
        <div class="flex-item">
        <label for="expense_date">Date:</label>
        <input type="date" id="expense_date" name="expense_date" required class="ht"><br><br>
        </div>
    </div>

        <div class="button-container">
                    <input type="submit" name="submit" value="Add Expense">
                </div> 
    </div>
    </form>
    </div>
    </div>

    <?php include "footer.php"?> <!-- Include your footer -->
<?php include "navbar.php"?>
</body>
</html>
