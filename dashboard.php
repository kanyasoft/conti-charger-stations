<?php
session_start();

// Check if user is logged in
if((!isset($_SESSION['user_id'])) && (!isset($_COOKIE['user_id']))) {
    header("Location: login.php");
    exit;
}

if ((isset($_COOKIE['user_id'])) && (!isset($_SESSION['user_id']))) $_SESSION['user_id'] = $_COOKIE['user_id'];
	

// Check if logout is clicked
if(isset($_GET['logout'])) {
    
	// Set an expired cookie to unset it
setcookie('user_id', '', time() - 3600, '/');
session_destroy();
    header("Location: login.php");
    exit;
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

$sql_checked_in_users2 = "SELECT registration_info.id  AS registration_id, registration_info.full_name, registration_info.car_model, checked_in_users_1.check_in_type, charging_stations.name, checked_in_users_1.check_in_time
FROM registration_info
LEFT JOIN checked_in_users AS checked_in_users_1 ON registration_info.id = checked_in_users_1.user
LEFT JOIN charging_stations ON charging_stations.id = checked_in_users_1.station_id WHERE checked_in_users_1.station_id = 2 AND charging_stations.name IS NOT NULL ORDER BY checked_in_users_1.check_in_type, checked_in_users_1.id
";


$sql_checked_in_users3 = "SELECT registration_info.id  AS registration_id, registration_info.full_name, registration_info.car_model, checked_in_users_1.check_in_type, charging_stations.name, checked_in_users_1.check_in_time
FROM registration_info
LEFT JOIN checked_in_users AS checked_in_users_1 ON registration_info.id = checked_in_users_1.user
LEFT JOIN charging_stations ON charging_stations.id = checked_in_users_1.station_id WHERE checked_in_users_1.station_id = 3 AND charging_stations.name IS NOT NULL ORDER BY checked_in_users_1.check_in_type, checked_in_users_1.id
";


$sql_checked_in_users4 = "SELECT registration_info.id  AS registration_id, registration_info.full_name, registration_info.car_model, checked_in_users_1.check_in_type, charging_stations.name, checked_in_users_1.check_in_time
FROM registration_info
LEFT JOIN checked_in_users AS checked_in_users_1 ON registration_info.id = checked_in_users_1.user
LEFT JOIN charging_stations ON charging_stations.id = checked_in_users_1.station_id WHERE checked_in_users_1.station_id = 4 AND charging_stations.name IS NOT NULL ORDER BY checked_in_users_1.check_in_type, checked_in_users_1.id
";


$result_checked_in_users1 = $conn->query($sql_checked_in_users1);
$result_checked_in_users2 = $conn->query($sql_checked_in_users2);
$result_checked_in_users3 = $conn->query($sql_checked_in_users3);
$result_checked_in_users4 = $conn->query($sql_checked_in_users4);
$result_registration_info = $conn->query($sql_registration_info);
$row_registration_info = $result_registration_info->fetch_assoc()
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	   <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<table style=" border-radius: 5px; table-layout:fixed; width:100%; background-color: #f5f3f0; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);">
	<tbody>
		<tr>
			<td style="width:50%">Hi <?php echo $row_registration_info['full_name']; ?> </td>
			<td style="text-align:right; width:25%">    <form action="messages.php" method="post">
        <button type="submit" style="background-color: #0a0569; color:#eFF">Inbox <span id="inbox_new_messages"></span></button>
    </form></td>
			<td style="text-align:right; width:25%">    <form action="?logout" method="post">
        <button type="submit" style="background-color: #e38702; color:#eFF">Logout</button>
    </form></td>
		</tr>
		<!-- Add more rows as needed -->
	</tbody>
</table>
<BR>


<div style="padding: 10px; width: 95%; background-color: #fafeff; border-radius: 5px; box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5);">
    <table style="table-layout:fixed; width:100%;">
	<tbody>
		<tr>
			<td style="font-weight: bold; text-decoration: none; color:#eFF; padding: 5px; width: 50%; background-color: #e38702; border-radius: 5px;">RD3 Station </td>
			<td style="text-align:right; width:50%"> 
			<a style="font-weight: bold; text-decoration: none; color:#eFF; padding: 5px; width: 50px; background-color: #0a0569; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);" href="station_details.php?id=1">Check in</a></td>
		</tr>
		<!-- Add more rows as needed -->
	</tbody>
</table>
	

    <DIV style="padding: 5px; ">

        <?php
			$counter = 0;
		while($row = $result_checked_in_users1->fetch_assoc()): 
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
                <?php echo '<span style="font-size: 0.7em; text-decoration: none; ">  is </span>' . '<span style="font-size: 0.7em; text-decoration: none; color: #04872b ">' . $row['check_in_type']; ?> </span><BR>
                <?php echo '<span style="color: #909090; font-size: 0.7em; text-decoration: none; ">' .$row['car_model']; ?></span><BR>
                <?php echo '<span style="font-size: 0.7em; text-decoration: none; "> ' . "for " . $hours . " hours and " . $minutes . " minutes"; ?>.


                <?php echo "Check out at " . date('H:i', strtotime($row['check_in_time'])); ?></span><BR>
           
        <?php endwhile; 
		if ($counter == 0) echo "nobody is here"; 
		?>
    </DIV>
 </div>


<BR>

<div style="padding: 10px; width: 95%; background-color: #fafeff; border-radius: 5px; box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5);">
    <table style="table-layout:fixed; width:100%;">
	<tbody>
		<tr>
			<td style="font-weight: bold; text-decoration: none; color:#eFF; padding: 5px; width: 50%; background-color: #e38702; border-radius: 5px;">Kindergarden </td>
			<td style="text-align:right; width:50%"> 
			<a style="font-weight: bold; text-decoration: none; color:#eFF; padding: 5px; width: 50px; background-color: #0a0569; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);" href="station_details.php?id=2">Check in</a></td>
		</tr>
		<!-- Add more rows as needed -->
	</tbody>
</table>
	

    <DIV style="padding: 5px;">

        <?php
			$counter = 0;
		while($row = $result_checked_in_users2->fetch_assoc()): 
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
                <?php echo '<span style="font-size: 0.7em; text-decoration: none; ">  is </span>' . '<span style="font-size: 0.7em; text-decoration: none; color: #04872b ">' . $row['check_in_type']; ?> </span><BR>
                <?php echo '<span style="color: #909090; font-size: 0.7em; text-decoration: none; ">' .$row['car_model']; ?></span><BR>
                <?php echo '<span style="font-size: 0.7em; text-decoration: none; "> ' . "for " . $hours . " hours and " . $minutes . " minutes"; ?>.


                <?php echo "Check out at " . date('H:i', strtotime($row['check_in_time'])); ?></span><BR>
        <?php endwhile; 
		if ($counter == 0) echo "nobody is here"; 
		?>
    </DIV>
 </div>
 
 <BR>
 
 
<div style="padding: 10px; width: 95%; background-color: #fafeff; border-radius: 10px; box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5);">
    <table style="table-layout:fixed; width:100%;">
	<tbody>
		<tr>
			<td style="font-weight: bold; text-decoration: none; color:#eFF; padding: 5px; width: 50%; background-color: #e38702; border-radius: 5px;">GataA Station </td>
			<td style="text-align:right; width:50%"> 
			<a style="font-weight: bold; text-decoration: none; color:#eFF; padding: 5px; width: 50px; background-color: #0a0569; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);" href="station_details.php?id=3">Check in</a></td>
		</tr>
		<!-- Add more rows as needed -->
	</tbody>
</table>
	

    <DIV style="padding: 5px;">

        <?php
			$counter = 0;
		while($row = $result_checked_in_users3->fetch_assoc()): 
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
                <?php echo '<span style="font-size: 0.7em; text-decoration: none; ">  is </span>' . '<span style="font-size: 0.7em; text-decoration: none; color: #04872b ">' . $row['check_in_type']; ?> </span><BR>
                <?php echo '<span style="color: #909090; font-size: 0.7em; text-decoration: none; ">' .$row['car_model']; ?></span><BR>
                <?php echo '<span style="font-size: 0.7em; text-decoration: none; "> ' . "for " . $hours . " hours and " . $minutes . " minutes"; ?>.


                <?php echo "Check out at " . date('H:i', strtotime($row['check_in_time'])); ?></span><BR>
        <?php endwhile; 
		if ($counter == 0) echo "nobody is here"; 
		?>
    </DIV>
 </div>
 
 
 <BR>
 
<div style="padding: 10px; width: 95%; background-color: #fafeff; border-radius: 10px; box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5);">
    <table style="table-layout:fixed; width:100%;">
	<tbody>
		<tr>
			<td style="font-weight: bold; text-decoration: none; color:#eFF; padding: 5px; width: 50%; background-color: #e38702; border-radius: 5px;">Logistic Station </td>
			<td style="text-align:right; width:50%"> 
			<a style="font-weight: bold; text-decoration: none; color:#eFF; padding: 5px; width: 50px; background-color: #0a0569; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);" href="station_details.php?id=4">Check in</a></td>
		</tr>
		<!-- Add more rows as needed -->
	</tbody>
</table>
	

    <DIV style="padding: 5px;">

        <?php
			$counter = 0;
		while($row = $result_checked_in_users4->fetch_assoc()): 
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
                <?php echo '<span style="font-size: 0.7em; text-decoration: none; ">  is </span>' . '<span style="font-size: 0.7em; text-decoration: none; color: #04872b ">' . $row['check_in_type']; ?> </span><BR>
                <?php echo '<span style="color: #909090; font-size: 0.7em; text-decoration: none; ">' .$row['car_model']; ?></span><BR>
                <?php echo '<span style="font-size: 0.7em; text-decoration: none; "> ' . "for " . $hours . " hours and " . $minutes . " minutes"; ?>.


                <?php echo "Check out at " . date('H:i', strtotime($row['check_in_time'])); ?></span><BR>
        <?php endwhile; 
		if ($counter == 0) echo "nobody is here"; 
		?>
    </DIV>
 </div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        // Function to check for new messages
        function checkForNewMessages() {
            $.ajax({
                url: 'messages_check_new.php',
                method: 'GET',
				data: {
					host_id: '<?php echo $_POST['host_id']; ?>',
					peer_id: '<?php echo $_POST['peer_id']; ?>'
					// Add more parameters as needed
				},				
                success: function(response) {
                    // Display new messages
                    $('#inbox_new_messages').html(response);
                    
                    // Check for new messages again after a delay
                    setTimeout(checkForNewMessages, 3000); // Repeat every 3 seconds
					//alert("This is a message box!");
                },
                error: function(xhr, status, error) {
                    console.error('Error checking for messages:', error);
                    
                    // Retry after a delay
                    setTimeout(checkForNewMessages, 3000); // Retry after 3 seconds
                }
            });
        }
        
        // Call the function to start checking for messages
        checkForNewMessages();
    });
    </script>



<BR>

</body>
</html>
