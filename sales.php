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
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
<body>
    
<?php 
    include "sidebar.php"; // Include your sidebar
    
    // Step 1: Connect to the database
    $servername = "localhost"; // Change this to your database server name
    $username = "root"; // Change this to your database username
    $password = ""; // Change this to your database password
    $dbname = "flexflow"; // Change this to your database name
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Step 2: Execute a query to fetch sales data with product names
    $sql = "SELECT s.ID, s.SaleDate, p.Name AS ProductName, s.Quantity, s.UnitPrice, s.TotalPrice, s.CreatedAt, s.UpdatedAt
            FROM sales s
            INNER JOIN product p ON s.ProductID = p.ID";
    $result = $conn->query($sql);
?>
    
<div class="container">
    <div class="col1">
        <h2>Sales</h2>
        <hr>
        <a href="addsale.php"><button class='add'>Add sale</button></a>
        <div class="card1">
            <?php
            // Display sales data in a table
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                      </tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row["ID"]."</td>
                            <td>".$row["SaleDate"]."</td>
                            <td>".$row["ProductName"]."</td>
                            <td>".$row["Quantity"]."</td>
                            <td>".$row["UnitPrice"]."</td>
                            <td>".$row["TotalPrice"]."</td>
                            <td>".$row["CreatedAt"]."</td>
                            <td>".$row["UpdatedAt"]."</td>
                            <td>
                            <button class='update' onclick='updateRecord(".$row["ID"].")'>Update</button>
                            <button class='delete' onclick='showModel(".$row["ID"].")'>Delete</button>
                        </td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>
</div>

<?php include "footer.php"; // Include your footer ?>
<?php include "navbar.php"; // Include your navbar ?>
<script>
    function updateRecord(id) {
        // Redirect to update page with the ID parameter
        window.location.href = 'updatesale.php?id=' + id;
    }

    function deleteRecord(id) {
        // Redirect to delete page with the ID parameter
        window.location.href = 'delete.php?id=' + id;
    }
</script>
</body>
</html>
