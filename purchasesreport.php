<?php
include 'config.php';

 // Default report type
$reportData = [];
$totalPurchasesCount = 0;
$totalPurchasesAmount = 0;

// Get the current date, month, and year
$currentDate = date('Y-m-d');
$currentMonth = date('Y-m');
$currentYear = date('Y');

$date = $currentDate;
$month = $currentMonth;
$year = $currentYear;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['report_type'])) {
    $reportType = $_POST['report_type'];
}

switch ($reportType) {
    case 'daily':
        $sql = "SELECT DATE(purchase_date) AS date, COUNT(*) AS purchase_count, SUM(total_price) AS total_purchases 
                FROM purchase 
                GROUP BY DATE(purchase_date)";
        $reportHeading = "Daily Purchases Report";
        break;

    case 'date':
        $date = $_POST['date'] ?? $currentDate;
        $sql = "SELECT DATE(purchase_date) AS date, COUNT(*) AS purchase_count, SUM(total_price) AS total_purchases 
                FROM purchase 
                WHERE DATE(purchase_date) = ?";
        $reportHeading = "Purchases Report for Date: " . htmlspecialchars($date);
        break;

    case 'monthly':
        $month = $_POST['month'] ?? $currentMonth;
        $sql = "SELECT DATE(purchase_date) AS date, COUNT(*) AS purchase_count, SUM(total_price) AS total_purchases 
                FROM purchase 
                WHERE DATE_FORMAT(purchase_date, '%Y-%m') = ?
                GROUP BY DATE(purchase_date)";
        $reportHeading = "Monthly Purchases Report for: " . htmlspecialchars($month);
        break;

    case 'annually':
        $year = $_POST['year'] ?? $currentYear;
        $sql = "SELECT DATE(purchase_date) AS date, COUNT(*) AS purchase_count, SUM(total_price) AS total_purchases 
                FROM purchase 
                WHERE YEAR(purchase_date) = ?
                GROUP BY DATE(purchase_date)";
        $reportHeading = "Annual Purchases Report for Year: " . htmlspecialchars($year);
        break;
}

$stmt = $conn->prepare($sql);

if ($reportType === 'date') {
    $stmt->bind_param("s", $date);
} elseif ($reportType === 'monthly') {
    $stmt->bind_param("s", $month);
} elseif ($reportType === 'annually') {
    $stmt->bind_param("i", $year);
}

if (!$stmt->execute()) {
    echo "Error executing query: " . $stmt->error;
    exit;
}

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $date = $row['date'];

    // Fetch individual purchases for the specific date
    $individualPurchases = [];
    $purchaseStmt = $conn->prepare("SELECT pur.*, p.NAME AS product FROM
    purchase pur INNER JOIN 
    pdt p ON pur.product_id = p.ID
    WHERE DATE(purchase_date) = ?");
    $purchaseStmt->bind_param("s", $date);
    if (!$purchaseStmt->execute()) {
        echo "Error executing query: " . $purchaseStmt->error;
        exit;
    }
    $purchaseResult = $purchaseStmt->get_result();

    while ($purchaseRow = $purchaseResult->fetch_assoc()) {
        $individualPurchases[] = $purchaseRow;
    }

    $row['individual_purchases'] = $individualPurchases;
    $reportData[] = $row;

    $totalPurchasesCount += $row['purchase_count'];
    $totalPurchasesAmount += $row['total_purchases'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Report</title>
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
                    <th>Purchases Count</th>
                    <th>Total Purchases</th>
                    <th class="details">Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($reportData)) {
                    foreach ($reportData as $data) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($data['date']) . "</td>";
                        echo "<td>" . htmlspecialchars($data['purchase_count']) . "</td>";
                        echo "<td>" . number_format($data['total_purchases'], 2) . "</td>";
                        echo "<td class='details'><button class=\"details-button\" onclick=\"toggleDetails('" . htmlspecialchars($data['date']) . "')\">View Details</button></td>";
                        echo "</tr>";

                        echo "<tr id=\"details-" . htmlspecialchars($data['date']) . "\" style=\"display:none;\">";
                        echo "<td colspan='5'>";
                        echo "<table class=\"details-table\">";
                        echo "<thead>";
                        echo "<tr><th>Purchase ID</th><th>Product</th><th>Quantity</th><th>Total Price</th></tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        foreach ($data['individual_purchases'] as $purchase) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($purchase['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($purchase['product']) . "</td>";
                            echo "<td>" . htmlspecialchars($purchase['quantity']) . "</td>";
                            echo "<td>" . number_format($purchase['total_price'], 2) . "</td>";
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

        <div class="overall-total">
            <p>Total purchases: <strong>Shs<?php echo number_format($totalPurchasesAmount, 2); ?></strong></p>
            <p>Number of purchases: <strong><?php echo $totalPurchasesCount; ?></strong></p>
        </div>
        <hr>
            </div>
</body>
</html>
