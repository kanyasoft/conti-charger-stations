<?php
$servername = "localhost"; // Change this to your MySQL/MariaDB server hostname
$username = "epumpro_epumpro"; // Change this to your MySQL/MariaDB username
$password = "Softy1982!"; // Change this to your MySQL/MariaDB password
$database = "epumpro_conti_db"; // Change this to the name of your database
$port = 3306; // Specify the port number for MySQL/MariaDB

// Create connection
$conn = new mysqli($servername, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM `checked_in_users` WHERE check_in_time < NOW();";
$result = $conn->query($sql);
?>
