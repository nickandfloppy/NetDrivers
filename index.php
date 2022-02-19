<head>
    <title>NetDrivers Index</title>
    <link rel="shortcut icon" type="image/png" href="/favicon.png"/>
</head>
<h1>Welcome to <i>NetDrivers</i></h1>
<hr>
<ul>
    <li><a href="search.html">Search the driver database</a></li>
    <li><a href="dev.html">Dev Menu</a></li>
</ul>

<?php
include 'creds.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT COUNT(*) FROM `systems`");
$row = $result->fetch_row();
$systemcount = $row[0];

$result = $conn->query("SELECT COUNT(*) FROM `drivers`");
$row = $result->fetch_row();
$drivercount = $row[0];

echo "<hr>";
echo "<i>Serving " . $drivercount . " drivers for " . $systemcount . " systems since 2022!</i><br>";
echo "<i>Copyright <a href=\"https://nickandfloppy.com/\">nick and floppy " . date("Y");
?>