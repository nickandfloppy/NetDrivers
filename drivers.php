<html>
<head>
  <title>Driver Listing Test</title>
  <link rel="shortcut icon" type="image/png" href="/favicon.png"/>
  <link rel="stylesheet" href="/res/style.css">
</head>
<body>

<h1>Driver Listing Test</h1>
<a href="/">Return to dev menu</a>
<hr>
<?php
include 'creds.php';

echo "<a href=\"/link.php?type=driver&id=" . $_GET['id'] . "\">Linkback</a><br><br>";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if(isset($_GET['id']))
{
  $sql = "SELECT ID, Manufacturer, Device_Name, Device_Model, Category, OS, File_URL, Version, Driver_Date FROM drivers WHERE id = " . $_GET['id'];
} else {
  $sql = "SELECT ID, Manufacturer, Device_Name, Device_Model, Category, OS, File_URL, Version, Driver_Date FROM drivers";
}
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    if ($row["File_URL"] == "") $fileurl = "N/A";
    else $fileurl = "./files/" . $row["File_URL"];
    echo "<b>Manufacturer:</b> " . $row["Manufacturer"] . "<br>";
    echo "<b>Device Name:</b> " . $row["Device_Name"] . "<br>";
    echo "<b>Device Model:</b> " . $row["Device_Model"] . "<br>";
    echo "<b>Version:</b> " . $row["Version"] . "<br>";
    echo "<b>Date:</b> " . $row["Driver_Date"] . "<br>";
    echo "<b>Category:</b> " . $row["Category"] . "<br>";
    echo "<b>OS:</b> " . $row["OS"] . "<br>";
    echo "<b>File URL:</b> " . $fileurl . "<br>";
    echo "<b>File Link:</b> <a href=\"" . $fileurl . "\">Link</a><br>";
    echo "<hr>";
  }
} else {
  echo "0 results";
}
$conn->close();
?>
</body>
</html>