<head>
    <title>NetDrivers Index</title>
    <link rel="shortcut icon" type="image/png" href="/favicon.png"/>
	<link rel="stylesheet" href="/res/style.css">
</head>
<h1>Welcome to <i>NetDrivers</i></h1>
<hr>
<ul>
    <li><a href="search.php">Search the driver database</a></li>
</ul>
<hr>
<?php
include 'creds.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  $diemsg = "<pre><i>Unable to retrieve database statistics!</i></pre><i>Copyright <a href=\"https://nickandfloppy.com/\">nick and floppy " . date("Y");
  die($diemsg);
} else {

$result = $conn->query("SELECT COUNT(*) FROM `systems`");
$row = $result->fetch_row();
$systemcount = $row[0];

$result = $conn->query("SELECT COUNT(*) FROM `drivers`");
$row = $result->fetch_row();
$drivercount = $row[0];

echo "<i>Serving " . $drivercount . " drivers for " . $systemcount . " systems since 2022!</i>";
include 'footer.php';
}
?>