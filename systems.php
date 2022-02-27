<html>
<head>
	<title>System Info</title>
	<link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image/png" href="/favicon.png">
	<link rel="stylesheet" href="/res/style.css">
</head>
<body>
<a href="/">Home</a> | <a href="javascript:history.back()">Back to search</a><br><br>

<?php
include 'creds.php';

echo "<a href=\"/link.php?type=driver&id=" . $_GET['id'] . "\">Linkback</a><br><br>";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

if(isset($_GET['id']))
{
	$sql = "SELECT ID, Manufacturer, Model, OS_and_Drivers FROM systems WHERE ID = " . $_GET['id'];


$result = $conn->query($sql);

if ($result->num_rows > 0) {
	echo "<table border>";
	// output data of each row
	while($row = $result->fetch_assoc()) {
		$drv = json_decode($row["OS_and_Drivers"], true);
		echo "<h1><i>" . $row["Manufacturer"] . " " . $row["Model"] . "</i></h1><hr>";
		foreach ($drv["data"] as $item) {
			echo "<tr><th colspan=\"4\"><b>" . $item["os"] . ":</b></th></tr>";
			if (count($item["drivers"]) > 0) {
				$drstr = "";
				foreach ($item["drivers"] as $driver) {
					$driversql = "SELECT Manufacturer, Device_Name, File_URL FROM drivers WHERE id = " . $driver;
					$driverresult = $conn->query($driversql);
					while($drvrow = $driverresult->fetch_assoc()) {
						$fileURL = "./files/" . $drvrow["File_URL"];
						echo "<tr><td class=\"drvdetails\">" . $drvrow["Manufacturer"] . "</td><td class=\"drvdetails\">" . $drvrow["Device_Name"] . "</td><td class=\"drvdetails\"><a href=\"/drivers.php?id=" . $driver . "\">More Details</a></td><td class=\"drvdetails\"><a href=\"" . $fileURL . "\">Download</a></td></tr>";
					}
				}
			}
		}
		echo "</table>";
	}
} else {
	echo "Invalid System ID";
}
$conn->close();
} else {
	echo "<b>Error:</b> No System ID Specified!";
}
?>
<hr>
</body>
</html>