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
}
// Include database connection
require_once "db_connection.php";
// Function to get messages from database
function getMessages($conn) {
    $sql = "SELECT * FROM messenger ORDER BY timestamp ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div><strong>" . $row["host_id"] . "</strong>: " . $row["message"] . " (" . $row["timestamp"] . ")</div>";
        }
    } else {
        echo "No messages";
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host_id = $_SESSION['user_id']; // You can get this from session or wherever it comes from
    $peer_id = $_POST['peer_id']; // You can get this from session or wherever it comes from
    $message = $_POST["message"];
    $timestamp = date("Y-m-d H:i:s");

    $sql = "INSERT INTO messenger (host_id, peer_id, message, timestamp) VALUES ('$host_id', '$peer_id', '$message', '$timestamp')";

    if ($conn->query($sql) === TRUE) {
        echo "Message sent successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Messenger</title>
</head>
<body>
<a href="dashboard.php">Go Back</a>
    <h2>Messenger</h2>
    
    <!-- Message form -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']."?peer_id=".$_GET['peer_id']; ?>">
        <textarea name="message" rows="4" cols="50" required></textarea><br>
		<input type="hidden" name="peer_id" value="<?php echo $_POST['peer_id']; ?>">
        <input type="submit" value="Send Message">
    </form>
    
    <hr>
    
    <!-- Display messages -->
    <h3>Messages</h3>
    <?php getMessages($conn); ?>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
