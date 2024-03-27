<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if logout is clicked
if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
if(isset($_GET['peer_id'])) {
    $_POST['peer_id'] = $_GET['peer_id'];
    $_POST['host_id'] = $_SESSION['user_id'];
}
// Include database connection
require_once "db_connection.php";
function timeAgo($timestamp) {
    $current_time = time();
    $timestamp = strtotime($timestamp);
    $time_difference = $current_time - $timestamp;

    if ($time_difference < 60) {
        return $time_difference . " sec ago";
    } elseif ($time_difference < 3600) {
        return floor($time_difference / 60) . " min ago";
    } elseif ($time_difference < 86400) {
        return floor($time_difference / 3600) . "h ago";
    } else {
        return floor($time_difference / 86400) . " days ago";
    }
}
// Function to get messages from database
function getMessages($conn) {

$sql_update = "
UPDATE messenger
LEFT JOIN registration_info ON messenger.host_id = registration_info.id
SET new = 0
WHERE ( (peer_id = " . $_POST['host_id'] . " AND host_id = " . $_POST['peer_id'] . ")) ;
";
//echo $sql_update;
$result = $conn->query($sql_update);

	$sql = "SELECT * FROM (
    SELECT messenger.*, registration_info.full_name 
    FROM messenger 
    LEFT JOIN registration_info ON messenger.host_id = registration_info.id 
    WHERE (peer_id = " . $_POST['peer_id'] . " AND host_id = " . $_POST['host_id'] . ") OR (peer_id = " . $_POST['host_id'] . " AND host_id = " . $_POST['peer_id'] . ") 
    ORDER BY timestamp DESC 
    LIMIT 8
) AS last_10_records
ORDER BY timestamp ASC;";






	$result = $conn->query($sql);
    $messages = array();
	$togle = 0;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
			$formatted_timestamp = timeAgo($row["timestamp"]);
            $messages[] = '<div style="padding: 3px; margin: 15px; width: 90%; background-color: #fafeff; border-radius: 5px; box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);">
			<span style="font-size: 0.8em; font-weight: bold; text-decoration: none;  color:#0013a6;">' . $row["full_name"] . "</span>".'<span style="font-size: 0.7em; text-decoration: none; "> ('. $formatted_timestamp . ')</span><BR> <span style="font-size: 0.8em; text-decoration: none; ">' . $row["message"] . "</span></div>";
        }
    }
    return $messages;
}

// Retrieve new messages
$new_messages = getMessages($conn);

// Output new messages
foreach ($new_messages as $message) {
    echo $message;
}

// Close connection
$conn->close();
?>
