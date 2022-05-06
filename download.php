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
   $conn = new mysqli(CONF["servername"], CONF["username"], CONF["password"], CONF["dbname"]);
   // Convert database ints and floats to php ints and floats
   $conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
   // Check connection
   if ($conn->connect_error) {
      die('Connection failed: ' . $conn->connect_error);
   }
   $stmt = $conn->prepare('SELECT id, file_name, file_path, version, date, mirrors FROM files WHERE id = ?');
   $stmt->bind_param('i', $_GET['id']);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
      //echo "<table border=\"1\"><td><b>Linkback: </b><a href=\"http://drivers.nickandfloppy.com/link.php?type=driver&id=" . $_GET['id'] . "\">http://drivers.nickandfloppy.com/link.php?type=driver&id=" . $_GET['id'] . "</td></table>";
   
      // output data of each row
      foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
         $fileMirrors = json_decode($row['mirrors'], true, 512, JSON_THROW_ON_ERROR);
         // Commented out as it doesn't get used anywhere
         //$fileurl = $row['File_URL'] === null || $row['File_URL'] === '' ? 'N/A' : $row['File_URL'];

         if (count($fileMirrors) < 1) {
            echo 'No mirrors available';
            continue;
         }
         echo '<h1>Downloading ' . $row['file_name'] .'</h1>';
         //echo '<b>Version:</b> ' . $row['version'] . '<br>';
         //echo '<b>Date:</b> ' . $row['date'] . '<br>';
         
         echo '<b>Select from the following mirrors...</b><br>';
         foreach($fileMirrors as $fileMirror) {
            $mirrorStmt = $conn->prepare('SELECT id, name, region, address, base_url, https FROM mirrors WHERE id = ?');
            $mirrorStmt->bind_param('i', $fileMirror);
            $mirrorStmt->execute();
            $mirrorResult = $mirrorStmt->get_result();
            if ($mirrorResult->num_rows > 0) {
               foreach($mirrorResult->fetch_all(MYSQLI_ASSOC) as $mirrorRow) {
                  $url = $mirrorRow['address'] . $mirrorRow['base_url'] . $row['file_path'] . $row['file_name'];
                  echo '<a href="' . ($mirrorRow['https'] === true ? 'https' : 'http') . '://' . $url . '">Mirror '
                     . $mirrorRow['id'] . ' (' . $mirrorRow['region'] . ')</a><br>';
               }
            }
         }
      }
      echo '<br> ID: ' . urlencode($_GET['id']);
   } else {
      echo 'Invalid ID provided';
   }
   
   $conn->close();
} else {
   echo 'No ID provided';
}

?>
</body>
</html>
