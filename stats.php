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
echo '<hr/>';
require('creds.php');

// Create connection
$conn = new mysqli(CONF["servername"], CONF["username"], CONF["password"], CONF["dbname"]);
// Check connection
if ($conn->connect_error) {
   $diemsg = '<pre><i>Unable to retrieve database statistics!</i></pre><i>Copyright <a href="https://nickandfloppy.com/">nick and floppy ' . date('Y');
   die($diemsg);
}

$result      = $conn->query('SELECT name, value FROM `stats`');

if ($result !== false) {
	echo '<table border="1"><tr><th>Item</th><th>Count</th><tr>';
	while ($row = $result->fetch_assoc()) {
		echo '<tr><td>' . $row['name'] . '</td><td>' . $row['value'] . '</td></tr>';
	}
	echo '</table>';
}

$conn->close();
?>
