<html>
  <head>
</head>
<body>
<h1>Search Results</h1>
<?php
$query = $_POST["query"];
echo "<b>Query: </b>" . $query;
?>
<br><a href="/">Return to dev menu</a> | <a href="/search.html">Return to search</a>
<hr>
<?php
include 'creds.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT ID, Manufacturer, Model, Form_Factor, OS_and_Drivers FROM systems WHERE Model LIKE '%". $query ."%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    //echo "<h2> " . $row["Manufacturer"] . " " . $row["Model"] . "</h2>";
    //echo "<b>Form Factor:</b> " . $row["Form_Factor"] . "<br>";
    echo "<h2><a href=\"/systems.php?id=" . $row["ID"] . "\">". $row["Manufacturer"] . " " . $row["Model"] . "</a></h2>";
    echo "<hr>";
  }
} else {
  echo "No Results for " . $query;
}
$conn->close();
?>
</body>
</html>