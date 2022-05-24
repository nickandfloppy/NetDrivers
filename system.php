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
         //echo '<a href="/link.php?type=system&id=' . urlencode($_GET['id']) . '">Linkback</a><br><br>';
         foreach ($drv['data'] as $item) {
            echo '<table border="1">';
            echo '<tr><th align="left" colspan="23"><b>' . $item['os'] . ':</b></th></tr>';
            if (count($item['files']) > 0) {
               foreach ($item['files'] as $file) {
                  $sql = "SELECT file_name FROM files WHERE id=" . $file['id'];
                  $filedata = $conn->query($sql)->fetch_assoc();
                  echo '<tr><td>' . $file['name'] . '</td><td>' . $filedata['file_name'] . '</td><td><a href="/download.php?id=' . $file['id'] . '">Details</a></td></tr>';
               }
            }
            echo '</table><br>';
         }
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
