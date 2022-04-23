<html>
<head>
	<?php $title = "System Info"; include 'head.php'; ?>
</head>
<body>
<a href="/"><table><tr>
	<td><img src="/favicon.png" width="50"></td>
	<td><h1 style="margin: 0">NetDrivers</h1><i>Archiving Drivers Since April 2022</i></td>
</tr></table></a>
<hr>
<?php include 'nav.html'; ?>
<hr>
<?php
include 'creds.php';


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
		echo "<h2><i>" . $row["Manufacturer"] . " " . $row["Model"] . "</i></h2><hr>";
		echo "<a href=\"/link.php?type=system&id=" . $_GET['id'] . "\">Linkback</a><br><br>";
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