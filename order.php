<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}
include "config.php";

// Execute a query to fetch the required data
$sql = "SELECT o.*, s.status_name, c.Name, 
               (SELECT SUM(pdt.UNITPRICE * po.quantity) 
                FROM order_pdt po 
                INNER JOIN pdt ON po.pdt_id = pdt.id 
                WHERE po.order_id = o.order_id) AS total_price
        FROM orders o
        INNER JOIN customer c ON o.customer_id = c.id
        INNER JOIN order_statuses s ON o.status_id = s.status_id";
$result = $conn->query($sql);

// Update total_price for orders if not already set
$updateTotalPriceSql = "UPDATE order_pdt po
                        INNER JOIN pdt p ON po.pdt_id = p.id
                        SET po.price = (po.quantity * p.UNITPRICE)";
$conn->query($updateTotalPriceSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="delete.css">

    
</head>
<body>

<?php include "sidebar.php"; ?> <!-- Include your sidebar -->
<div class="container">
    <div class="col1">
        <h2>Orders:</h2>
        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" id="search-input" placeholder="Search for an order...">
            <a href="orderadd.php"><button class='add'>Add order</button></a>

        </div>
        <?php include "successmessage.php"?>
        <hr>
        <div class="row">
            <?php
            // Display data in an HTML table
            if ($result->num_rows > 0) {
                echo "<table id='orders-table'class='tables'>";
                echo "<thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Total Price</th>
                            <th>Delivered At</th>
                            <th>Completed At</th>
                            <th>Cancelled At</th>
                            <th>Updated At</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                      </thead>";
                echo "<tbody>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row["order_id"]."</td>
                            <td>".$row["Name"]."</td>
                            <td>".$row["order_date"]."</td>
                            <td>".$row["status_name"]."</td>
                            <td>".$row["total_price"]."</td>
                            <td>".$row["delivered_at"]."</td>
                            <td>".$row["completed_at"]."</td>
                            <td>".$row["cancelled_at"]."</td>
                            <td>".$row["updated_at"]."</td>
                            <td>".$row["deleted_at"]."</td>
                            <td>
                                <a href='orderdetails.php?order_id=".$row["order_id"]."'><i class='fa fa-eye view'></i></a>
                                <i class='fas fa-pencil update' onclick='updateRecord(".$row["order_id"].")'></i>
                                <i class='fas fa-trash delete' onclick='showModal(".$row["order_id"].")'></i>
                            </td>
                          </tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "0 results";
            }
            // Close the database connection
            $conn->close();
            ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideModal()">&times;</span>
        <p>Are you sure you want to delete this order?</p>
        <button class="modal-button" id="confirmDeleteButton">Confirm</button>
        <button class="modal-button" onclick="hideModal()">Cancel</button>
    </div>
</div>
<?php include "footer.php"?> <!-- Include your footer -->
<?php include "navbar.php"?> <!-- Include your navbar -->

<script>
    let orderIdToDelete;

    function updateRecord(id) {
        // Redirect to update page with the ID parameter
        window.location.href = 'orderupdate.php?order_id=' + id;
    }

    let productIdToDelete;

    function showModal(id) {
        productIdToDelete = id;
        document.getElementById('deleteModal').style.display = 'block';
    }

    function hideModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        window.location.href = 'orderdelete.php?order_id=' + productIdToDelete;
    });

    // Display the success message if it exists
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'block';
            // Hide the success message after 5 seconds
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 5000);
        }
    });

    function fetchResults() {
        const query = document.getElementById('search-input').value.toLowerCase();
        const table = document.getElementById('orders-table');
        const rows = Array.from(table.querySelectorAll('tbody tr'));
        const matches = [];
        const nonMatches = [];

        rows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            const orderDetails = Array.from(cells).map(cell => cell.innerText.toLowerCase()).join(' ');

            if (orderDetails.includes(query)) {
                row.classList.add('highlighted');
                matches.push(row);
            } else {
                row.classList.remove('highlighted'); // Remove highlighted class
                nonMatches.push(row);
            }
        });

        // Remove all rows from the tbody
        const tbody = table.querySelector('tbody');
        tbody.innerHTML = '';

        // Append matched rows first, then non-matching rows
        matches.forEach(match => {
            tbody.appendChild(match);
        });

        nonMatches.forEach(nonMatch => {
            tbody.appendChild(nonMatch);
        });


        // If query is empty, clear all highlights
        if (query === '') {
            rows.forEach(row => {
                row.classList.remove('highlighted');
            });
        }


        // If no matches, show alert
        if (matches.length === 0) {
            showAlert();
        } else {
            closeAlert();
        }
    }

    function showAlert() {
        document.getElementById('alert-box').style.display = 'block';
    }

   

    function closeAlert() {
        document.getElementById('alert-box').style.display = 'none';
        // document.getElementById('search-input').value = ''; // Clear search input value
    }

    document.getElementById('search-input').addEventListener('input', fetchResults);
</script>

<div class="alert-box" id="alert-box">
    <button class="close-icon" onclick="closeAlert()">&times;</button>
    <p>Order not found</p>
</div>

</body>
</html>
