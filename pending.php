<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="product.css">
    <style>
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 70%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5); /* Black background with transparency */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 40px;
            border: 1px solid #888;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: slide-down 0.4s;
        }

        /* Add animation for the modal */
        @keyframes slide-down {
            from { top: -300px; opacity: 0; }
            to { top: 0; opacity: 1; }
        }

        /* Close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-table {
            width: 100%;
            border-collapse: collapse;
            margin-top :10%       }

        .modal-table th, .modal-table td {
            border: 1px solid #ddd;
            padding: 18px;
        }

        .modal-table th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>

<!-- Pending Orders Modal -->
<div id="pendingModal" class="modal">
    <div class="modal-content"    >
        <span class="close" onclick="closeModal('pendingModal')">&times;</span>
        <h2>Pending Orders</h2>
        <table class="modal-table">
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date Ordered</th>
                <th>Actions</th>
            </tr>
            <?php if (!empty($_SESSION['pending'])): ?>
                <?php foreach ($_SESSION['pending'] as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td>
                            <button class='delete' onclick="showDeleteModal(<?php echo htmlspecialchars($order['order_id']); ?>)">Delete</button>
                            <a href='orderdetails.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>'>
                                <button class='view'>View</button>
                            </a>
                            <a href='orderupdate.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>'>
                                <button class='update'>Update</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No pending orders found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- Delivered Orders Modal -->
<div id="deliveredModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('deliveredModal')">&times;</span>
        <h2>Delivered Orders</h2>
        <table class="modal-table">
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date Ordered</th>
                <th>Actions</th>
            </tr>
            <?php if (!empty($_SESSION['delivered'])): ?>
                <?php foreach ($_SESSION['delivered'] as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td>
                            <button class='delete' onclick="showDeleteModal(<?php echo htmlspecialchars($order['order_id']); ?>)">Delete</button>
                            <a href='orderdetails.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>'>
                                <button class='view'>View</button>
                            </a>
                            <a href='orderupdate.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>'>
                                <button class='update'>Update</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No delivered orders found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- Low Stock Items Modal -->
<div id="lowStockModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('lowStockModal')">&times;</span>
        <h2>Low Stock Items</h2>
        <ul>
            <?php if (!empty($_SESSION['lowStockItems'])): ?>
                <?php foreach ($_SESSION['lowStockItems'] as $item): ?>
                    <li><?php echo htmlspecialchars($item['NAME']); ?> - <?php echo htmlspecialchars($item['QUANTITY']); ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No low stock items found.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('deleteModal')">&times;</span>
        <?php include "deletemodal.html"; ?>
    </div>
</div>

<script src="modal.js"></script>

<script>
    let orderIdToDelete;

    function showDeleteModal(id) {
        orderIdToDelete = id;
        document.getElementById('deleteModal').style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function openPendingOrdersModal() {
        document.getElementById('pendingModal').style.display = 'block';
    }

    function openDeliveredOrdersModal() {
        document.getElementById('deliveredModal').style.display = 'block';
    }

    function openLowStockModal() {
        document.getElementById('lowStockModal').style.display = 'block';
    }

    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        window.location.href = 'orderdelete.php?order_id=' + orderIdToDelete;
    });

    // Display the success message if it exists
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'block';
            // Hide the success message after 5 seconds
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 5000);
        }
    });

    // Automatically open the pending orders modal on page load (if needed)
    // window.onload = function() {
    //     document.getElementById('pendingModal').style.display = 'block';
    // }
</script>

</body>
</html>
