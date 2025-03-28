<?php
session_start(); // Start the session to store session variables

// Include database connection
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aid = $_POST['aid'] ?? '';
    $aname = $_POST['aname'] ?? '';
    $password = $_POST['password'] ?? '';

    // Query to fetch user credentials
    $sql = "SELECT password FROM admin WHERE admin_id = ? AND username = ?";
    $params = [$aid, $aname];

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // If user found, validate the password
    if (sqlsrv_has_rows($stmt)) {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $hashed_password = $row['password']; // Fetch the stored hashed password

        if (password_verify($password, $hashed_password)) {
            // Store the admin ID in the session
            $_SESSION['admin_id'] = $aid;  // Storing admin_id in session

            // Redirect to admin dashboard
            echo "<script>alert('Login successful!'); window.location.href = 'admindash.php';</script>";
        } else {
            echo "<script>alert('Invalid password, please try again.');</script>";
        }
    } else {
        echo "<script>alert('Admin ID or Admin name not found, please try again.');</script>";
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h3>Admin Login</h3>
        
        <form action="logina.php" method="POST" id="loginForm">
            <div class="form-group">
                <label for="adminid">Admin Id</label>
                <input type="text" id="aid" name="aid" required>
            </div>
            <div class="form-group">
                <label for="aname">Admin username</label>
                <input type="text" id="aname" name="aname" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="login-button">
                <button type="submit" id="loginBtn">Login</button>
            </div>
            <a href="aforget-password.php">Forget Password</a>
            <a href="admin_registration.php">Forget Password</a>
        </form>
    </div>

    <script>
        document.getElementById('loginBtn').addEventListener('click', function() {
            document.getElementById('loginForm').submit();
        });
    </script>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
