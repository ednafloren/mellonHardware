<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
    <style>
  
    </style>
</head>
<body>
<?php
include "sidebar.php";
// Include your database connection file
include 'config.php';
include 'calculations.php';
// Fetch sales data along with size, unit selling price, and product name
$sql = "SELECT s.ID, s.SaleDate, p.UNITPRICE AS UnitSellingPrice, p.COSTPRICE, p.NAME, s.QuantitySold, s.TotalPrice
        FROM sales s
        INNER JOIN pdt p ON s.product_id = p.ID";

$result = mysqli_query($conn, $sql);
$totalSales = getTotalSalesCount($conn);
$totalAmount = getTotalSales($conn);

// Initialize total profit variable
$totalProfit = 0;
?>
<div class="container">
    <div class="col1">
        <h2>Sales Information</h2>
   

        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" id="search-input" placeholder="Search for a sale...">
            <a href="addsale.php"><button class='add'>Add sale</button></a>

        </div>
        <hr>
        <div class="card10">
        <div id="table-container">
            <?php include "successmessage.php"; ?>
            <?php
                      
          if ($result) {
                // Start HTML table

                echo "<table id='sales-table'class='tables'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sale Date</th>
                                <th>Product Name</th>
                                <th>Unit Selling Price</th>
                                <th>Quantity Sold</th>
                                <th>Total Price</th>
                                <th>Profit</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id='sales-table-body'>";

                // Fetch and display each row of the result
                while ($row = mysqli_fetch_assoc($result)) {
                    // Calculate profit for each sale
                    $profit = calculateProfit($row['UnitSellingPrice'], $row['COSTPRICE'], $row['QuantitySold']);
                    $totalProfit += $profit; // Accumulate total profit
                    $_SESSION['profit'] = $totalProfit;
                    echo "<tr>
                            <td>" . $row["ID"] . "</td>
                            <td>" . $row['SaleDate'] . "</td>
                            <td>" . $row['NAME'] . "</td>
                            <td>" . $row['UnitSellingPrice'] . "</td>
                            <td>" . $row['QuantitySold'] . "</td>
                            <td>" . $row['TotalPrice'] . "</td>
                            <td>" . $profit . "</td>
                            <td>
                                <i class='fas fa-pencil update' onclick='updateRecord(".$row["ID"].")'></i>
                                <i class='fas fa-trash delete' onclick='showModal(".$row["ID"].")'></i>
                            </td>
                          </tr>";
                }

                // End HTML table
                echo "</tbody></table>";
            } else {
                // Display error message if query fails
                echo "Error: " . mysqli_error($conn);
            }
            ?>
            </div>
        </div>
    </div>
</div>
<div id="deleteModal" class="modal">
    <?php include "deletemodal.html"; ?>
</div>
<hr>
<div class="card1">
    <h1>Total Sales</h1>
    <p>Total Number of Sales: <?php echo $totalSales; ?></p>
</div>
<div class="card1">
    <h1>Total Amount in Sales</h1>
    <p>Total Amount: <?php echo $totalAmount; ?></p>
</div>
<div class="card1">
    <h1>Total Profit</h1>
    <p>Total Profit: <?php echo $_SESSION['profit']; ?></p>
</div>
<?php include "footer.php"; ?>
<?php include "navbar.php"; ?>

<script>

function updateRecord(id) {
    // Redirect to update page with the ID parameter
    window.location.href = 'updates.php?id=' + id;
}

function showModal(id) {
    productIdToDelete = id;
    document.getElementById('deleteModal').style.display = 'block';
}

function hideModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

document.getElementById('confirmDeleteButton').addEventListener('click', function () {
    window.location.href = 'saledelete.php?id=' + productIdToDelete;
});

// Display the success message if it exists
document.addEventListener('DOMContentLoaded', function () {
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        successMessage.style.display = 'block';
        // Hide the success message after 5 seconds
        setTimeout(function () {
            successMessage.style.display = 'none';
        }, 5000);
    }
});
function fetchResults() {
        const query = document.getElementById('search-input').value.toLowerCase();
        const table = document.getElementById('sales-table');
        // const rows = document.querySelectorAll('#sales-table-body tr');
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
    <p>Sale not found</p>
</div>

<?php
// Close database connection
mysqli_close($conn);
?>
</body>
</html>
