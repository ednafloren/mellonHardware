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
    <title>Product Variations</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">    
</head>
<body>
    <h1>Product Variations</h1>
    
    <?php
        include "sidebar.php"; // Include your sidebar

    // Connect to the database
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

    // Query to retrieve products and their variations with category information
    $sql = "SELECT p.ID AS productId, p.Name AS product_name, c.catName, v.id AS variation_id, v.size, v.price, v.quantity, v.unitcostprice, v.unitsellingprice, v.profit, v.totalcostprice, v.updatedAt, v.createdAt
            FROM product p
            INNER JOIN productvariations v ON p.ID= v.productId
            INNER JOIN category c ON p.categoryID = c.id";

    $result = $conn->query($sql);
    
    ?>
    
    <div class="container">
        <div class="col1">
            <h5>Sales Page</h5>
            <hr>
            <a href="addpro.php"><button class='add'>Add sale</button></a>
            <div class="card1">
                <?php
    if ($result->num_rows > 0) {
        // Start table
        echo "<table>";
        echo "<tr>
                <th>Product Name</th>
                <th>Category</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Unit Cost Price</th>
                <th>Unit Selling Price</th>
                <th>Profit</th>
                <th>Total Cost Price</th>
                <th>Updated At</th>
                <th>Created At</th>
                <th>Actions</th>
              </tr>";

        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["product_name"] . "</td>";
            echo "<td>" . $row["catName"] . "</td>";
            echo "<td>" . $row["size"] . "</td>";
            echo "<td>" . $row["quantity"] . "</td>";
            echo "<td>" . $row["unitcostprice"] . "</td>";
            echo "<td>" . $row["unitsellingprice"] . "</td>";
            echo "<td>" . $row["profit"] . "</td>";
            echo "<td>" . $row["totalcostprice"] . "</td>";
            echo "<td>" . $row["updatedAt"] . "</td>";
            echo "<td>" . $row["createdAt"] . "</td>";
            echo "<td>
            <button class='update' onclick='updateRecord(".$row["variation_id"].")'>Update</button>
            <button class='delete' onclick='deleteRecord(".$row["variation_id"].")'>Delete</button>
                  </td>";
            echo "</tr>";
        }
        
        // End table
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

<?php include "footer.php"; // Include your footer ?>
<?php include "navbar.php"; // Include your navbar ?>
<script>
    function updateRecord(id) {
        // Redirect to update page with the ID parameter
        window.location.href = 'updatep.php?id=' + id;
    }

    function deleteRecord(id) {
        // Redirect to delete page with the ID parameter
        window.location.href = 'delete.php?id=' + id;
    }
</script>
</body>
</html>
