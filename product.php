<?php
session_start();
// Redirect the user to login if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('location:login.php');
    exit;
}
include 'calculations.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="delete.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .product-details2 {
            background-color: #fff;
            padding: 20px;
            max-width: 30%;
            margin: auto auto;
        }
        .product-details2 p{
            font-size:14px
        }
        .product-details {
            background-color: #fff;
            padding: 20px;
            max-width: 40%;
            margin: 2px auto;
        }
        .product-details:hover {
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .product-details p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .product-details .unit-price {
            font-weight: bold;
            font-size: 24px;
            color: red;
        }
        .product-details .cat {
            color: blue;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .buttons button {
            padding: 4px 2px;
            font-size: 12px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }
        .update {
            background-color: blue;
            color: white;
        }
        .delete {
            background-color: #f44336;
            color: white;
        }
        .view-all {
            color: blue;
        }
        .viewAll {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px 15px;
            text-align: right;
            text-decoration: none;
            border-radius: 5px;
        }
        .related-data {
            margin-top: 20px;
        }
        .related-data h2 {
            text-align: center;
        }
        .product-details2 table {
            width: 100%;
            
            border: 1px solid #ccc;
            border-collapse: collapse;
            /* margin-bottom: 20px; */
        }
     .product-details2 th  {
            border: 1px solid #ccc;
            padding: 4px 10px;
              background-color:grey;
              color:white;
          
        }
   .product-details2 td{
  text-align: center;
  font-size:18px;
  font-weight:bold;
  background-color:white;
  border: 1px solid #ccc;
        }
        .related-data th {
            background-color: #f8f8f8;
 
        }
        .result-content {
            background-color: #fff;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
            padding: 10px;
            max-width: 90%;
            margin: 0px auto;
        }
        .allcontent{
            width: 100%;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 90%;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }
        .up {
            align-items: center;
            width: 100%;
            padding:2px;
            max-width: 90%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <?php
    include 'config.php';
    include "sidebar.php";
    ?>
    <div class="container">
        <div class="allcontent">
            <div class="up">
                <?php echo "<a href='pdt.php' class='viewAll'>View all products</a>"; ?>
            </div>
            <div class='result-content'>
                <?php
                // Check if ID parameter is set
                if (isset($_GET['id'])) {
                    $productId = $_GET['id'];

                    // SQL query to retrieve product details
                    $sql = "SELECT p.*, c.catName AS CategoryName 
                            FROM pdt p 
                            LEFT JOIN category c ON p.CATEGORY_ID = c.ID 
                            WHERE p.ID = ?";
                    
                    $stmt = $conn->prepare($sql);
                    // Bind parameter
                    $stmt->bind_param("i", $productId);
                    // Execute statement
                    $stmt->execute();
                    // Get result
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // Fetch product details
                        $row = $result->fetch_assoc();
                        $unitprice_formatted = number_format($row['UNITPRICE'], 2);
                        $costprice_formatted = number_format($row['COSTPRICE'], 2);
                        $totalcostprice_formatted = number_format($row['TOTAL_COST_PRICE'], 2);
                        $profit_formatted = number_format($row['PROFIT'], 2);

                        // Display product details
                        echo "<div class='product-details'>";
                        echo "<h1>" . htmlspecialchars($row['NAME']) . "</h1>";
                        echo "<p class='cat'>Category: " . htmlspecialchars($row['CategoryName']) . "</p>";
                        echo "<p>Quantity: " . htmlspecialchars($row['QUANTITY']) . "</p>";
                        echo "<p class='unit-price'>Shs " . $unitprice_formatted . "</p>";
                        echo "<div class='buttons'>";
                        echo "<button class='update' onclick='updateRecord(" . $row["ID"] . ")'>Update</button>";
                        echo "<button class='delete' onclick='showModal(" . $row["ID"] . ")'>Delete</button>";
                        echo "</div>";
                        // echo "<div class='product-details2'>";
                        // echo "<table>";
                        // echo "<tr><th>Sales</th><th>Purchases</th><th>Orders</th></tr>";
                        // echo "<tr><td>" . htmlspecialchars($_SESSION['totalSales']) . "</td>";
                        // echo "<td>" . htmlspecialchars($_SESSION['totalpurchase']) . "</td>";
                        // echo "<td>" . htmlspecialchars($_SESSION['totalorder']) . "</td></tr>";
                        // echo "</table>";
                        // echo "</div>";
                        echo "</div>";
                        
                        // Display stats in a table
                  
                    } else {
                        echo "<p style='color: black;'>Product not found</p>";
                    }
                }
                ?>
                <?php include "pdtdeletemodal.html"; ?>
                <?php include "footer.php"; ?>
                <?php include "navbar.php"; ?>
                <script src="pdt.js"></script>
            </div>
        </div>
    </div>
</body>
</html>
