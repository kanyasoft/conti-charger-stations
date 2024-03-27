<?php
session_start();

// Include database connection
require_once "db_connection.php";
// Function to get messages from database
echo "checkpoing<BR>";
// Fetch charging user data
$sql_charging_time = "SELECT checked_in_users.*, registration_info.* FROM checked_in_users LEFT JOIN registration_info ON checked_in_users.user = registration_info.id";
$result_charging_time = $conn->query($sql_charging_time);


    if ($result_charging_time->num_rows > 0) {
        
        while($row = $result_charging_time->fetch_assoc()) {
            
			$formatted_timestamp = timeForSeconds($row["check_in_time"]);
            echo $row["full_name"]."     ". $formatted_timestamp."<BR>";
        }
    }
?>



<?php
function timeForSeconds($timestamp) {
    $current_time = time();
    $timestamp = strtotime($timestamp);
    $time_difference = $timestamp - $current_time;

        return $time_difference;
}
?>