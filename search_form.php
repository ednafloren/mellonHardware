<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <link rel="stylesheet" href="nav.css">

</head>
<body>
<form id="search-form" class="search-form" action="search_results.php" method="GET">
    <input type="text" id="search" name="keywords" class="search-input" placeholder="Search products and categories..." onkeyup="fetchResults()">
  

    <button type="submit" id="search-button">  <i class="fas fa-search search-icon" ></i></button>
    <div id="search-results-container"></div>
</form>

<script>
    function fetchResults() {
        const query = document.getElementById('search').value;
        if (query.length > 0) {
            fetch('newsearch.php?keywords=' + encodeURIComponent(query))
                .then(response => response.text())
                .then(data => {
                    document.getElementById('search-results-container').innerHTML = data;
                });
        } else {
            document.getElementById('search-results-container').innerHTML = '';
        }
    }
</script>
</body>
</html>