<?php
declare(strict_types=1);
?>
<html>
<head>
   <?php
   $title = 'System Info';
   require('head.php'); ?>
</head>
<body>
<a href="/">
	<table>
		<tr>
			<td><img src="/favicon.png" width="50"></td>
			<td><h1 class="header">NetDrivers</h1><i>Archiving Drivers Since February 2022</i></td>
		</tr>
	</table>
</a>
<hr>
<?php require('nav.html'); ?>
<hr>
<?php
require('creds.php');

// Create connection
// @TODO: See `stats.php` line 24
$conn = new mysqli(CONF["servername"], CONF["username"], CONF["password"], CONF["dbname"]);

// Check connection
if ($conn->connect_error) {
   die('Connection failed: ' . $conn->connect_error);
}

if (isset($_GET['id'])) {
   $stmt = $conn->prepare('SELECT id, manufacturer, device_name, device_model, category, files FROM devices WHERE id = ?');
   $stmt->bind_param('i', $_GET['id']);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
      // output data of each row
      foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
         echo '<h2 class="title"><i>' . $row['manufacturer'] . ' ' . $row['device_name'] . '</i></h2><hr>';
         //echo '<table border="1">';
         echo "<b>Category:</b> " . $row['category'];
         //echo '</table><br>';
      }
   } else {
      echo 'Invalid System ID';
   }
   $conn->close();
} else {
   echo '<b>Error:</b> No System ID Specified!';
}
?>
<hr>
</body>
</html>
