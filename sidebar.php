<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Sidebar</title>
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="reports.css">

</head>
<body>
    <div class="sidebar" id="sidebar">
        <a href="home.php">Home</a>
        <a href="pdt.php">Products</a>
        <a href="order.php">Orders</a>
        <a href="category.php">Categories</a>
        <a href="customer.php">Customers</a>
        <a href="sales2.php">Sales</a>
        <a href="purchases.php">Purchases</a>
        <a href="expenses.php">Expenses</a>
        
   <button class="dropdown-btn">Reports

  </button>
  <div class="dropdown-container">
    <a href="orderpurchasereports">View All</a>
    <a href="purchaserep.php">Purchases</a>
    <a href="salerep.php">Sales</a>
  </div>

    </div>
    <script>

    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                // Hide all other dropdowns
                var allDropdowns = document.getElementsByClassName("dropdown-container");
                for (var j = 0; j < allDropdowns.length; j++) {
                    allDropdowns[j].style.display = "none";
                }
                dropdownContent.style.display = "block";
            }
        });
    }

    // Get the current filename from the URL
    var filename = window.location.pathname.split('/').pop();

    // Find the link with the matching href attribute
    var link = document.querySelector('.sidebar a[href="' + filename + '"]');
    if (link) {
        link.classList.add('active');
        // If the link is inside a dropdown, make sure the dropdown is visible
        var parentDropdown = link.closest(".dropdown-container");
        if (parentDropdown) {
            parentDropdown.style.display = "block";
            parentDropdown.previousElementSibling.classList.add('active');
        }
    }
</script>





</script>

</body>
</html>

