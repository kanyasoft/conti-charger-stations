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

// Fetch charging stations data
$sql = "SELECT * FROM charging_stations";
$result = $conn->query($sql);

// Fetch charging user data
$sql_registration_info = "SELECT * FROM registration_info WHERE registration_info.id=".$_SESSION['user_id'];


// Fetch checked-in users data
//$sql_checked_in_users = "SELECT * FROM checked_in_users";
$sql_checked_in_users1 = "SELECT registration_info.id  AS registration_id, registration_info.full_name, registration_info.car_model, checked_in_users_1.check_in_type, charging_stations.name, checked_in_users_1.check_in_time
FROM registration_info
LEFT JOIN checked_in_users AS checked_in_users_1 ON registration_info.id = checked_in_users_1.user
LEFT JOIN charging_stations ON charging_stations.id = checked_in_users_1.station_id WHERE checked_in_users_1.station_id = 1 AND charging_stations.name IS NOT NULL ORDER BY checked_in_users_1.check_in_type, checked_in_users_1.id
";
	$sql = "
SELECT DISTINCT full_name, user_id
FROM(
    SELECT registration_info.full_name, registration_info.id AS user_id, messenger.* 
    FROM messenger 
    LEFT JOIN registration_info ON messenger.host_id = registration_info.id 
    WHERE peer_id = ".$_SESSION['user_id']."
    
    UNION 
    
    SELECT registration_info.full_name, registration_info.id AS user_id, messenger.* 
    FROM messenger 
    LEFT JOIN registration_info ON messenger.peer_id = registration_info.id 
    WHERE host_id = ".$_SESSION['user_id']."
    ) AS combined_result
";

$result = $conn->query($sql);
$result_registration_info = $conn->query($sql_registration_info);
$row_registration_info = $result_registration_info->fetch_assoc()
?>

<!DOCTYPE html>
<html>
<body>
<table style=" border-radius: 5px; table-layout:fixed; width:100%; background-color: #f5f3f0; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);">
	<tbody>
		<tr>
			<td style="width:50%">Hi <?php echo $row_registration_info['full_name']; ?> </td>
<td style="text-align:right; width:25%">    <form action="dashboard.php" method="post">
        <button type="submit" style="background-color: #0a0569; color:#eFF">HOME</button>
    </form></td>
			<td style="text-align:right; width:25%">    <form action="?logout" method="post">
        <button type="submit" style="background-color: #e38702; color:#eFF">Logout</button>
    </form></td>
		</tr>
		<!-- Add more rows as needed -->
	</tbody>
</table>
<BR>



       <?php
			$counter = 0;
		while($row = $result->fetch_assoc()): 

		
		?>

<div style="padding: 10px; width: 95%; background-color: #fafeff; border-radius: 5px; box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5);">
    <table style="table-layout:fixed; width:100%;">
	<tbody>
		<tr>
			<td>
				<a style="font-size: 0.9em; font-weight: bold; text-decoration: none; color:#0013a6; padding: 5px; width: 50%;  border-radius: 5px;" href="messenger.php?peer_id=<?php echo $row['user_id'];?>"><?php echo $row['full_name']; ?> </a>
			</td>
			<?php
	$sql = "
SELECT full_name, user_id, new, host_id, peer_id 
FROM(
    SELECT registration_info.full_name, registration_info.id AS user_id, messenger.* 
    FROM messenger 
    LEFT JOIN registration_info ON messenger.host_id = registration_info.id 
    WHERE peer_id = ".$row['user_id']."
    
    UNION 
    
    SELECT registration_info.full_name, registration_info.id AS user_id, messenger.* 
    FROM messenger 
    LEFT JOIN registration_info ON messenger.peer_id = registration_info.id 
    WHERE host_id = ".$row['user_id']."
    ) AS combined_result WHERE new = 1  AND user_id = ".$_SESSION['user_id']."
";		

$result_new_msg = $conn->query($sql);	
			if ($result_new_msg->num_rows != 0)
				echo '
			<td style="text-align:right; width:50%"> 
				<a style="font-weight: bold; text-decoration: none; color:#eFF; padding: 5px; width: 50px; background-color: #0a0569; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);" href="messenger.php?peer_id='.$row['user_id'].'">New message</a>
			</td>
			'
			?>
		</tr>
		<!-- Add more rows as needed -->
	</tbody>
</table>
</div>
<BR>

           
        <?php endwhile; ?>
		







<BR>

</body>
</html>
