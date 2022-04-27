<?php
declare(strict_types=1);
?>
<html>
<head>
   <?php $title = 'Select Mirror';
   require('head.php'); ?>
</head>
<body>

<?php
require('creds.php');

if (isset($_GET['id'])) {
   // Create connection
   // @TODO: See `stats.php` line 24
   $conn = new mysqli(CONF["servername"], CONF["username"], CONF["password"], CONF["dbname"]);
   $conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
   // Check connection
   if ($conn->connect_error) {
      die('Connection failed: ' . $conn->connect_error);
   }
   $stmt = $conn->prepare('SELECT id, file_name, file_path, version, date, mirrors FROM files WHERE id = ?');
   $stmt->bind_param('i', $_GET['id']);
   $stmt->execute();
   $result = $stmt->get_result();

   $mirrors = 1;
   // @TODO: Use a trigger, much like I mentioned in stats too.
   $mirr_res = $conn->query('SELECT id, name, region, address, base_url, https FROM mirrors');
   if ($mirr_res->num_rows === 0) {
      echo 'No mirrors available';
      $mirrors = 0;
   }

   // Get mirror info
   $sql = 'SELECT id, name, region, address, base_url, https FROM mirrors';

   if ($result->num_rows > 0 && $mirrors === 1) {
      //echo "<table border=\"1\"><td><b>Linkback: </b><a href=\"http://drivers.nickandfloppy.com/link.php?type=driver&id=" . $_GET['id'] . "\">http://drivers.nickandfloppy.com/link.php?type=driver&id=" . $_GET['id'] . "</td></table>";

      // output data of each row
      foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
         //$fileurl = $row['File_URL'] === null || $row['File_URL'] === '' ? 'N/A' : $row['File_URL'];

         echo '<h1>Downloading ' . $row['file_name'] . '</h1>';
         echo '<b>Version:</b> ' . $row['version'] . '<br>';
         echo '<b>Date:</b> ' . $row['date'] . '<br>';
         $mirrors = json_decode($row['mirrors'], true, 512, JSON_THROW_ON_ERROR);
         echo '<br><b>Select from the following mirrors...</b><br>';
         foreach ($mirr_res->fetch_all(MYSQLI_ASSOC) as $mirrorRW) {
            if (in_array($mirrorRW["ID"], $mirrors, true)) {
               $url = $mirrorRW['address'] . $mirrorRW['base_url'] . $row['file_path'] . $row['file_name'];
               echo '<a href="' . ($mirrorRW['https'] === true ? 'https' : 'http') . '://' . $url . '">Mirror '
                  . $mirrorRW['id'] . ' (' . $mirrorRW['region'] . ')</a><br>';
            }
         }
      }
   } else {
      if ($mirrors === 1) {
         echo 'Invalid ID provided';
      }
   }
   $conn->close();
} else {
   echo 'No ID provided';
}

?>
</body>
</html>
