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
        .product-details {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            width: 30%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 10px;
            box-sizing: border-box;
            background-color: #fff;
            padding: 20px;
            max-width: 40%;
            margin: 2px auto;
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
        .viewAll {
            display: block;
            text-align: right;
            margin-top: 20px;
            padding: 10px 15px;
            color: blue;
            text-decoration: none;
            border-radius: 5px;
        }
        .result-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Center items in flex container */
            width: 100%;
            background-color: white;
            border: 1px solid #ccc;
            padding: 10px;
            max-width: 90%;
            margin: 0px auto;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
        }
        .up {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            /* background-color: white;
            border: 1px solid #ccc; */
            padding:2px;
            max-width: 90%;
            margin: 0 auto;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
        }

        .up p {
            margin-left: 40px;
            font-size: 16px; /* Adjust font size as needed */
        }

        .up .myview {
            margin-right:50px ;
            color: blue;
            text-decoration: none;
        }

        .up .myview:hover {
            /* color: blue;
            color: white; */
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

        @media (max-width: 768px) {
            .product-details {
                width: 100%; /* Adjust width to full width on smaller screens */
            }
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
    <?php
    // Display search query or product details heading based on search
    if (isset($_GET['keywords']) && !empty(trim($_GET['keywords']))) {
        $keywords = htmlspecialchars($_GET['keywords']);
        echo "<p>Search Results for: '<strong>$keywords</strong>'</p>";
    } else {
        echo "<p>Product Details</p>";
    }

    // Link to view all products
    echo "<p><a href='pdt.php' class='myview'>View all products</a></p>";
    ?>
</div>

        <div class='result-content'>
            <?php
            if (isset($_GET['keywords']) && !empty(trim($_GET['keywords']))) {
                $keywords = '%' . $_GET['keywords'] . '%';

                $sql = "SELECT p.*, c.catName AS CategoryName 
                        FROM pdt p 
                        LEFT JOIN category c ON p.CATEGORY_ID = c.ID 
                        WHERE p.NAME LIKE ? OR c.catName LIKE ? OR p.UNITPRICE LIKE ? OR p.COSTPRICE LIKE ? OR p.QUANTITY LIKE ? OR p.TOTAL_COST_PRICE LIKE ? OR p.PROFIT LIKE ? OR p.CREATED_AT LIKE ? OR p.UPDATED_AT LIKE ?";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssss", $keywords, $keywords, $keywords, $keywords, $keywords, $keywords, $keywords, $keywords, $keywords);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $unitprice_formatted = number_format($row['UNITPRICE'], 2);

                        // Display product details
                        echo "<div class='product-details'>";
                        echo "<h2>" . htmlspecialchars($row['NAME']) . "</h2>";
                        echo "<p class='cat'>Category: " . htmlspecialchars($row['CategoryName']) . "</p>";
                        echo "<p>Quantity: " . htmlspecialchars($row['QUANTITY']) . "</p>";
                        echo "<p class='unit-price'>Shs " . $unitprice_formatted . "</p>";
                        echo "<div class='buttons'>";
                        echo "<button class='update' onclick='updateRecord(" . $row["ID"] . ")'>Update</button>";
                        echo "<button class='delete' onclick='showModal(" . $row["ID"] . ")'>Delete</button>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No results found</p>";
                }

                $stmt->close();
            } else {
                echo "<p>Invalid request</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <?php include "pdtdeletemodal.html"; ?>
    <?php include "footer.php"; ?>
    <?php include "navbar.php"; ?>
    <script src="pdt.js"></script>
</body>
</html>
