<html>
<head>
	<title>Downloading file</title>
	<link rel="shortcut icon" type="image/png" href="/favicon.png"/>
	<link rel="stylesheet" href="/res/style.css">
</head>
<body>

<?php
include 'creds.php';

if (isset($_GET['id'])) {
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT ID, File_Name, File_Path, Version, Date, Mirrors FROM files WHERE ID = " . $_GET['id'];
	$result = $conn->query($sql);

	$mirrors = 1;
	$mirr_res = $conn->query("SELECT ID, Name, Region, Address, BaseURL, HTTPS FROM mirrors");
	if ($mirr_res->num_rows == 0) {
		echo "No mirrors available";
		$mirrors = 0;
	}

	// Get mirror info
	$sql = "SELECT ID, Name, Region, Address, BaseURL, HTTPS FROM mirrors";

	if ($result->num_rows > 0 && $mirrors == 1) {
		//echo "<table border=\"1\"><td><b>Linkback: </b><a href=\"http://drivers.nickandfloppy.com/link.php?type=driver&id=" . $_GET['id'] . "\">http://drivers.nickandfloppy.com/link.php?type=driver&id=" . $_GET['id'] . "</td></table>";

		// output data of each row
		while($row = $result->fetch_assoc()) {
			if ($row["File_URL"] == "") $fileurl = "N/A";
			else $fileurl = $row["File_URL"];
			echo "<h1>Downloading " . $row["File_Name"] . "</h1>";
			echo "<b>Version:</b> " . $row["Version"] . "<br>";
			echo "<b>Date:</b> " . $row["Date"] . "<br>";
			$mirrors = json_decode($row["Mirrors"]);
			echo "<br><b>Select from the following mirrors...</b><br>";
			while($mirrorRW = $mirr_res->fetch_assoc()) {
				if (in_array($mirrorRW["ID"], $mirrors)) {
					$url = $mirrorRW['Address'] . $mirrorRW['BaseURL'] . $row['File_Path'] . $row['File_Name'];
					if ($mirrorRW["HTTPS"] == 1) $url = "https://" . $url;
					else $url = "http://" . $url;
					echo "<a href=\"" . $url . "\">Mirror " . $mirrorRW["ID"] . " (" . $mirrorRW["Region"] . ")</a><br>";
				}
			}
		}
	} else {
		if ($mirrors == 1) echo "Invalid ID provided";
	}
	$conn->close();
} else {
	echo "No ID provided";
}

?>
</body>
</html>
