
<?php Include 'calculations.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- <link rel="stylesheet" href="dashboard.css"> -->
    <!-- <link rel="stylesheet" href="product.css"> -->
    <title>dashboard</title>
    
    
</head>
<body> 
<!-- cards -->
<div class="container">
    <div class="fastrow">
        <hr>

 <div class="card4">
 <hr>
 <div class="card3" onclick="funcModal()">
 <i class="fa fa-clock" ></i>
 <div class="text-content">
            <p>Pending Orders</p>
           <h1> <?php echo $_SESSION['pendingCount']; ?></h1>
           </div>
           </div>
           <div class="card3" onclick="deModal()">
    <i class="fa fa-check-circle"></i>
    <div class="text-content">
        <p>Orders Delivered</p>
        <h1><?php echo $_SESSION['deliveredCount']; ?></h1>
    </div>
</div>
 <div class="card3"onclick="lowStockModal()">

 <i class="fa fa-exclamation-circle" ></i>
 <div class="text-content">
 <p> Low Stock</p>
<h1><?php echo $_SESSION['lowStockCount']; ?></h1>   
       
</div>  

    </div>
  
</div>
<hr>
</div>

<?php include 'pending.php'; ?>
<!-- totals -->
<div class="myrow">

 <div class="cards">
 <a href="pdt.php"class="totalpdts">


 
 <i class="fas fa-box"></i>

 <div class="card7">
            <p>PRODUCTS </p>
           <h1> <?php echo $_SESSION['totalpdts']; ?></h1>
           
           </div>
         
                </a>
                <a href="sales2.php"class="totalpdts">

        
           <i class="fas fa-receipt"></i>


           <div class="card7">
<p> SALES </p>
<h1><?php echo $_SESSION['totalsale']; ?></h1>
</div>

      </a>
                <a href="purchases.php"class="totalpdts">
        


<i class="fas fa-shopping-cart"></i>
  
<div class="card7">
<p> PURCHASES</p>
<h1><?php echo$_SESSION['totalpurchases']; ?></h1>   
</div>

         </a>   </div>      </div>

<div class="row">
            <div class="card1">
            <div class="latest">
           
                    <i class="fas fa-newspaper"></i>
          
                <h4>LATEST SALES:</h4>

                <a href="sales2.php" class="viewAll"> <button>View all</button></a>
                <div class="button-container">
                <a href="addsale.php"><button class="addsale">Add sale</button></a>
            </div>
            </div>
     
           
            <table class="latests">
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Sale Date</th>
                </tr>
                <?php foreach ($_SESSION['latestSales'] as $sales): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sales['ID']); ?></td>
                        <td><?php echo htmlspecialchars($sales['NAME']); ?></td>
                        <td><?php echo htmlspecialchars($sales['QuantitySold']); ?></td>
                        <td><?php echo htmlspecialchars($sales['UnitSellingPrice']); ?></td>
                        <td><?php echo htmlspecialchars($sales['TotalPrice']); ?></td>
                        <td><?php echo htmlspecialchars($sales['SaleDate']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="card1">
            <div class="latest">

                    <i class="fas fa-newspaper"></i>

                <h4>LASTEST ORDERS:</h4>
               
            <a href="order.php" class="viewAll"><button>View all</button></a>
            <div class="button-container">
                <a href="orderadd.php"><button class="addsale">Add order</button></a>
            </div>
            </div>
         
          
            <table class="latests">
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date Ordered</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($_SESSION['latestOrders'] as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td><?php echo htmlspecialchars($order['status_name']); ?></td>
                        <td>
                            <a href="orderdetails.php?order_id=<?php echo htmlspecialchars($order["order_id"]); ?>"><i class="fa fa-eye view"></i></a>
                            <i class="fas fa-pencil update" onclick="updateRecord(<?php echo htmlspecialchars($order['order_id']); ?>)"></i>
                            <i class="fas fa-trash delete" onclick="showModal(<?php echo htmlspecialchars($order['order_id']); ?>)"></i>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <!-- </div> -->



<!-- MONEY -->
<div class="myrow">
<div class="card4">
<div class="card5">
<p> STOCK AMOUNT</p>
<h1><?php echo"Shs".$_SESSION['stockAmount']; ?></h1> 
</div>

<div class="card5">
<p>AMOUNT SOLD</p>
<h1><?php echo "Shs".$_SESSION['totalSalesAmount']; ?></h1> 
</div>
<div class="card5">
<p> PROFIT</p>

<h1><?php echo"Shs". number_format($_SESSION['overallTotalProfit'], 2) ?></h1> 
</div>
<div class="card5">
<p> EXPENSES</p>
<h1><?php echo"Shs".$_SESSION['expenses'] ; ?></h1> 
</div>

                </DIV>
                </div>


                <div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideModal()">&times;</span>
        <p>Are you sure you want to delete this order?</p>
        <button class="modal-button" id="confirmDeleteButton">Confirm</button>
        <button class="modal-button" onclick="hideModal()">Cancel</button>
    </div>
</div>


                
            </div>
            
            <script src="modal.js"></script>
            <script>
    let orderIdToDelete;

    function updateRecord(id) {
        // Redirect to update page with the ID parameter
        window.location.href = 'orderupdate.php?order_id=' + id;
    }

    let productIdToDelete;



function showModal(id) {
    productIdToDelete = id;
    document.getElementById('deleteModal').style.display = 'block';
}

function hideModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

document.getElementById('confirmDeleteButton').addEventListener('click', function() {
    window.location.href = 'orderdelete.php?order_id=' + productIdToDelete;
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
</script>
      
 </body>
</html> 