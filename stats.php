<head>
		<?php $title = "Statistics"; include 'head.php'; ?>
</head>
<a href="/"><table><tr>
	<td><img src="/favicon.png" width="50"></td>
	<td><h1 style="margin: 0">NetDrivers</h1><i>Archiving Drivers Since February 2022</i></td>
</tr></table></a>
<hr>
<?php include 'nav.html'; ?>
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
