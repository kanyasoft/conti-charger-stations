<?php
//echo "Current time: " . date("h:i:s A");
?>

<?php
// Database connection parameters
error_reporting(E_ALL);

$servername = "127.0.0.1"; // Or use 192.168.1.106 if 'localhost' doesn't work
$username = "root"; // Your MySQL username
$password = "Aiculedssul100!"; // Your MySQL password
$port = 3306; // Specify the port number for MySQL/MariaDB
$database = "testdb";
// Create connection
$conn = new mysqli($servername, $username, $password, $database, $port);

// SQL query
$sql = "SELECT * FROM test_table"; // Change "your_table_name" to the name of your table

// Execute query
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        // Print each row
        foreach ($row as $key => $value) {
            echo $key . ": " . $value . "<br>";
        }
        echo "<br>";
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();
?>



<?php
//echo "Current time: " . date("h:i:s A");
?>


