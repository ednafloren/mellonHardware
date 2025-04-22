<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 5px;
}
</style>
</head>
<body>

<table>
  <tr>
    <td>Filter Name</td>

  </tr>
  <?php
  foreach (filter_list() as $filter) {
    echo '<tr><td>' . $filter . '</td></tr>';
  }
?>
  
</table>
<?php   
//   key and value looping
$members = array("Peter"=>"35", "Ben"=>"37", "Joe"=>"43");

foreach ($members as $x => $y) {
  echo "$x : $y <br>";
}
?>
</body>
</html>


/* Default styles */
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

.navbar {
    height: 50px;
    z-index: 1;
    background-color: black;
    color: white;
    padding: 1px 10px;
    position: fixed;
    right: 0;
    left: 0;
    top: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar-nav {
    list-style-type: none;
    flex-grow: 1;
    display: flex;
    justify-content: flex-end;
    margin: 0;
}

.navbar-nav li {
    margin-right: 20px;
}

.nav-link {
    color: white;
    text-decoration: none;
}

.nameAndLogo {
    flex-grow: 1;
    margin-left: 0px;
    white-space: nowrap; /* Prevent text from wrapping */
}

.hello {
    display: flex;
    align-items: center; /* Align items vertically */
    margin-right: 20px; /* Adjust margin */
    color: white;
    white-space: nowrap; /* Prevent text from wrapping */
}

.hello button {
    margin-left: 10px; /* Adjust margin between the message and the button */
    border: none;
    padding: 8px 10px;
    color: white;
    background-color: rgb(71, 71, 188);
}

.hello a {
    color: white;
    text-decoration: none;
}

/* Hamburger menu */
.hamburger-menu {
    display: none; /* Initially hide the hamburger menu icon */
    cursor: pointer;
    padding: 10px;
}

.bar {
    width: 25px;
    height: 3px;
    background-color: white;
    margin: 5px 0;
}

/* Media query for smaller screens */
@media screen and (max-width: 768px) {
    .navbar {
        height: 50px;
    }

    .navbar-nav {
        display: none; /* Hide navigation links by default */
        flex-direction: column;
        background-color: black;
        padding: 10px;
        position: absolute; /* Position the navigation links absolutely */
        top: 50px; /* Adjust the top position to accommodate the navbar height */
        right: 0;
        left: 0;
    }

    .navbar.show .navbar-nav {
        display: flex; /* Show navigation links when .show class is applied to .navbar */
    }

    .hamburger-menu {
        display: block;
    }

    .nameAndLogo {
        margin-left: 10px;
    }

    .hello {
        display: flex;
        align-items: center; /* Align items vertically */
        margin-right: 20px; /* Adjust margin */
        color: white;
        white-space: nowrap; /* Prevent text from wrapping */
    }
}

/* Styling for the search form */
.search-form {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 800px;
    padding: 5px 30px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative; /* Set relative position to parent */
}

.search-input {
    flex-grow: 6; /* Allow the input to take up remaining space */
    padding: 6px 20px;
    padding-left: 40px; /* Space for the icon */
    width: 300px;
    font-size: 12px;
    border: 1px solid #ccc;
    border-radius: 20px;
    margin-right: 10px; /* Space between input and button */
    position: relative; /* Set relative position */
}

.search-icon {
    position: absolute;
    left: 350px; /* Adjust left position */
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    color: #aa2121;
    z-index: 2000;
}

#search-button {
    display: none; /* Hide the button if you don't need it */
}

/* Styling for the search results container */
#search-results-container {
    margin-top: 100px;
    max-height: 200px;
    overflow-y: auto;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 10px;
    display: none; /* Hide initially */
}

/* Show the container when there are results */
#search-results-container.active {
    display: block;
}
