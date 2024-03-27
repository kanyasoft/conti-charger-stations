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
// Function to get messages from database

// Fetch charging user data
$sql_registration_info = "SELECT * FROM registration_info WHERE registration_info.id=".$_SESSION['user_id'];
$result_registration_info = $conn->query($sql_registration_info);
$row_registration_info = $result_registration_info->fetch_assoc();


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

function getMessages($conn) {

	$sql = "SELECT * FROM (
    SELECT messenger.*, registration_info.full_name 
    FROM messenger 
    LEFT JOIN registration_info ON messenger.host_id = registration_info.id 
    WHERE (peer_id = " . $_POST['peer_id'] . " AND host_id = " . $_POST['host_id'] . ") OR (peer_id = " . $_POST['host_id'] . " AND host_id = " . $_POST['peer_id'] . ") 
    ORDER BY timestamp DESC 
    LIMIT 10
) AS last_10_records
ORDER BY timestamp ASC;";

	$result = $conn->query($sql);
    $messages = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
			$formatted_timestamp = timeAgo($row["timestamp"]);
            $messages[] = "<div><strong>" . $row["full_name"] . "</strong>: " . $row["message"] . " (" . $formatted_timestamp . ")</div>";
        }
    }
    return $messages;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host_id = $_SESSION['user_id']; // You can get this from session or wherever it comes from
    $peer_id = $_POST['peer_id']; // You can get this from session or wherever it comes from
    $message = $_POST["message"];
    $timestamp = date("Y-m-d H:i:s");

    $sql = "INSERT INTO messenger (host_id, peer_id, message, timestamp, new) VALUES ('$host_id', '$peer_id', '$message', '$timestamp', '1')";

    $conn->query($sql);
}
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



    <!-- Message form -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']."?peer_id=".$_GET['peer_id']; ?>">
        <textarea style="width:95%" name="message" rows="4"  required></textarea><br>
		<input type="hidden" name="peer_id" value="<?php echo $_POST['peer_id']; ?>">
        <input style="background-color: #0a0569; color:#eFF" type="submit" value="Send Message">
    </form>
    
    <hr>
    
    <!-- Display messages -->

    <?php //getMessages($conn); ?>

	   <div id="message-container">
        <?php
        // Display existing messages
        $messages = getMessages($conn);
        foreach ($messages as $message) {
            echo $message;
        }
        ?>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        // Function to check for new messages
        function checkForNewMessages() {
            $.ajax({
                url: 'check_messages.php',
                method: 'GET',
				data: {
					host_id: '<?php echo $_POST['host_id']; ?>',
					peer_id: '<?php echo $_POST['peer_id']; ?>'
					// Add more parameters as needed
				},				
                success: function(response) {
                    // Display new messages
                    $('#message-container').html(response);
                    
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
</body>
</html>

<?php
// Close connection
$conn->close();
?>
