<?php
session_start();  // Start session to track user activity

include('db_connection.php'); // Ensure this file contains a valid `$conn`

if (isset($_SESSION['userid']) && isset($_SESSION['fullname'])) {
    $userId = $_SESSION['userid']; // Fetch user ID from session
    $username = $_SESSION['fullname']; // Fetch full name from session

    // Activity details
    $activityType = "User Registration";
    $description = "User $username has registered successfully.";

    // Ensure connection is valid
    if (!$conn) {
        die("Database connection failed: " . print_r(sqlsrv_errors(), true));
    }

    // Insert activity into recent_activity table
    $sql = "INSERT INTO recent_activity (user_id, action_type, description, timestamp) 
            VALUES (?, ?, ?, GETDATE())";
    $params = [$userId, $activityType, $description];

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("SQL Execution Failed: " . print_r(sqlsrv_errors(), true));
    }

    $successMessage = "User $username registered successfully! 🎉";
} else {
    $errorMessage = "Error: Session not found. Please register again.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            text-align: center;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
            width: 400px;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
            animation: slideIn 1s ease-in-out;
        }

        .message {
            font-size: 18px;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 20px;
            animation: fadeIn 1.2s ease-in-out;
        }

        .error {
            font-size: 18px;
            color: #d9534f;
            font-weight: bold;
            margin-bottom: 20px;
            animation: fadeIn 1.2s ease-in-out;
        }

        .btn {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .btn:hover {
            background: #218838;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
</head>
<body>

    <div class="container">
        <?php if (isset($successMessage)): ?>
            <h1>🎉 Success!</h1>
            <p class="message"><?php echo $successMessage; ?></p>
            <a href="login.php" class="btn">Go to Login</a>
        <?php else: ?>
            <h1>⚠️ Error</h1>
            <p class="error"><?php echo $errorMessage; ?></p>
            <a href="registration.php" class="btn" style="background: #d9534f;">Try Again</a>
        <?php endif; ?>
    </div>

</body>
</html>
