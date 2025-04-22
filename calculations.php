<?php

include 'config.php';
// Function to count total sales for a specific product
// Function to count total sales for a specific product
function getTotalSalesCountForProduct($conn, $productId) {
    $sql = "SELECT COUNT(*) AS num_sales FROM sales WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['num_sales'];
    } else {
        return 0;
    }
}

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $totalSales = getTotalSalesCountForProduct($conn, $productId);
    $_SESSION['totalSales'] = $totalSales;
}

// total purchases of a product
function getTotalpurchaseCountForProduct($conn, $productId) {
    $sql = "SELECT COUNT(*) AS num_purchase FROM purchase WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['num_purchase'];
    } else {
        return 0;
    }
}


// Function to calculate profit based on business logic
function calculateProfit($unitSellingPrice, $costPrice, $quantitySold) {
    // Example calculation based on your business logic
    $totalSales = $unitSellingPrice * $quantitySold;
    $profit = $totalSales - ($costPrice * $quantitySold);
    return $profit;
}


// total orders of a product
function getTotalorderCountForProduct($conn, $productId) {
    $sql = "SELECT COUNT(*) AS num_order FROM orders o
    JOIN order_pdt op ON op.order_id=o.order_id
     WHERE pdt_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['num_order'];
    } else {
        return 0;
    }
}

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $totalpurchases = getTotalpurchaseCountForProduct($conn, $productId);
    $_SESSION['totalpurchase'] = $totalpurchases;
    $totalorders = getTotalorderCountForProduct($conn, $productId);
    $_SESSION['totalorder'] =   $totalorders;
}
// Function to count total products
function getTotalpdts($conn) {
    $sql = "SELECT COUNT(*) AS pdts FROM pdt";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['pdts'];
    } else {
        return 0;
    }
}

// Fetch the total products
$totalpdts = getTotalpdts($conn);
$_SESSION['totalpdts'] = $totalpdts;

// Function to count total purchases
function getTotalpurchases($conn) {
    $sql = "SELECT COUNT(*) AS purchases FROM purchase";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['purchases'];
    } else {
        return 0;
    }
}

// Fetch the total purchases
$totalpurchases = getTotalpurchases($conn);
$_SESSION['totalpurchases'] = $totalpurchases;

// Function to count total sales
function getTotalSalesCount($conn) {
    $sql = "SELECT COUNT(*) AS num_sales FROM sales";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['num_sales'];
    } else {
        return 0;
    }
}

// Fetch the total sales
$totalSales = getTotalSalesCount($conn);
$_SESSION['totalsale'] = $totalSales;

// Function to get total sales amount
function getTotalSales($conn) {
    $sql = "SELECT SUM(TotalPrice) AS total_sales FROM sales";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_sales'];
    } else {
        return 0;
    }
}

// Fetch total stock amount
function getTotalStockAmount($conn) {
    $sql = "SELECT SUM(TOTAL_COST_PRICE) AS total_amount FROM pdt";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_amount'];
    } else {
        return 0;
    }
}
$_SESSION['stockAmount'] = getTotalStockAmount($conn);

// Fetch total expenses
function getTotalExpenses($conn) {
    $sql = "SELECT SUM(amount) AS total_expenses FROM expenses";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_expenses'];
    } else {
        return 0;
    }
}
$_SESSION['expenses'] = getTotalExpenses($conn);


function countOrdersByStatus($conn, $status) {
    $sql = "SELECT COUNT(*) AS count FROM orders 
            JOIN order_statuses ON orders.status_id = order_statuses.status_id 
            WHERE order_statuses.status_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Fetch pending orders from the database
$sql_pending = "SELECT o.order_id, c.NAME AS customer_name, o.order_date 
                FROM orders o 
                JOIN customer c ON o.customer_id = c.id 
                WHERE o.status_id = '1'";
$result_pending = $conn->query($sql_pending);

$pending_orders = [];
if ($result_pending->num_rows > 0) {
    while ($row = $result_pending->fetch_assoc()) {
        $pending_orders[] = [
            'order_id' => $row['order_id'],
            'customer_name' => $row['customer_name'],
            'order_date' => $row['order_date']
        ];
    }
}

// Store pending orders in session variable
$_SESSION['pending'] = $pending_orders;

// Fetch delivered orders from the database
$sql_delivered = "SELECT o.order_id, c.NAME AS customer_name, o.order_date 
                  FROM orders o 
                  JOIN customer c ON o.customer_id = c.id 
                  WHERE o.status_id = '2'";
$result_delivered = $conn->query($sql_delivered);

$delivered_orders = [];
if ($result_delivered->num_rows > 0) {
    while ($row = $result_delivered->fetch_assoc()) {
        $delivered_orders[] = [
            'order_id' => $row['order_id'],
            'customer_name' => $row['customer_name'],
            'order_date' => $row['order_date']
        ];
    }
}

// Store delivered orders in session variable
$_SESSION['delivered'] = $delivered_orders;

// Function to get low stock items
function getLowStockItems($conn, $threshold = 5) {
    $sql = "SELECT NAME, QUANTITY FROM pdt WHERE QUANTITY <= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $threshold);
    $stmt->execute();
    $result = $stmt->get_result();
    $lowStockItems = [];

    while ($row = $result->fetch_assoc()) {
        $lowStockItems[] = $row;
    }
    return $lowStockItems;
}

// Function to fetch the latest orders
function fetchLatestOrders($conn, $limit = 5) {
    $sql = "SELECT o.order_id, c.NAME AS customer, o.order_date, os.status_name 
            FROM orders o
            JOIN order_pdt op ON o.order_id = op.order_id
            INNER JOIN customer c ON o.customer_id = c.id
            JOIN order_statuses os ON o.status_id = os.status_id
            ORDER BY o.order_date DESC
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    return $orders;
}

// Fetch the latest orders
$latestOrders = fetchLatestOrders($conn);
$_SESSION['latestOrders'] = $latestOrders;

// Function to fetch the latest sales
function fetchLatestSales($conn, $limit = 5) {
    $sql = "SELECT s.ID, s.SaleDate, p.UNITPRICE AS UnitSellingPrice, p.COSTPRICE, p.NAME, s.QuantitySold, s.TotalPrice
            FROM sales s
            INNER JOIN pdt p ON s.product_id = p.ID
            ORDER BY s.SaleDate DESC
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $sales = [];
    while ($row = $result->fetch_assoc()) {
        $sales[] = $row;
    }
    return $sales;
}

// Fetch the latest sales
$latestSales = fetchLatestSales($conn);
$_SESSION['latestSales'] = $latestSales;

// Store values in session variables
$_SESSION['pendingCount'] = countOrdersByStatus($conn, 'pending');
$_SESSION['deliveredCount'] = countOrdersByStatus($conn, 'delivered');
$_SESSION['totalSalesCount'] = getTotalSalesCount($conn);
$_SESSION['totalSalesAmount'] = getTotalSales($conn);
$_SESSION['lowStockItems'] = getLowStockItems($conn);
$_SESSION['lowStockCount'] = count($_SESSION['lowStockItems']);

// Redirect to the dashboard or any other page

$reportType = 'daily'; // Default report type
$reportData = [];
$overallSalesCount = 0;
$overallTotalSales = 0;
$overallTotalProfit = 0;

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
        $sql = "SELECT DATE(SaleDate) AS date, COUNT(*) AS sales_count, SUM(TotalPrice) AS total_sales 
                FROM sales 
                GROUP BY DATE(SaleDate)";
        $reportHeading = "Daily Sales Report";
        break;

    case 'date':
        $date = $_POST['date'];
        $sql = "SELECT DATE(SaleDate) AS date, COUNT(*) AS sales_count, SUM(TotalPrice) AS total_sales 
                FROM sales 
                WHERE DATE(SaleDate) = ?";
        $reportHeading = "Purchases Report for Date: " . htmlspecialchars($date);
        break;

    case 'monthly':
        $month = $_POST['month'];
        $sql = "SELECT DATE_FORMAT(SaleDate, '%Y-%m-%d') AS date, COUNT(*) AS sales_count, SUM(TotalPrice) AS total_sales 
                FROM sales 
                WHERE DATE_FORMAT(SaleDate, '%Y-%m') = ?
                GROUP BY DATE_FORMAT(SaleDate, '%Y-%m-%d')";
        $reportHeading = "Monthly Sales Report for: " . htmlspecialchars($month);
        break;

    case 'annually':
        $year = $_POST['year'];
        $sql = "SELECT DATE_FORMAT(SaleDate, '%Y-%m-%d') AS date, COUNT(*) AS sales_count, SUM(TotalPrice) AS total_sales 
                FROM sales 
                WHERE YEAR(SaleDate) = ?
                GROUP BY DATE_FORMAT(SaleDate, '%Y-%m-%d')";
        $reportHeading = "Annual Sales Report for Year: " . htmlspecialchars($year);
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

    // Retrieve all sales details for this date
    $salesDetails = getSalesDetails($row['date']);

    foreach ($salesDetails as $sale) {
        $unitSellingPrice = $sale['UnitSellingPrice'];
        $costPrice = $sale['COSTPRICE'];
        $quantitySold = $sale['QuantitySold'];

        // Calculate profit for each sale entry
        $profit = calculateProfit($unitSellingPrice, $costPrice, $quantitySold);
        $totalProfit += $profit;
    }

    $row['total_profit'] = $totalProfit;
    $row['sales_details'] = $salesDetails;
    $reportData[] = $row;

    // Accumulate overall totals
    $overallSalesCount += $row['sales_count'];
    $overallTotalSales += $row['total_sales'];
    $overallTotalProfit += $totalProfit;
}


// Store overall total profit in session variable
$_SESSION['overallTotalProfit'] = $overallTotalProfit;
function getSalesDetails($date) {
    global $conn;
    $sql = "SELECT p.UNITPRICE AS UnitSellingPrice, p.COSTPRICE, s.QuantitySold, s.ID AS SaleID, P.name AS product
            FROM sales s
            INNER JOIN pdt p ON s.product_id = p.ID
            WHERE DATE(s.SaleDate) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $salesDetails = [];
    while ($row = $result->fetch_assoc()) {
        $salesDetails[] = $row;
    }

    return $salesDetails;
}
?>