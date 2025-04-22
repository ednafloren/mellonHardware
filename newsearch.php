<?php
// Include your database connection configuration
include 'config.php';

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

    // Check if there are any results
    if ($result->num_rows > 0) {
        echo "<ul>";
        // Fetch rows
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            echo "<a href='product.php?id=" . $row['ID'] . "'>" . htmlspecialchars($row['NAME']) . "</a>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No results found</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Invalid request</p>";
}
?>
