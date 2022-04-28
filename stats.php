<?php
declare(strict_types=1);
?>

<head>
   <?php $title = 'Statistics';
   require('head.php'); ?>
</head>
<a href="/">
	<table>
		<tr>
			<td><img src="/favicon.png" width="50"></td>
			<td><h1 style="margin: 0">NetDrivers</h1><i>Archiving Drivers Since February 2022</i></td>
		</tr>
	</table>
</a>
<hr>
<?php
require('nav.html');
echo '<hr/>'; // No need to close the php tag.
require('creds.php');

// Create connection
$conn = new mysqli(CONF["servername"], CONF["username"], CONF["password"], CONF["dbname"]);
// Check connection
if ($conn->connect_error) {
   $diemsg = '<pre><i>Unable to retrieve database statistics!</i></pre><i>Copyright <a href="https://nickandfloppy.com/">nick and floppy ' . date('Y');
   die($diemsg);
}

// @TODO: This ideally should be moved to a table 'stats' that's updated on inserts
//        with a simple trigger. More efficient. COUNT(*) is terrible inefficient, and
//        I've at least aliviated the issue by replacing * with 0.
$result      = $conn->query('SELECT COUNT(0) FROM `systems`');
$row         = $result->fetch_row();
$systemcount = $row[0];

$result    = $conn->query('SELECT COUNT(0) FROM `files`');
$row       = $result->fetch_row();
$filecount = $row[0];

$result      = $conn->query('SELECT COUNT(0) FROM `devices`');
$row         = $result->fetch_row();
$devicecount = $row[0];

$conn->close();
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

