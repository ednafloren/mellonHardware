<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}
include "config.php";

//  Execute a query to fetch the required data
$sql = "SELECT ID, catName, CreatedAt, UpdatedAt FROM category";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
</head>
<body>
    
<?php include "sidebar.php"?> <!-- Include your sidebar -->
<div class="container">
    <div class="col1">
    <h2>Product Categories</h2>
        <a href="addcat.php"><button class='add'>Add category</button></a>
<?php include "successmessage.php"?>

        <hr>

        <div class="row[-">
<?php
// Display data in an HTML table
if ($result->num_rows > 0) {
    echo "<table class='tables'>";
    echo "<tr>
            <th>ID</th>
            <th>Name</th>
            <th>CreatedAt</th>
            <th>UpdatedAt</th>
            <th>Actions</th>
          </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>  
                <td>".$row["ID"]."</td>
          
                <td>".$row["catName"]."</td>
               
                <td>".$row["UpdatedAt"]."</td>
                
                <td>".$row["CreatedAt"]."</td>
                <td>
                                <i class='fas fa-pencil update' onclick='updateRecord(".$row["ID"].")'></i>
                       <i class='fas fa-trash delete' onclick='showModal(".$row["ID"].")' ></i>
                    </td>
              </tr>";
    }
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
<?php include "footer.php"?> <!-- Include your footer -->
<?php include "navbar.php"?> <!-- Include your navbar -->

<script>
    function updateRecord(id) {
        // Redirect to update page with the ID parameter
        window.location.href = 'updatecat.php?ID=' + id;
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
    window.location.href = 'catdelete.php?ID=' + productIdToDelete;
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
</script>
<script src="catdelete.js"></script>

</body>
</html>
