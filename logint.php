<?php
session_start();

// Include database connection
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate and sanitize inputs
    $trainerid = trim($_POST['tid'] ?? ''); 
    $name = trim($_POST['tname'] ?? '');
    $password = $_POST['password'] ?? '';

    // Query to fetch user credentials
    $sql = "SELECT password FROM trainer WHERE trainer_id = ? AND username = ?";
    $params = [$trainerid, $name];

    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(json_encode(["error" => sqlsrv_errors()]));
    }

    // Check if user exists
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $hashed_password = $row['password'];

        // If passwords are stored in plaintext, change this condition to `$password === $hashed_password`
        if (password_verify($password, $hashed_password)) {
            $_SESSION['tname'] = $name;
            $_SESSION['tid'] = $trainerid;

            echo "<script>alert('Login successful! Redirecting...'); window.location.href = 'trainerdash.php';</script>";
        } else {
            echo "<script>alert('Invalid password, please try again.');</script>";
        }
    } else {
        echo "<script>alert('Trainer ID or Username not found, please try again.');</script>";
    }

    sqlsrv_free_stmt($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Trainer Login Page</title>
    <link rel="stylesheet" href="login.css" />
</head>
<body>
    <div class="login-container">
        <h3>Trainer Login</h3>
        <form action="logint.php" method="POST" id="loginForm">
            <div class="form-group">
                <label for="trainerid">Trainer Id</label>
                <input type="text" id="tid" name="tid" required />
            </div>
            <div class="form-group">
                <label for="tname">Trainer Name</label>
                <input type="text" id="tname" name="tname" required />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />
            </div>
            <div class="login-button">
                <button type="submit" id="loginBtn">Login</button>
            </div>
            <a href="tforget-password.php">Forget Password</a>
            <a href="trainer_registration.php">Register</a>
        </form>
    </div>

    <script>
        document.getElementById('loginBtn').addEventListener('click', function () {
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
