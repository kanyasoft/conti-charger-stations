<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once "db_connection.php";

// Check if logout is clicked
if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
if(isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    $sql_delete = "DELETE FROM checked_in_users WHERE id = $delete_id";
    if($conn->query($sql_delete) === TRUE) {
        // Refresh the page after deletion
	
		mysqli_close($conn);
		header("Location: station_details.php?id=".$_GET['id']);
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}


if(isset($_POST['switch_charge_id'])) {
    $switch_charge_id = $_POST['switch_charge_id'];
    $sql_delete = "UPDATE `checked_in_users` SET `check_in_type`='charging' WHERE `id` = $switch_charge_id";
    if($conn->query($sql_delete) === TRUE) {
        // Refresh the page after deletion
	
		mysqli_close($conn);
		header("Location: station_details.php?id=".$_GET['id']);
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

if(isset($_POST['switch_wait_id'])) {
    $switch_wait_id = $_POST['switch_wait_id'];
    $sql_delete = "UPDATE `checked_in_users` SET `check_in_type`='waiting' WHERE `id` = $switch_wait_id";
    if($conn->query($sql_delete) === TRUE) {
        // Refresh the page after deletion
	
		mysqli_close($conn);
		header("Location: station_details.php?id=".$_GET['id']);
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch station details based on ID
if(isset($_GET['id'])) {
    $station_id = $_GET['id'];
    $sql_station = "SELECT * FROM charging_stations WHERE id = $station_id";
    $result_station = $conn->query($sql_station);
    $station = $result_station->fetch_assoc();
}

// Check if check-in form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_SESSION['user_id'];
    $check_in_type = $_POST['check_in_type'];
    $time = $_POST['time'];
	$time_expiration  = date('Y-m-d H:i:s', time()+(3600*$time));
    // Insert check-in data into database
    $sql_check_in = "INSERT INTO checked_in_users (user, check_in_type, check_in_time, station_id) VALUES ('$user', '$check_in_type', '$time_expiration', $station_id)";
    $conn->query($sql_check_in);
}
$sql_checked_in_users = "SELECT registration_info.id  AS registration_id, registration_info.full_name, registration_info.car_model, checked_in_users_1.check_in_type, charging_stations.name, checked_in_users_1.check_in_time, checked_in_users_1.id
FROM registration_info
LEFT JOIN checked_in_users AS checked_in_users_1 ON registration_info.id = checked_in_users_1.user
LEFT JOIN charging_stations ON charging_stations.id = checked_in_users_1.station_id WHERE checked_in_users_1.station_id = $station_id ORDER BY checked_in_users_1.check_in_type, checked_in_users_1.id
";




$result_checked_in_users = $conn->query($sql_checked_in_users);
$result_checked_in_users2 = $conn->query($sql_checked_in_users);

$sql_registration_info = "SELECT * FROM registration_info WHERE registration_info.id=".$_SESSION['user_id'];

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
<div style="font-weight: bold; text-decoration: none; color:#eFF; padding: 5px; width: 95%; background-color: #e38702; border-radius: 5px;"><?php echo $station['name']; ?></div>
 
    <h3>Check-in</h3>
    <form action="" method="post">
<span style="font-size: 1.2em; font-weight: bold; text-decoration: none;  color:#0013a6;"> Time: </span>&nbsp;&nbsp;&nbsp;&nbsp;
       
<select name="time" required style="font-size: 1.2em; font-weight: bold; text-decoration: none;  color:#ffffff; border-radius: 5px; table-layout:fixed;  background-color: #04cc57; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);">
    <?php
    // Generate options from 0.5 to 8 by 0.5 increments
 //   echo "<option value='0.01'>test minute h</option>";
	for ($i = 0.5; $i <= 8; $i += 0.5) {
        echo "<option value='$i'>$i h</option>";
    }
    ?>
</select><br><BR>
   <button style="font-weight: bold; text-decoration: none; color:#eFF; padding: 5px; background-color: #04cc57; border-radius: 5px;" type="submit" name="check_in_type" value="charging">Charging</button>&nbsp;&nbsp;&nbsp;&nbsp;
    <button style="font-weight: bold; text-decoration: none; color:#eFF; padding: 5px; background-color: #049dcc; border-radius: 5px;" type="submit" name="check_in_type" value="waiting">Waiting</button><br>
 
    </form>


   <DIV style="padding: 5px; ">

        <?php
			$counter = 0;
		while($row = $result_checked_in_users->fetch_assoc()): 
		$counter++;
		
		$future_time = $row['check_in_time'];
		$future_timestamp = strtotime($future_time);
		$current_timestamp = time();
		$time_difference = $future_timestamp - $current_timestamp;
		$hours = floor($time_difference / 3600);
		$minutes = floor(($time_difference % 3600) / 60);
		
		?><hr style="border-top: 1px solid #000;"> 
               <?php 
					if ($_SESSION['user_id'] != $row['registration_id'])
					{	
						echo '<a style="font-size: 0.9em; font-weight: bold; text-decoration: none;  color:#0013a6;" href="messenger.php?peer_id='. $row['registration_id'] .'" >'.$row['full_name'].'</a>';
					}
					else
					{
						echo '<span style="font-size: 0.9em; font-weight: bold; text-decoration: none; ">' . $row['full_name'] . "</span>";
					}
					?>
                <?php echo '<span style="font-size: 0.7em; text-decoration: none; ">  is </span>' . '<span style="font-size: 0.9em; font-weight: bold; text-decoration: none; color: #04872b ">' . $row['check_in_type']; ?> </span><BR>
                <?php echo '<span style="color: #909090; font-size: 0.7em; text-decoration: none; ">' .$row['car_model']; ?></span><BR>
                <?php echo '<span style="font-size: 0.7em; text-decoration: none; "> ' . "for " . $hours . " hours and " . $minutes . " minutes"; ?>.


                <?php echo "Check out at " . date('H:i', strtotime($row['check_in_time'])); ?></span><BR>
 				<?php 
					if ($_SESSION['user_id'] == $row['registration_id'])
					{					
						echo "<form action='' method='post'><input type='hidden' name='delete_id' value='" . $row['id'] . "'><input type='submit' name='delete' value='Check out' style='display: inline-block font-weight: bold; text-decoration: none; color:#eFF; background-color: #b50802;'>&nbsp;&nbsp;&nbsp;&nbsp;"; 
						if ($row['check_in_type'] == 'waiting')
						echo "<input type='hidden' name='switch_charge_id' value='" . $row['id'] . "'><input type='submit' name='switch' value='Switch to charge' style='display: inline-block font-weight: bold; text-decoration: none; color:#eFF; background-color: #04cc57;'></form>"; 
						if ($row['check_in_type'] == 'charging')
						echo "<input type='hidden' name='switch_wait_id' value='" . $row['id'] . "'><input type='submit' name='switch' value='Switch to wait' style='display: inline-block font-weight: bold; text-decoration: none; color:#eFF; background-color: #049dcc;'></form>"; 
					}			
				?>          
        <?php endwhile; 
		if ($counter == 0) echo "<BR><BR>nobody is here"; 
		?>
    </DIV>



</body>
</html>
