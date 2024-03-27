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

// Include database connection
require_once "db_connection.php";

// Fetch charging stations data
$sql = "SELECT * FROM charging_stations";
$result = $conn->query($sql);

// Fetch checked-in users data
//$sql_checked_in_users = "SELECT * FROM checked_in_users";
$sql_checked_in_users = "SELECT registration_info.full_name, checked_in_users_1.check_in_type, charging_stations.name, checked_in_users_1.check_in_time
FROM registration_info
LEFT JOIN checked_in_users AS checked_in_users_1 ON registration_info.id = checked_in_users_1.user
LEFT JOIN charging_stations ON charging_stations.id = checked_in_users_1.station_id WHERE checked_in_users_1.station_id IS NOT NULL AND charging_stations.name IS NOT NULL ORDER BY charging_stations.name
";
$result_checked_in_users = $conn->query($sql_checked_in_users);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome to Dashboard</h2>
	<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Go Back</a>

    <form action="?logout" method="post">
        <button type="submit">Logout</button>
    </form>
    <h3>Charging Stations</h3>
    <table border="1">
        <tr>
            <th>Charging Station Name</th>
            <th>Status</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><a href="station_details.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <h3>Checked-in Users</h3>
    <table border="1">
        <tr>
            <th>User</th>
            <th>Station</th>
            <th>Check-in Type</th>
            <th>Check-in Time</th>
        </tr>
        <?php while($row = $result_checked_in_users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['check_in_type']; ?></td>
                <td><?php echo $row['check_in_time']; ?></td>
                <td><?php echo $row['check_in_time']; ?></td>
            </tr>
        <?php endwhile; ?>
		

    </table>
<?php
echo "Current time: " . date("h:i:s A");
?>
</body>
</html>
