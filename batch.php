<?php
declare(strict_types=1);
?>
	<html>
	<head>
       <?php
       $title = 'Batch Download';
       require('head.php'); ?>
	</head>
	<body>
	<h1>Batch download</h1>
	<hr>
<?php
require('creds.php');

// @TODO: This is all fucked up. Really.
//        Ideally, make a JSON already (don't include []!), verify it (throw on error - like I added)
//        and if it's valid, MAKE SURE that the values are all integers, and then you can start building the
//        SQL query.
//        Replace all double quotes with single quotes.
if (isset($_GET['files'])) {
   $files    = '[' . $_GET['files'] . ']';
   $filesarr = json_decode($files, true, 512, JSON_THROW_ON_ERROR);
   $sql      = "SELECT ID, File_Name, File_Path, Version, Date, Mirrors FROM files WHERE ";

   $i = 0;
   foreach ($filesarr as $file) {
      if ($i != 0) $sql = $sql . " OR ";
      $sql = $sql . "id = " . $file;
      $i   = $i + 1;
   }

   // Create connection
   // @TODO: See `stats.php` line 24
   $conn = new mysqli($servername, $username, $password, $dbname);
   // Check connection
   if ($conn->connect_error) {
      die('Connection failed: ' . $conn->connect_error);
   }

   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
         $filepath = "/mnt/data1" . $row["File_Path"] . $row["File_Name"];
         echo "<b>Path:</b> " . $filepath . "<br>";
      }
   } else {
      echo "No data";
   }
   $conn->close();
} else {
   echo "No files specified";
}
?>