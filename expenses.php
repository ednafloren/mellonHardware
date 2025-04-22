<?php
session_start();
include 'config.php'; // Include database connection configuration

// Fetch expenses from the database
$expense_query = "SELECT * FROM expenses ORDER BY expense_date DESC";
$result_expense = $conn->query($expense_query);
$expenses = [];
while ($row_expense = $result_expense->fetch_assoc()) {
    $expenses[] = $row_expense;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Expenses</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- <link rel="stylesheet" href="delete.css"> -->
  
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
</head>
<body>
<?php include "sidebar.php"?>
<div class="container">
    <div class="col1">
    <h2>View Expenses</h2>
    <a href="expenseadd.php"><button class='add'>Add expense</button></a>
    <?php include "successmessage.php"?>
    <div class="row">
    <table class='tables'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Quantity</th>
                
                <th>Amount</th>
                
                <th>Date</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($expenses as $expense): ?>
                <tr>
                    <td><?php echo $expense['expense_id']; ?></td>
                    <td><?php echo $expense['Name']; ?></td>
                    <td><?php echo $expense['description']; ?></td>
                    <td><?php echo $expense['quantity'] !== null ? $expense['quantity'] . ' ' . $expense['unit'] : ''; ?></td>                   
                    
                    <td><?php echo $expense['amount']; ?></td>
                    <td><?php echo $expense['expense_date']; ?></td>

                    <td><?php echo $expense['created_at']; ?></td>
                    <td><?php echo $expense['updated_at']; ?></td>
                    <td>
                    <i class="fas fa-pencil update" onclick='updateRecord(<?php echo $expense["expense_id"]; ?>)'></i>
                    <i class="fas fa-trash delete" onclick='showModal(<?php echo $expense["expense_id"];?>' ></i>
                            </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
            </div>
            </div>
            </div>
            <div id="deleteModal" class="modal">
<?php include "deletemodal.html"?>
        </div>
            <!-- Modal Structure -->


            
    <?php include "footer.php"?> <!-- Include your footer -->
<?php include "navbar.php"?>
<script>
function updateRecord(id) {
        // Redirect to update page with the ID parameter
        window.location.href = 'expenseupdate.php?expense_id=' + id;
    } 




    </script>
       <script src="delete.js"></script>

</body>
</html>
