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
<?php
echo '<hr>';
require('nav.html');
echo '<hr>';
require('creds.php');

// Create connection
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
         // Make table data array to avoid duplicate headers later
         $systemsTableDataQuery = $conn->query('SELECT id, manufacturer, model FROM systems');
         $systemsTableData = $systemsTableDataQuery->fetch_all(MYSQLI_ASSOC);
         foreach($systemsTableData as $key => $system) {
            $systemsTableData[$key]['data'] = [];
         }
         echo '<h2 class="title"><i>' . $row['manufacturer'] . ' ' . $row['device_name'] . '</i></h2><hr>';
         echo "<b>Category:</b> " . $row['category'] . '<br><br>';
         echo '<table border="1">';
         $files = json_decode($row['files']);
         foreach($files as $file) {
            $systemStmt = $conn->prepare('SELECT id, data FROM systems WHERE JSON_CONTAINS(data->"$.data[*].drivers", ?)');
            $systemStmt->bind_param('s', $file);
            $systemStmt->execute();
            $systemResult = $systemStmt->get_result();
            if ($systemResult->num_rows > 0) {
               foreach($systemResult->fetch_all(MYSQLI_ASSOC) as $system) {
                  $data = json_decode($system['data'], true);
                  foreach($data['data'] as $dataElement) {
                     if (in_array($file, $dataElement['drivers'])) {
                        $systemKey = 0;
                        // Match system in table data array and add file data to it
                        foreach($systemsTableData as $key => $singleSystem) {
                           if ($singleSystem['id'] == $system['id']) {
                              $systemKey = $key;
                           }
                        }
                        array_push($systemsTableData[$systemKey]['data'], array(
                           'os' => $dataElement['os'],
                           'driver' => $file
                        ));
                     }
                  }
               }
            }
         }
         foreach($systemsTableData as $system) {
            if (count($system['data']) > 0) {
               echo '<tr><th colspan="4"><b>' . $system['manufacturer'] . ' ' . $system['model'] . ':</b></th></tr>';
               foreach($system['data'] as $data) {
               echo '<tr><td class="drvdetails">' . $data['os'] . '</td>
                        <td class="drvdetails"><a href="/drivers.php?id=' . $data['driver'] . '">More Details</a></td><td class="drvdetails">'
                        . '<a href="/download.php?id=' . $data['driver'] .'">Download</a></td></tr>';
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
