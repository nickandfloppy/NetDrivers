<head>
	<title>Query Results</title>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="/res/style.css">
	<link rel="shortcut icon" type="image/png" href="/favicon.png"/>
</head>
<h1>NetDrivers Search</h1>
<br><a href="/">Home</a> | <a href="javascript:history.back()">Back</a>
<hr>
<form action="search.php" method="post">
		<input type="text" name="query">&nbsp;<input type="submit"><br>
		<input type="radio" name="scope" checked="true"
<?php if (isset($scope) && $scope=="systems") echo "checked";?>
value="systems">Systems<input type="radio" name="scope"
<?php if (isset($scope) && $scope=="drivers") echo "checked";?>
value="drivers">Drivers
</form>
<br>
<?php

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
$queryScope = test_input($_POST["scope"]);
$query = '%'.$_POST["query"].'%';
if($query != "%%"){
	$cleanquery = str_replace("%","",$query);
	echo "Results for \"" . $cleanquery . "\" in " . $queryScope;
}
?>
<hr>
<?php
include 'creds.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

if ($queryScope == "systems") {
	if ($query == "%%"){
		return;
	}else{
		$stmt = $conn->prepare("SELECT ID, Manufacturer, Model FROM systems WHERE Model LIKE ?");
		$stmt->bind_param(s,$query);
		$stmt->execute();
		$result = $stmt->get_result();
	
	}
	
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<h2><a href=\"/systems.php?id=" . $row["ID"] . "\">". $row["Manufacturer"] . " " . $row["Model"] . "</a></h2>";
			echo "<hr>";
		}
	} else {
		echo "No Results for " . $query;
	}
} else if ($queryScope == "drivers") {
    if ($query == "%%"){
		return;
	}else{
		$stmt = $conn->prepare("SELECT ID, Manufacturer, Device_Name FROM drivers WHERE Device_Name LIKE ? OR Manufacturer LIKE ?");
		$stmt->bind_param(ss,$query, $query);
		$stmt->execute();
		$result = $stmt->get_result();
	
	}
	
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<h2><a href=\"/drivers.php?id=" . $row["ID"] . "\">". $row["Manufacturer"] . " " . $row["Device_Name"] . "</a></h2>";
			echo "<hr>";
		}
	} else {
		echo "No Results for \"" . $cleanquery . "\"";
	}
}
$conn->close();
?>
