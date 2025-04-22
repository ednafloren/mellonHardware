
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="reports.css">

    
    <script>
       function toggleMenu() {
    var navLinks = document.querySelector(".navbar-nav");
    navLinks.classList.toggle("show");
}

    </script>
  
</head>
<body>
    <header class="navbar">
        <!-- Hamburger menu icon -->
        <div class="hamburger-menu2" onclick="toggleSidebar()">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>
       
        <div class="nameAndLogo"><h2><i>Mellon Hardware</i></h2></div>


        <!-- Navigation links -->
        <ul class="navbar-nav">
            <li><a href="#" class="nav-link">Navbar</a></li>
            <li><a href="#" class="nav-link">Dashboard</a></li>
            <li><a href="product.php" class="nav-link">Products</a></li>
        </ul>
        

        <!-- Welcome message and logout button -->
        <div class="hello">Hello,<?= htmlspecialchars($_SESSION['name'], ENT_QUOTES)?>!
            <button><a href="logout.php">Logout</a></button>
        </div>
        <div class="hamburger-menu" onclick="toggleMenu()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </header>

    <!-- Your page content goes here -->
    <div class="container">
        <!-- Add your content here -->
    </div>

</body>
</html>


