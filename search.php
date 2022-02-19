<head>
  <title>Query Results</title>
	<link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" type="image/png" href="/favicon.png"/>
</head>
<h1>Search</h1>
<hr>
<form action="search.php" method="post">
    <input type="text" name="query">&nbsp;<input type="submit">
</form>

<?php
$query = '%'.$_POST["query"].'%';
if($query != "%%"){
  $cleanquery = str_replace("%","",$query);
  echo "<b>Query: </b>" . $cleanquery;
}
echo $_POST["browsers"];
?>
<br><a href="/">Home</a> | <a href="javascript:history.back()">Back</a>
<hr>
<?php
include 'creds.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
if ($query == "%%"){
  return;
}else{
  $stmt = $conn->prepare("SELECT ID, Manufacturer, Model, Form_Factor, OS_and_Drivers FROM systems WHERE Model LIKE ?");
  $stmt->bind_param(s,$query);
  $stmt->execute();
  $result = $stmt->get_result();

}

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
