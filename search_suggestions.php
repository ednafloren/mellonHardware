<?php
include 'config.php'; // Include your database connection configuration

// Function to format date if the input is a valid date
function format_date_if_possible($date_string) {
    try {
        $date = new DateTime($date_string);
        return $date->format('Y-m-d'); // Change format as per your database format
    } catch (Exception $e) {
        return false;
    }
}

// Check if keywords parameter is set
if (isset($_GET['keywords'])) {
    $keywords = $_GET['keywords'];

    // Attempt to format the keywords as date
    $formatted_date = format_date_if_possible($keywords);
    if ($formatted_date) {
        $keywords = '%' . $formatted_date . '%';
    } else {
        $keywords = '%' . $keywords . '%';
    }

    // SQL query to search across multiple tables
    $sql = "SELECT 
                'orders' AS type, o.order_id AS id, o.order_date AS search_field, s.status_name AS orderstatus, c.Name AS customer, p.NAME AS product
            FROM 
                orders o
            LEFT JOIN
                order_statuses s ON o.status_id = s.status_id
            LEFT JOIN
                customer c ON o.customer_id = c.id
            LEFT JOIN
                order_pdt op ON o.order_id = op.order_id
            LEFT JOIN
                pdt p ON op.pdt_id = p.ID
            WHERE 
                o.order_id LIKE ? OR 
                o.order_date LIKE ? OR
                s.status_name LIKE ? OR
                c.Name LIKE ? OR
                p.NAME LIKE ?
            UNION 
     SELECT 
                'sales' AS type, s.ID AS id, s.SaleDate AS search_field, '' AS orderstatus, '' AS customer, p.NAME AS product
            FROM 
                sales s
            LEFT JOIN
                pdt p ON s.product_id = p.ID
            WHERE 
                s.ID LIKE ? OR 
                s.SaleDate LIKE ? OR
                p.NAME LIKE ?
            UNION 
            SELECT 
                'purchase' AS type, p.id AS id, p.purchase_date AS search_field, p.payment_status AS orderstatus, '' AS supplier, pr.NAME AS product
            FROM 
                purchase p
            LEFT JOIN
                pdt pr ON p.product_id = pr.ID
            WHERE 
                p.id LIKE ? OR 
                p.purchase_date LIKE ? OR 
                p.supplier LIKE ? OR
                pr.NAME LIKE ? OR
                p.payment_status LIKE ?";

    // Prepare the SQL statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters for each occurrence of ?
        $stmt->bind_param("sssssssssssss", 
            $keywords, $keywords, $keywords, $keywords, $keywords, // For orders table
            $keywords, $keywords, $keywords,// For sales table
            $keywords, $keywords, $keywords, $keywords, $keywords // For purchases table
        );
        
        // Execute statement
        $stmt->execute();
        
        // Get result
        $result = $stmt->get_result();

        // Collect results
        $suggestions = [];
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = $row;
        }

        // Output JSON encoded suggestions
        echo json_encode($suggestions);

        // Close statement
        $stmt->close();
    } else {
        // If the statement could not be prepared, output the SQL error
        echo json_encode(["error" => "Error preparing SQL statement: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}

// Close database connection
$conn->close();
?>
