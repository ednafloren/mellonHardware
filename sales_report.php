<?php
include 'config.php';
include 'calculations.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="reports.css">
    <script>
        function toggleDetails(date) {
            var detailsRow = document.getElementById('details-' + date);
            if (detailsRow.style.display === 'none') {
                detailsRow.style.display = 'table-row';
            } else {
                detailsRow.style.display = 'none';
            }
        }
        function showInputFields() {
            var reportType = document.getElementById('report_type').value;
            document.getElementById('date-input').style.display = (reportType == 'date') ? 'block' : 'none';
            document.getElementById('month-input').style.display = (reportType == 'monthly') ? 'block' : 'none';
            document.getElementById('year-input').style.display = (reportType == 'annually') ? 'block' : 'none';
        }
    </script>
</head>
<body>

        <h2><?php echo $reportHeading; ?></h2>

     

        
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Sales Count</th>
                <th>Total Sales</th>
                <th>Total Profit</th>
                <th class="details">Details</th>
            </tr>
        </thead>
        <tbody>
        <?php
// Example PHP code for generating sales report table
if (!empty($reportData)) {
    foreach ($reportData as $data) {
        echo "<tr>";

        echo "<td>" . htmlspecialchars($data['date']) . "</td>";
        echo "<td>" . htmlspecialchars($data['sales_count']) . "</td>";
        echo "<td>" . number_format($data['total_sales'], 2) . "</td>";
        echo "<td>" . number_format($data['total_profit'], 2) . "</td>";
        echo "<td class='details'><button class=\"details-button\" onclick=\"toggleDetails('" . htmlspecialchars($data['date']) . "')\">View Details</button></td>";
        echo "</tr>";

        echo "<tr id=\"details-" . htmlspecialchars($data['date']) . "\" style=\"display:none;\">";
        echo "<td colspan='5'>";
        echo "<table class=\"details-table\">";
        echo "<thead>";
        echo "<tr><th>Sale ID</th><th>Product</th><th>Quantity Sold</th><th>Unit Selling Price</th><th>Cost Price</th><th>Total Price</th></tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($data['sales_details'] as $sale) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($sale['SaleID']) . "</td>";
            echo "<td>" . htmlspecialchars($sale['product']) . "</td>";
            echo "<td>" . htmlspecialchars($sale['QuantitySold']) . "</td>";
            echo "<td>" . number_format($sale['UnitSellingPrice'], 2) . "</td>";
            echo "<td>" . number_format($sale['COSTPRICE'], 2) . "</td>";
            echo "<td>" . number_format($sale['QuantitySold'] * $sale['UnitSellingPrice'], 2) . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</td>";
        echo "</tr>";
    }
    
  
} else {
    echo "<tr><td colspan='5'>No data found</td></tr>";
}

?>
            
        </tbody>
    </table>
    <?php
    // Add a row for the overall totals
    echo "<div class=\"overall-total\">";
    echo "<p> Total sales: <strong>Shs" . number_format($overallTotalSales, 2) . "</strong></p>";
    echo "<p> Total profits:<strong> Shs" . number_format($overallTotalProfit, 2) . "</strong></p>";
    echo "<p>Number of  Sales :<strong> " . htmlspecialchars($overallSalesCount) . "</strong></p>";
    echo "</div>";
    echo "<hr>";
    
     ?>

</body>
</html>
