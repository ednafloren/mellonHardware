<?php
session_start();
include 'config.php';
include 'calculations.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reportType = $_POST['report_type'];
    $reportData = [];

    switch ($reportType) {
        case 'daily':
            $sql = "SELECT DATE(order_date) AS date, COUNT(*) AS order_count, SUM(price) AS total_amount 
                    FROM order_pdt op 
                    INNER JOIN orders o ON op.order_id = o.id 
                    GROUP BY DATE(order_date)";
            break;

        case 'date':
            $date = $_POST['date'];
            $sql = "SELECT DATE(order_date) AS date, COUNT(*) AS order_count, SUM(price) AS total_amount 
                    FROM order_pdt op 
                    INNER JOIN orders o ON op.order_id = o.id 
                    WHERE DATE(order_date) = ?";
            break;

        case 'monthly':
            $month = $_POST['month'];
            $sql = "SELECT DATE_FORMAT(order_date, '%Y-%m') AS date, COUNT(*) AS order_count, SUM(price) AS total_amount 
                    FROM order_pdt op 
                    INNER JOIN orders o ON op.order_id = o.id 
                    WHERE DATE_FORMAT(order_date, '%Y-%m') = ?";
            break;

        case 'annually':
            $year = $_POST['year'];
            $sql = "SELECT YEAR(order_date) AS date, COUNT(*) AS order_count, SUM(price) AS total_amount 
                    FROM order_pdt op 
                    INNER JOIN orders o ON op.order_id = o.id 
                    WHERE YEAR(order_date) = ?";
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

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        // Initialize total profit for this date
        $totalProfit = 0;

        // Retrieve all order details for this date
        $orderDetails = getOrderDetails($row['date']);

        foreach ($orderDetails as $order) {
            $unitSellingPrice = $order['price'];
            $costPrice = $order['COSTPRICE'];
            $quantityOrdered = $order['QuantityOrdered'];

            // Calculate profit for each order entry
            $profit = calculateProfit($unitSellingPrice, $costPrice, $quantityOrdered);
            $totalProfit += $profit;
        }

        $row['total_profit'] = $totalProfit;
        $reportData[] = $row;
    }
}

// Function to fetch order details based on date (replace with your actual logic)
function getOrderDetails($date) {
    global $conn;
    // Example query, replace with your actual query to fetch UnitSellingPrice and CostPrice
    $sql = "SELECT op.*, p.UNITPRICE AS UnitSellingPrice, p.COSTPRICE, o.order_date, o.customer_id, o.status_id
            FROM order_pdt op 
            INNER JOIN pdt p ON op.pdt_id = p.id 
            INNER JOIN orders o ON op.order_id = o.id 
            WHERE DATE(o.order_date) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $orderDetails = [];
    while ($row = $result->fetch_assoc()) {
        $orderDetails[] = $row;
    }

    return $orderDetails;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Order Report</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Order Count</th>
                <th>Total Amount</th>
                <th>Total Profit</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($reportData)) {
                foreach ($reportData as $data) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($data['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($data['order_count']) . "</td>";
                    echo "<td>" . number_format($data['total_amount'], 2) . "</td>";
                    echo "<td>" . number_format($data['total_profit'], 2) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No data found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
