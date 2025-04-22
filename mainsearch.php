<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="nav.css">
    <title>Document</title>
    <style>
        .suggestions-dropdown {
            border: 1px solid #ccc;
            max-height: 400px;
            overflow-y: auto;
            position: absolute;
            background-color: white;
            z-index: 1000;
            width: 300px;
        }
        .suggestion-item {
            padding: 8px;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background-color: #f0f0f0;
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
                <form id="searchForm" onsubmit="return handleSearch(event)">
                    <input type="text" id="search" placeholder="Search..." onkeyup="fetchResults()">
                    <div id="suggestions" class="suggestions-dropdown"></div>
                </form>
            </li>
            <li><a href="#" class="nav-link">Navbar</a></li>
            <li><a href="#" class="nav-link">Dashboard</a></li>
            <li><a href="#" class="nav-link">Products</a></li>
        </ul>

        <!-- Welcome message and logout button -->
        <div class="hello">
            Hello, <?= htmlspecialchars($_SESSION['name'], ENT_QUOTES) ?>!
            <button><a href="logout.php">Logout</a></button>
        </div>
    </header>

    <!-- Your page content goes here -->

    <!-- JavaScript for toggling the menu -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleMenu() {
            var sidebar = document.querySelector(".sidebar");
            sidebar.classList.toggle("show");
        }

        function fetchResults() {
            let query = $('#search').val();
            if (query.length > 2) {
                $.ajax({
                    url: 'search_suggestions.php',
                    type: 'GET',
                    data: { keywords: query },
                    success: function(data) {
                        let suggestions = JSON.parse(data);
                        $('#suggestions').empty();
                        suggestions.forEach(function(suggestion) {
                            $('#suggestions').append('<div class="suggestion-item" data-type="' + suggestion.type + '" data-id="' + suggestion.id + '">' +
                                'Type: ' + suggestion.type + ', ID: ' + suggestion.id + ', ' +
                                'Field: ' + suggestion.search_field + ', ' +
                                'Customer: ' + suggestion.customer + ', ' +
                                'Product: ' + suggestion.product +
                                '</div>');
                        });
                    }
                });
            } else {
                $('#suggestions').empty();
            }
        }

        $(document).on('click', '.suggestion-item', function() {
            let type = $(this).data('type');
            let id = $(this).data('id');
            let link = '';
            switch (type) {
                case 'orders':
                    link = 'order.php?id=' + encodeURIComponent(id);
                    break;
                case 'sales':
                    link = 'sales2.php?id=' + encodeURIComponent(id);
                    break;
                case 'purchase':
                    link = 'purchases.php?id=' + encodeURIComponent(id);
                    break;
                default:
                    link = '#';
                    break;
            }
            window.location.href = link;
        });

        function handleSearch(event) {
            event.preventDefault();
            let query = $('#search').val();
            if (query.length > 2) {
                let link = 'search_results.php?keywords=' + encodeURIComponent(query);
                window.location.href = link;
            }
        }

        // Close the suggestions dropdown when clicking outside
        $(document).click(function(event) {
            if (!$(event.target).closest('#search').length && !$(event.target).closest('#suggestions').length) {
                if ($('#suggestions').is(":visible")) {
                    $('#suggestions').empty();
                }
            }
        });
    </script>
</body>
</html>
