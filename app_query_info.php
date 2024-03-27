<?php

$new_message_notification = 0;

if(($_GET['user_id']!=0xFFFFFFFF)) {

if ($_GET['action'] == "write_token")
{
	require_once "db_connection.php";	
	
	
	
	$sql = 'UPDATE registration_info SET firebase_token="'.$_GET['token'].'" WHERE registration_info.id = '.$_GET['user_id'];

	$result = $conn->query($sql);
}
else
	{	
require_once "db_connection.php";

// Fetch charging stations data
$sql = "SELECT * FROM messenger WHERE peer_id = ".$_GET['user_id']." AND new = 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) 
	$new_message_notification = 1;
else
	$new_message_notification = 0;

	
	
	
	echo 'new_message_notification='.$new_message_notification;
	echo ';Helloka';
}
}

?>
