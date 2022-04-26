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
<?php include 'nav.html'; ?>
<hr>
<?php
include 'creds.php';

// Create connection
// @TODO: See `stats.php` line 24
$conn = new mysqli(CONF["servername"], CONF["username"], CONF["password"], CONF["dbname"]);

// Check connection
if ($conn->connect_error) {
   die('Connection failed: ' . $conn->connect_error);
}

if (isset($_GET['id'])) {
   $stmt = $conn->prepare('SELECT ID, Manufacturer, Model, OS_and_Drivers FROM systems WHERE ID = ?');
   $stmt->bind_param('i', $_GET['id']);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
      // output data of each row
      foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
         $drv = json_decode($row['OS_and_Drivers'], true, 512, JSON_THROW_ON_ERROR);
         echo '<h2 class="title"><i>' . $row['Manufacturer'] . ' ' . $row['Model'] . '</i></h2><hr>';
         echo '<a href="/link.php?type=system&id=' . $_GET['id'] . '">Linkback</a><br><br>';
         echo '<table border="1">';
         foreach ($drv['data'] as $item) {
            echo '<tr><th colspan="4"><b>' . $item['os'] . ':</b></th></tr>';
            if (count($item['drivers']) > 0) {
               $drstr = '';
               foreach ($item['drivers'] as $driver) {
                  $driverstmt = $conn->prepare('SELECT Manufacturer, Device_Name, File_URL FROM drivers WHERE id = ?');
                  $driverstmt->bind_param('i', $_GET['id']);
                  $driverstmt->execute();
                  $driverresult = $stmt->get_result();
                  foreach ($driverresult->fetch_all(MYSQLI_ASSOC) as $drvrow) {
                     $fileURL = './files/' . $drvrow['File_URL'];
                     echo '<tr><td class="drvdetails">' . $drvrow['Manufacturer'] . '</td><td class="drvdetails">' . $drvrow['Device_Name']
                        . '</td><td class="drvdetails"><a href="/drivers.php?id=' . $driver . '">More Details</a></td><td class="drvdetails">'
                        . '<a href="' . $fileURL . '">Download</a></td></tr>';
                  }
               }
            }
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