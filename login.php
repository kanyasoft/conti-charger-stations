<?php
// Start session
session_start();

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// Check if form is submitted
// Check if form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    require_once "db_connection.php";

    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Validate credentials
    $sql = "SELECT * FROM registration_info WHERE email = ?";
    $stmt = $conn->prepare($sql);
	if(!$stmt) {
    die("Prepare statement failed: " . $conn->error);
}
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 1) {
		
        // User authenticated, set session
        $row = $result->fetch_assoc();
		//echo password_hash($_POST['password'], PASSWORD_DEFAULT);
		
		if (password_verify($password, $row['password']))
		{
			$_SESSION['user_id'] = $row['id'];
			// Redirect to dashboard
			setcookie('user_id', $_SESSION['user_id']);
			header("Location: dashboard.php");
			exit;
		}
		else
		{
			$error = "Invalid email or password.";
		}
    } else {
        // Invalid credentials
        $error = "Invalid email or password.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if(isset($error)) echo '<div style="color: red;">'.$error.'</div>'; ?>
    <form action="" method="post">
        <label>Email:</label><br>
        <input type="text" name="email"><br><br>
        <label>Password:</label><br>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Login"><br><br>
    </form>
    <p>Don't have an account? <a href="register.php">Register now</a>.</p>
    <p>Forgot your password? <a href="forgot_password.php">Reset it</a>.</p>
</body>
</html>
