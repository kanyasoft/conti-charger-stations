<?php
// Include database connection
require_once "db_connection.php";

// Check if form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];

    // Generate reset token and send reset link to user's email
    // (Implementation of this functionality is omitted here for brevity)
    
    $message = "A password reset link has been sent to your email.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <?php if(isset($message)) echo '<div>'.$message.'</div>'; ?>
    <form action="" method="post">
        <label>Email:</label><br>
        <input type="email" name="email"><br>
        <input type="submit" value="Submit">
    </form>
    <p>Remember your password? <a href="login.php">Login</a>.</p>
</body>
</html>
