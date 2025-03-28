<?php
session_start();
require_once 'db_connection.php'; // Include database connection

// Check if the database connection was successful
if ($conn === false) {
    die("Database connection failed: " . print_r(sqlsrv_errors(), true));
}

// Initialize message variables
$message = "";
$messageClass = "";

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch input values securely using null coalescence
    $userid = trim($_POST['userid'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $password = $_POST['password'] ?? '';
    $contactno = trim($_POST['contactno'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // Validation - Check for empty fields
    if (empty($userid) || empty($username) || empty($fullname) || empty($password) || empty($email)) {
        $message = "All fields are required.";
        $messageClass = "error";
    } else {
        // Password Hashing
        $encryptedPassword = password_hash($password, PASSWORD_BCRYPT);
        $role = 'user';

        // SQL Query to insert data
        $sql = "INSERT INTO regestration (userid, username, name, password, contact, email, address, role, email_verified) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [$userid, $username, $fullname, $encryptedPassword, $contactno, $email, $address, $role, 0];

        $stmt = sqlsrv_query($conn, $sql, $params);

        // Error handling
        if ($stmt === false) {
            $message = "Error during registration: " . print_r(sqlsrv_errors(), true);
            $messageClass = "error";
        } else {
            $_SESSION['userid'] = $userid;
            $_SESSION['fullname'] = $fullname;
            header('Location: user_register.php');
            exit;
        }

        // Free statement only if it was successful
        if ($stmt !== false) {
            sqlsrv_free_stmt($stmt);
        }
    }
    sqlsrv_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gym Registration</title>
    <link rel="stylesheet" href="registration.css" />
</head>
<body>
    <div class="container">
        

        <!-- Display success or error message -->
        <?php if (!empty($message)): ?>
            <div class="message <?= htmlspecialchars($messageClass); ?>">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form action="registration.php" method="POST" id="registrationForm">
            <div class="form-group">
                <label for="userid">User ID</label>
                <input type="text" id="userid" name="userid" required />
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required />
            </div>

            <div class="form-group">
                <label for="fullname">Name</label>
                <input type="text" id="fullname" name="fullname" required />
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />
            </div>

            <div class="form-group">
                <label for="contactno">Contact No</label>
                <input type="text" id="contactno" name="contactno" required />
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required />
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required />
            </div>

            <div class="form-group">
                <button type="submit" class="register-button">Register</button>
            </div>
        </form>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
