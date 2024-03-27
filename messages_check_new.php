<?php
session_start();

require_once "db_connection.php";

// Fetch charging stations data
$sql = "SELECT * FROM messenger WHERE peer_id = ".$_SESSION['user_id']." AND new = 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) 
	echo '<span style="font-weight: bold; color:#00d400">('.$result->num_rows.')</span>';
else
	echo '<span style="font-weight: bold; color:#eFF">('.$result->num_rows.')</span>';



?>