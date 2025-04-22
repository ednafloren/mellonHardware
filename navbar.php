<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="reports.css">

    <title>Document</title>
    <style>
        /* Additional styles for search results */
        #search-results-container {
            position: absolute;
            width: 100%;
            max-width: 300px; /* Adjust max-width as needed */
            background-color: white;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            display: none; /* Hide initially */
        }

        #search-results-container ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #search-results-container li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        #search-results-container a {
            color: black;
            text-decoration: none;
        }

        #search-results-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <!-- Hamburger menu icon -->
        <div class="hamburger-menu" onclick="toggleMenu()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <div class="nameAndLogo"><h2><i>Mellon Hardware</i></h2></div>
        <!-- Your navigation links -->
        <ul class="navbar-nav">
       <li>
           
        <?php include 'search_form.php'; ?>
  
            </li>
        </ul>

        <!-- Welcome message and logout button -->
        <div class="hello">Hello, <?= htmlspecialchars($_SESSION['name'], ENT_QUOTES) ?>!
            <button><a href="logout.php">Logout</a></button>
        </div>

    </header>

    <!-- Container for search results -->
    <div id="search-results-container"></div>

    <!-- Your page content goes here -->

    <script>
        function fetchResults() {
            const query = document.getElementById('search').value;
            if (query.length > 0) {
                fetch('newsearch.php?keywords=' + encodeURIComponent(query))
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('search-results-container').innerHTML = data;
                        document.getElementById('search-results-container').style.display = 'block';
                    });
            } else {
                document.getElementById('search-results-container').innerHTML = '';
                document.getElementById('search-results-container').style.display = 'none';
            }
        }

        function toggleMenu() {
            var sidebar = document.querySelector(".sidebar");
            sidebar.classList.toggle("show");
        }
    </script>
</body>
</html>
