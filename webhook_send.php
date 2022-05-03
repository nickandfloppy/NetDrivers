<?php
declare(strict_types=1);
require('creds.php');

$conn = new mysqli(CONF["servername"], CONF["username"], CONF["password"], CONF["dbname"]);
// Check connection
if ($conn->connect_error) {
   die('Connection failed: ' . $conn->connect_error);
}

$sql      = "SELECT id, content FROM webhook_queue";
$result   = $conn->query($sql);

function IsNullOrEmptyString($str){
    return ($str === null || trim($str) === '');
}

function isJson($string) {
	json_decode($string);
	return json_last_error() === JSON_ERROR_NONE;
 }

if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		echo $row['id'] . " - " . $row['content'];
		$timestamp = date("c", strtotime("now"));

		$json_data = json_encode([
			"username" => "NetDrivers",
			"avatar_url" => "http://drivers.nickandfloppy.com/favicon.png",
			"tts" => false,
			"embeds" => [
				[
					"title" => "NetDrivers Database Updated",
					"type" => "rich",
					"description" => $row['content'],
					//"url" => "url",
					"timestamp" => $timestamp,
					"color" => hexdec( "3366ff" ),
					"footer" => [
						"text" => "ID " . $row['id'],
						"icon_url" => "http://drivers.nickandfloppy.com/favicon.png"
					]
				]
			]
		], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		$ch = curl_init(CONF["webhook"]);
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		curl_setopt( $ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $ch );
		echo $response;
		curl_close( $ch );
		
		if (!isJson($response)) {
			// Remove from queue
			$sql = 'DELETE FROM webhook_queue WHERE id=' . $row['id'];
			$conn->query($sql);
		}
	}
} else {
	echo "Queue empty!";
}
$conn->close();
?>