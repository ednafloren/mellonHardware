<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}
include "config.php";

// Query to retrieve products and their variations with category information
$sql = "SELECT  pur.* ,p.NAME
        FROM purchase pur 
        INNER JOIN pdt p ON pur.product_id = p.ID";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchases</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">    
</head>
<body>
   
   <?php include "sidebar.php";?> 

    
    <div class="container">

        <div class="col1">
            <h2>Purchases</h2>
          
          
            <div class="search-container">
            <i class="fas fa-search"></i>

            <input type="text" id="search-input" placeholder="Search for a purchase...">
            <a href="addpurchase.php"><button class='add'>Add purchase</button></a>
       
        </div>
        <hr>
            <?php include "successmessage.php"?>
            <div class="row">
                <?php
                if ($result->num_rows > 0) {
                    // Start table
                    echo "<table id='sales-table' class='tables'>";
                
                    echo"<thead>";
       
                    echo "<tr><th>Purchase Date</th><th>Purchase ID</th><th>Product Name</th><th>Payment Status</th>
                  
                    <th>Delivery Status</th>
                  
                    <th>Price</th><th>Total </th><th>Quantity</th><th>Supplier</th>
                    <th>Actions</th></tr>";
        echo"</thead>";
                echo "<tbody>";
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                       
                        echo "<tr>";
                        echo "<td>".$row['purchase_date']."</td>";
                        echo "<td>".$row['id']."</td>";
                        echo "<td>".$row['NAME']."</td>"; // Displaying product name instead of ID
          
                        echo "<td>".$row['payment_status']."</td>";
                        // echo "<td>".$row['payment_date']."</td>";
                        echo "<td>".$row['delivery_status']."</td>";
                        // echo "<td>".$row['delivery_date']."</td>";
                        echo "<td>".$row['unit_price']."</td>";
                        echo "<td>".$row['total_price']."</td>";
                        echo "<td>".$row['quantity']."</td>";
  
                        echo "<td>".$row['supplier']."</td>";
                        echo "<td>
 <i class='fas fa-pencil update' onclick='updateRecord(".$row["id"].")'></i>
                       <i class='fas fa-trash delete' onclick='showModal(".$row["id"].")' ></i>
                              </td>";
                        echo "</tr>";
                    }
                    
                    // End table
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
    <div id="deleteModal" class="modal">
<?php include "deletemodal.html"?>
        </div>
    <?php include "footer.php"; // Include your footer ?>
    <?php include "navbar.php"; // Include your navbar ?>
    <script>
        function updateRecord(id) {
            // Redirect to update page with the ID parameter
            window.location.href = 'purchaeupdate.php?id=' + id;
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
    window.location.href = 'purchasedelete.php?id=' + productIdToDelete;
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
    <p>Purchase not found</p>
</div>




    
</body>
</html>
