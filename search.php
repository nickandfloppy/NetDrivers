<?php
declare(strict_types=1);
?>

<head>
   <?php
   if (isset($_POST['query'])) {
      $title = 'Search for "' . $_POST['query'] . '"';
   } else {
      $title = 'Search';
   }
   require('head.php');
   ?>
</head>
<a href=".">
	<table>
		<tr>
			<td><img src="favicon.png" width="50"></td>
			<td><h1 style="margin: 0">NetDrivers</h1><i>Archiving Drivers Since February 2022</i></td>
		</tr>
	</table>
</a>
<hr>
<?php require('nav.html'); ?>
<hr>
<form action="search.php" method="post">
	<input type="text" name="query">&nbsp;<input type="submit"><br>
	<input type="radio" name="scope" checked="true"
       <?php
       // @TODO: This should be moved to a function. Furthermore, $scope is not defined
       //        therefore I'm commenting this line out for the time being.
       // - if (isset($scope) && $scope === 'systems') echo 'checked';?>
	       value="systems">Systems<input type="radio" name="scope"
      <?php // - if (isset($scope) && $scope=="devices") echo "checked";?>
	                                     value="devices">Devices<input type="radio" name="scope"
      <?php // - if (isset($scope) && $scope=="drivers") echo "checked";?>
	                                                                   value="drivers">Filename
</form>
<?php
if (!isset($_POST['scope'])) {
   return;
}

/**
 * Cleans a string
 *
 * @param string $data The string to be cleaned
 *
 * @return string Clean string
 */
// @TODO: The name of this function doesn't make any sense?
//        From what I've understood, it just cleans it
//        Refactoring to cleanInput() would make much more sense
function test_input(string $data): string {
   $data = trim($data);
   $data = stripslashes($data);

   return htmlspecialchars($data);
}

function listName(string $list, array $row): string {
   if ($list === 'systems' || $list === 'devices') {
      if ($list === 'systems') {
         $output = $row['Manufacturer'] . ' ' . $row['Model'];
      } else {
         $output = $row['Manufacturer'] . ' ' . $row['Device_Name'];
      }

      return '<h2><a href="' . $list . '.php?id=' . $row['ID'] . '">'
         . $output
         . '</a></h2>';
   } else if ($list === 'files') {
      $date = new DateTime($row['Date']);

      return '<p><b>Filename:</b> ' . $row['File_Name'] . '<br><b>Version:</b> ' . $row['Version'] . '<br><b>Date:</b> ' . $date->format('d M Y') .
         '<br><a href="download.php?id=' . $row['ID'] . '"><button type="button">Download</button></a></p>';
   }

   return '';
}

$queryScope = test_input($_POST['scope']);

$query      = null;
$cleanquery = '';
if (isset($_POST['query'])) {
   $query      = '%' . $_POST['query'] . '%';
   $cleanquery = str_replace('%', '', $query);
}

require('creds.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
   die('Connection failed: ' . $conn->connect_error);
}

if ($query === null) {
   $conn->close();

   return;
}

$result = false;
$list   = '';
// @TODO: Column names should be snake_case
switch ($queryScope) {
   case 'systems':
   {
      $stmt = $conn->prepare('SELECT ID, Manufacturer, Model FROM systems WHERE Model LIKE ?');
      $stmt->bind_param('s', $query);
      $stmt->execute();
      $result = $stmt->get_result();
      break;
   }
   case 'devices':
   {
      $stmt = $conn->prepare('SELECT ID, Manufacturer, Device_Name FROM devices WHERE Device_Name LIKE ? OR Manufacturer LIKE ?');
      $stmt->bind_param('ss', $query, $query);
      $stmt->execute();
      $result = $stmt->get_result();
      break;
   }
   case 'files':
   {
      $stmt = $conn->prepare("SELECT ID, File_Name, File_Path, Version FROM files WHERE File_Name LIKE ?");
      $stmt->bind_param('s', $query);
      $stmt->execute();
      $result = $stmt->get_result();
   }
}

if ($result !== false) {

   echo $result->num_rows . ' results for "' . $cleanquery . '" in ' . $queryScope . '<hr>';

   if ($result->num_rows > 0) {
      // output data of each row
      foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
         echo listName($queryScope, $row);
         echo '<hr>';
      }
   } else {
      echo 'No Results for ' . $cleanquery;
   }
}

$conn->close();
?>
