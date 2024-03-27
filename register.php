<?php
// Include database connection
require_once "db_connection.php";

// Check if form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $full_name = $_POST['full_name'];
    $car_registration_plate = $_POST['car_registration_plate'];
    $car_model = $_POST['car_model'];
    $email = $_POST['email'];
	$invitation = $_POST['invitation'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);;

    // Prepare insert statement
    $sql = "INSERT INTO registration_info (full_name, car_registration_plate, car_model, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
	
	
    $sql = "SELECT * FROM invitations WHERE invitation = '". $invitation ."'";
	$result_invitation = $conn->query($sql);

    $sql = "SELECT * FROM registration_info WHERE email = '". $email ."'";
	$result_reg_info = $conn->query($sql);
	
	if ($result_invitation->num_rows == 0)
	{
		header("Location: register.php?error=1");
		exit;
	}
	if ($result_reg_info->num_rows != 0)
	{
		header("Location: register.php?error=2");
		exit;
	}	
    // Check if prepare statement succeeded
    if(!$stmt) {
        die("Prepare statement failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("sssss", $full_name, $car_registration_plate, $car_model, $email, $password);

    // Execute statement
    if($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        die("Execute statement failed: " . $stmt->error);
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
	<?php 
		if ($_GET['error'] == 1)
		{
			$error="Invitation code is wrong";
		}
		if ($_GET['error'] == 2)
		{
			$error="Email is already in use";
		}
		?>
    <?php if(isset($error)) echo '<div style="color: red;">'.$error.'</div>'; ?>
 <form action="" method="post">
    <label>Invitation code:</label><br>
    <input type="text" name="invitation" required><br><br>
    <label>Full Name:</label><br>
    <input type="text" name="full_name" required><br><br>
    <label>Car Registration Plate:</label><br>
    <input type="text" name="car_registration_plate" required><br><br>
    <label>Car Model:</label><br>
    <input type="text" name="car_model" required><br><br>
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <input type="submit" value="Register">
</form>

    <p>Already have an account? <a href="login.php">Login</a>.</p>
</body>
</html>
