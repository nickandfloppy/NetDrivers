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
   $stmt = $conn->prepare('SELECT id, manufacturer, model, data FROM systems WHERE id = ?');
   $stmt->bind_param('i', $_GET['id']);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
      // output data of each row
      foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
         $drv = json_decode($row['data'], true, 512, JSON_THROW_ON_ERROR);
         echo '<h2 class="title"><i>' . $row['manufacturer'] . ' ' . $row['model'] . '</i></h2><hr>';
         echo '<a href="/link.php?type=system&id=' . $_GET['id'] . '">Linkback</a><br><br>';
         echo '<table border="1">';
         foreach ($drv['data'] as $item) {
            echo '<tr><th colspan="4"><b>' . $item['os'] . ':</b></th></tr>';
            if (count($item['drivers']) > 0) {
               // Commented out as it doesn't get used anywhere
               //$drstr = '';
               foreach ($item['drivers'] as $driver) {
                  $deviceStmt = $conn->prepare('SELECT manufacturer, device_name FROM devices WHERE id = ?');
                  $deviceStmt->bind_param('i', $driver);
                  $deviceStmt->execute();
                  $deviceResult = $deviceStmt->get_result();
                  foreach ($deviceResult->fetch_all(MYSQLI_ASSOC) as $deviceRow) {
                     echo '<tr><td class="drvdetails">' . $deviceRow['manufacturer'] . '</td><td class="drvdetails">' . $deviceRow['device_name']
                        . '</td><td class="drvdetails"><a href="/drivers.php?id=' . $driver . '">More Details</a></td><td class="drvdetails">'
                        . '<a href="/download.php?id=' . $driver .'">Download</a></td></tr>';
                  }
               }
            }
            echo '<tr><td>TBD</td></tr>';
         }
         echo '</table><br>';
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
