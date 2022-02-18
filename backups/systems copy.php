<html>
<head>
	<title>System Info</title>
	<link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image/png" href="/favicon.png"/>
</head>
<body>

<?php
include 'err.php';
include 'creds.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

if(isset($_GET['id']))
{
	$sql = "SELECT ID, Manufacturer, Model, Form_Factor, OS_and_Drivers FROM systems WHERE ID = " . $_GET['id'];


$result = $conn->query($sql);

if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) {
		$drv = json_decode($row["OS_and_Drivers"], true);
		echo "<h1>" . $row["Manufacturer"] . " " . $row["Model"] . "</h1><a href=\"/\">Return to dev menu</a><hr>";
		foreach ($drv["data"] as $item) {
			echo "<b>".$item["os"].":</b><br>";
			if (count($item["drivers"]) > 0) {
				$drstr = "";
				foreach ($item["drivers"] as $driver) {
					$driversql = "SELECT Manufacturer, Device_Name, File_URL FROM drivers WHERE id = " . $driver;
					$driverresult = $conn->query($driversql);
					while($drvrow = $driverresult->fetch_assoc()) {
						$fileURL = "./files/" . $drvrow["File_URL"];
						echo $drvrow["Manufacturer"] . " " . $drvrow["Device_Name"] . " - <a href=\"/drivers.php?id=" . $driver . "\">More Details</a> | <a href=\"" . $fileURL . "\">Download</a><br>";
					}
				}
			} else {
				echo "No drivers available for " . $item["os"];
			}
			echo "<br>";
		}
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