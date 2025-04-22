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

include "config.php";
    
   
    
  
    // Check if sale ID is provided
if (!isset($_GET['expense_id'])) {
     echo "Sale ID not provided";
        exit;
}

// Retrieve sale details from the database
$id = $_GET['expense_id'];
$sql = "SELECT * FROM expenses WHERE expense_id='$id'";
  
$result = $conn->query($sql);
    

    
    // Check if the record exists
    if ($result->num_rows === 0) {
        echo "Sale not found";
        exit;
    }

        // Retrieve data from the record
        $row = $result->fetch_assoc();
  // Check if the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize
    $name =  $_POST["name"];
    $description =  $_POST["description"];
    $quantity =  $_POST["quantity"];
    $unit =  $_POST["unit"];
    $amount=  $_POST["amount"];
    $date = $_POST["expense_date"];

  

    // Prepare an SQL statement for updating the record
    $sql = "UPDATE expenses SET Name='$name',description ='$description',quantity='$quantity',unit='$unit',amount='$amount',expense_date='$date'
    WHERE expense_id='$id'";
// Fetch the record with the specified ID
if ($conn->query($sql) === TRUE) {
    header('location:expenses.php');
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}
}




        
 
    
    
    // Close the database connection
    // $conn->close();
?>
  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="update.css">
<body>  
<div class="container">
    <div class="col1">
   
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]. '?expense_id=' . $id); ?>">
    <div class="cardadd">
    <h2>Update Expense</h2>

    <div class="flexcontainer"> 
   
    <div class="flex-item">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name"  value="<?php echo $row['Name']; ?>"required><br><br>
    </div>
    <div class="flex-item">
        <label for="description">Description:</label>
        <input type="text" id="description" name="description"value="<?php echo $row['description']; ?>" required><br><br>
        </div>
        <div class="flex-item">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity"value="<?php echo $row['quantity']; ?>" ><br><br>
        </div>
        <div class="flex-item">
        <label for="unit">Unit:</label>
        <input type="text" id="unit" name="unit" value="<?php echo $row['unit']; ?>"><br><br>
        </div>
        <div class="flex-item">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" step="0.01"value="<?php echo $row['amount']; ?>" required><br><br>
        </div>
     
        <div class="flex-item">
        <label for="expense_date">Date:</label>
        <input type="datetime" id="expense_date" name="expense_date" value="<?php echo $row['expense_date']; ?>"required class="ht"><br><br>
        </div>
    </div>

        <div class="button-container">
                    <input type="submit"  value="Add Expense">
                </div> 
    </div>
    </form>
        </div>
    </div>
</div>

<?php include "footer.php"; // Include your footer ?>
<?php include "navbar.php"; // Include your navbar ?>

</body>
</html>
