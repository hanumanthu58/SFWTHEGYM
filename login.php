<?php
// Start the session
session_start();

// Load environment variables
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch input values securely
    $userid = trim($_POST['userid']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Query to fetch user credentials
    $sql = "SELECT password FROM regestration WHERE userid = ? AND username = ?";
    $params = [$userid, $username];

    // Prepare and execute the statement
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if (!$stmt) {
        die("SQL Prepare Failed: " . print_r(sqlsrv_errors(), true));
    }

    if (!sqlsrv_execute($stmt)) {
        die("SQL Execution Failed: " . print_r(sqlsrv_errors(), true));
    }

    // Check if user exists
    if (sqlsrv_has_rows($stmt)) {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $hashed_password = $row['password'];

        // Validate the password
        if (password_verify($password, $hashed_password)) {
            // Set session
            $_SESSION['user_id'] = $userid;
            $_SESSION['username'] = $username;

            // Redirect to indexhp.php
            echo "<script>alert('Login successful!'); window.location.href = 'indexhp.php';</script>";
        } else {
            echo "<script>alert('Invalid password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('User ID or Username not found. Please try again.');</script>";
    }

    // Free resources
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - SFW THE GYM</title>
    <link rel="stylesheet" href="login.css?v=1.0">
</head>
<body>
    <div class="login-container">
        <form action="login.php" method="POST" id="login">
            <div class="form-group">
                <h3>Login Page - SFW THE GYM</h3>
                <label for="userid">User ID</label>
                <input type="text" id="userid" name="userid" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="login-button">
                <button type="submit" id="loginBtn">Login</button>
            </div>
        </form>
        <div class="footer-links">
            <a href="forget_password.php">Forget Password</a>
            <a href="registration.php">Register</a>
            <a href="logina.php">Login as Admin</a>
            <a href="logint.php">Login as Trainer</a>
            <a href="terms-of-service.php">Terms of Services</a>
            <a href="privacy-policy.php">Privacy Policy</a>
        </div>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>

</html>
