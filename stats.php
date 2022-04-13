<head>
    <title>NetDrivers Statistics</title>
    <link rel="shortcut icon" type="image/png" href="/favicon.png"/>
	<link rel="stylesheet" href="/res/style.css">
</head>
<h1><i>Website Statistics</i></h1>
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

$result = $conn->query("SELECT COUNT(*) FROM `files`");
$row = $result->fetch_row();
$filecount = $row[0];

$result = $conn->query("SELECT COUNT(*) FROM `devices`");
$row = $result->fetch_row();
$devicecount = $row[0];

$conn->close();

$drivercount = $drivercount_dev + $drivercount_sys;
?>

<table border="1">
<tr>
<th>Item</th>
<th>Count</th>
<tr>
<td>Files</td>
<td><?php echo $filecount ?></td>
</tr>
<tr>
<td>Systems</td>
<td><?php echo $systemcount ?></td>
</tr>
<tr>
<td>Devices</td>
<td><?php echo $devicecount ?></td>
</tr>
</table>

<?php
}
?>
