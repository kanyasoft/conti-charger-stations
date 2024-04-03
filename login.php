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
<div class="container">
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
</div>
</body>
</html>
