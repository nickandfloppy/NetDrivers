<html>
<head>
	<?php $title = "Batch Download"; include 'head.php'; ?>
</head>
<body>
<h1>Batch download</h1>
<hr>
<?php
include 'creds.php';

if (isset($_GET['files'])) {
    $files = "[" . $_GET['files'] . "]";
    $filesarr = json_decode($files);
    $sql = "SELECT ID, File_Name, File_Path, Version, Date, Mirrors FROM files WHERE ";

    $i = 0;
    foreach ($filesarr as $file) {
        if ($i != 0) $sql = $sql . " OR ";
        $sql = $sql . "id = " . $file;
        $i = $i + 1;
    }

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
    }
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
    	while($row = $result->fetch_assoc()) {
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