<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}
include 'config.php';

// Fetch existing product data from the database
$product_query = "SELECT p.*, c.catName AS CategoryName
                  FROM pdt p
                  LEFT JOIN category c ON p.CATEGORY_ID = c.ID ORDER BY CREATED_AT DESC";

$result_product = $conn->query($product_query);
$products = [];
while ($row_product = $result_product->fetch_assoc()) {
    $products[] = $row_product;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Information</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="delete.css">
 
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="container">
    <div class="col1">
        <div id="results"></div>
        <h2 style="text-align:left">Product Information</h2>
        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" id="search-input" placeholder="Search for a product...">
            <a href="addpdt.php"><button class='add'>Add product</button></a>

        </div>
        <hr>
        <?php include "successmessage.php"; ?>
        
        <div class="row">
                <table class="tables" id ='sales-table'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Unit Price</th>
                            <th>Cost Price</th>
                            <th>Quantity</th>
                            <th>Total Cost Price</th>
                            <th>Profit</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="product-table-body">
                    <?php foreach ($products as $product): 
                        $unitprice_formatted = number_format($product['UNITPRICE'], 2);
                        $costprice_formatted = number_format($product['COSTPRICE'], 2);
                        $totalcostprice_formatted = number_format($product['TOTAL_COST_PRICE'], 2);
                        $profit_formatted = number_format($product['PROFIT'], 2);
                    ?>
                        <tr id='product-<?php echo $product['ID']; ?>' class='product-row'>
                            <td><?php echo htmlspecialchars($product['ID']); ?></td>
                            <td><?php echo htmlspecialchars($product['NAME']); ?></td>
                            <td><?php echo htmlspecialchars($product['CategoryName']); ?></td>
                            <td>Shs<?php echo $unitprice_formatted; ?></td>
                            <td>Shs<?php echo $costprice_formatted; ?></td>
                            <td><?php echo htmlspecialchars($product['QUANTITY']); ?></td>
                            <td>Shs<?php echo $totalcostprice_formatted; ?></td>
                            <td>Shs<?php echo $profit_formatted; ?></td>
                            <td><?php echo htmlspecialchars($product['CREATED_AT']); ?></td>
                            <td><?php echo htmlspecialchars($product['UPDATED_AT']); ?></td>
                            <td>
                                <i class="fas fa-pencil update" onclick='updateRecord(<?php echo $product["ID"]; ?>)'></i>
                                <i class="fas fa-trash delete" onclick='showModal(<?php echo $product["ID"]; ?>)'></i>
                            </td>
                        </tr>
                        <!-- <tr id='unit-price-<?php echo $product['ID']; ?>' class='unit-price-row' style='display: none;'>
                            <td colspan='11' style='padding-left: 100px; color: red; background-color: white; font-weight: bold; text-align: right; font-size: 40px; border-left: none;'>Unit Price: Shs<?php echo $unitprice_formatted; ?></td>
                        </tr> -->
                    <?php endforeach; ?>
                    </tbody>
                </table>
        
        </div>
    </div>
</div>
<div class="alert-box" id="alert-box">
    <button class="close-icon" onclick="closeAlert()">&times;</button>
    <p>Product not found</p>
</div>
<?php include "pdtdeletemodal.html"; ?>
<?php include "footer.php"; ?>
<?php include "navbar.php"; ?>

<script>
    let orderIdToDelete;

function updateRecord(id) {
    // Redirect to update page with the ID parameter
    window.location.href = 'pdtupdate.php?id=' + id;
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
    window.location.href = 'delete_product.php?id=' + productIdToDelete;
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
    <p>Order not found</p>
</div>

</body>
</html>