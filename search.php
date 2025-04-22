<?php
include 'config.php';

// Initialize variables
$searchResults = [];

// Check if keywords parameter is set
if (isset($_GET['keywords'])) {
    $keywords = '%' . $_GET['keywords'] . '%';

    // SQL query to search across multiple fields
    $sql = "SELECT p.*, c.catName AS CategoryName 
            FROM pdt p 
            LEFT JOIN category c ON p.CATEGORY_ID = c.ID 
            WHERE p.NAME LIKE ? OR c.catName LIKE ? OR p.UNITPRICE LIKE ? OR p.COSTPRICE LIKE ? OR p.QUANTITY LIKE ? OR p.TOTAL_COST_PRICE LIKE ? OR p.PROFIT LIKE ? OR p.CREATED_AT LIKE ? OR p.UPDATED_AT LIKE ?";
    
    $stmt = $conn->prepare($sql);
    // Bind parameters
    $stmt->bind_param("sssssssss", $keywords, $keywords, $keywords, $keywords, $keywords, $keywords, $keywords, $keywords, $keywords);
    // Execute statement
    $stmt->execute();
    // Get result
    $result = $stmt->get_result();

    // Fetch rows
    while ($row = $result->fetch_assoc()) {
        // Format prices
        $unitprice_formatted = number_format($row['UNITPRICE'], 2);
        $costprice_formatted = number_format($row['COSTPRICE'], 2);
        $totalcostprice_formatted = number_format($row['TOTAL_COST_PRICE'], 2);
        $profit_formatted = number_format($row['PROFIT'], 2);

        // Build product details HTML for dropdown
        $productDetails = "<span>ID: " . htmlspecialchars($row['ID']) . ", ";
        $productDetails .= "Name: " . htmlspecialchars($row['NAME']) . ", ";
        $productDetails .= "Category: " . htmlspecialchars($row['CategoryName']) . ", ";
        $productDetails .= "Unit Price: Shs" . $unitprice_formatted . ", ";
        $productDetails .= "Cost Price: Shs" . $costprice_formatted . ", ";
        $productDetails .= "Quantity: " . htmlspecialchars($row['QUANTITY']) . ", ";
        $productDetails .= "Total Cost Price: Shs" . $totalcostprice_formatted . ", ";
        $productDetails .= "Profit: Shs" . $profit_formatted . ", ";
        $productDetails .= "Created At: " . htmlspecialchars($row['CREATED_AT']) . ", ";
        $productDetails .= "Updated At: " . htmlspecialchars($row['UPDATED_AT']) . "</span>";

        // Add product details to search results array
        $searchResults[] = [
            'id' => $row['ID'],
            'name' => htmlspecialchars($row['NAME']),
            'details' => $productDetails
        ];
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>
