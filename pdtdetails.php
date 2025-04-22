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
            /* border: 1px solid #ccc; */
            padding: 20px;
            max-width: 30%;
            margin: auto auto;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
        }
        .product-details2 p{
            font-size:14px
        }
        .product-details {
            background-color: #fff;
            /* border: 1px solid #ccc; */
            padding: 20px;
            max-width: 30%;
            margin: 2px auto;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
        }
        .product-details:hover {
            background-color: #fff;
            border: 1px solid #ccc;
      
            max-width: 30%;
         
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
        /* .result-content {
            display: flex;
            width: 100%;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 90%;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        } */
        .related-data {
            margin-top: 20px;
        }
        .related-data h2 {
            text-align: center;
        }
        .related-data table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .related-data th, .related-data td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        .related-data th {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        .result-content {
            background-color: #fff;
          
    
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Center items in flex container */
            width: 100%;
            /* background-color: white;
            border: 1px solid #ccc; */
            padding: 10px;
            max-width: 90%;
            margin: 0px auto;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
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
            /* background-color: white;
            border: 1px solid #ccc; */
            padding:2px;
            max-width: 90%;
            margin: 0 auto;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
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
    <?php echo "        <a href='pdt.php'class='viewAll'>View all</a>";?>
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
        
                echo "<h2> " . htmlspecialchars($row['NAME']) . "</h2>";
                echo "<p class='cat'>Category: " . htmlspecialchars($row['CategoryName']) . "</p>";
                echo "<p>Quantity: " . htmlspecialchars($row['QUANTITY']) . "</p>";
                echo "<p class='unit-price'> Shs " . $unitprice_formatted . "</p>";
                // Additional fields can be uncommented and styled as needed
                /*
                echo "<p><strong>Cost Price:</strong> Shs " . $costprice_formatted . "</p>";
                echo "<p><strong>Total Cost Price:</strong> Shs " . $totalcostprice_formatted . "</p>";
                echo "<p><strong>Profit:</strong> Shs " . $profit_formatted . "</p>";
                echo "<p><strong>Created At:</strong> " . htmlspecialchars($row['CREATED_AT']) . "</p>";
                echo "<p><strong>Updated At:</strong> " . htmlspecialchars($row['UPDATED_AT']) . "</p>";
                */
                echo "<div class='buttons'>";
                echo "<button class='update' onclick='updateRecord(" . $row["ID"] . ")'>Update</button>";
                echo "<button class='delete' onclick='showModal(" . $row["ID"] . ")'>Delete</button>";
                echo "</div>";
               
            } else {
                echo "<p style='color: black;'>Product not found</p>";
            }
            echo "</div>";
            echo "<div class='product-details2'>";
            echo "<p style='color: black;'> Sales: .<strong>". $_SESSION['totalSales'] . "</strong></p>";
      
            echo "<p style='color: black;'> Purchases:<strong>". $_SESSION['totalpurchase'] . "</strong></p> ";
            echo "<p style='color: black;'>Orders:<strong>". $_SESSION['totalorder'] . "</strong></p> ";

            echo "</div>";
            echo "</div>";
            // SQL queries to retrieve related sales, orders, and purchases
            $sales_sql = "SELECT * FROM sales WHERE product_id = ?";
            $orders_sql = "SELECT o.*,op.quantity,os.status_name AS status 
                           FROM orders o
                           JOIN order_pdt op ON o.order_id = op.order_id
                           JOIN order_statuses os ON os.status_id = o.status_id
                           WHERE op.pdt_id = ?";
            $purchases_sql = "SELECT * FROM purchase WHERE product_id = ?";

            // Prepare and execute sales query
            $stmt_sales = $conn->prepare($sales_sql);
            $stmt_sales->bind_param("i", $productId);
            $stmt_sales->execute();
            $result_sales = $stmt_sales->get_result();

            // Prepare and execute orders query
            $stmt_orders = $conn->prepare($orders_sql);
            $stmt_orders->bind_param("i", $productId);
            $stmt_orders->execute();
            $result_orders = $stmt_orders->get_result();

            // Prepare and execute purchases query
            $stmt_purchases = $conn->prepare($purchases_sql);
            $stmt_purchases->bind_param("i", $productId);
            $stmt_purchases->execute();
            $result_purchases = $stmt_purchases->get_result();

            // Display related sales
            echo "<div class='related-data'>";
            echo "<h2>Sales</h2>";
            echo "<table>";
            echo "<tr><th>Sale ID</th><th>Date</th><th>Quantity</th><th>Price</th></tr>";
            while ($row_sales = $result_sales->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row_sales['ID']) . "</td>";
                echo "<td>" . htmlspecialchars($row_sales['SaleDate']) . "</td>";
                echo "<td>" . htmlspecialchars($row_sales['QuantitySold']) . "</td>";
                echo "<td>" . htmlspecialchars($row_sales['TotalPrice']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            // Display related orders
            echo "<h2>Orders</h2>";
            echo "<table>";
            echo "<tr><th>Order ID</th><th>Date</th><th>Quantity</th><th>Status</th></tr>";
            while ($row_orders = $result_orders->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row_orders['order_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row_orders['order_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row_orders['quantity']) . "</td>";
                echo "<td>" . htmlspecialchars($row_orders['status']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            // Display related purchases
            echo "<h2>Purchases</h2>";
            echo "<table>";
            echo "<tr><th>Purchase ID</th><th>Date</th><th>Quantity</th><th>Price</th></tr>";
            while ($row_purchases = $result_purchases->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row_purchases['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row_purchases['purchase_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row_purchases['quantity']) . "</td>";
                echo "<td>" . htmlspecialchars($row_purchases['total_price']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<p style='color: black;'>Product ID is not set</p>";
        }
        ?>
           <?php include "pdtdeletemodal.html"; ?>
    <?php include "footer.php"; ?>
    <?php include "navbar.php"; ?>
    </div>
</body>
</html>
