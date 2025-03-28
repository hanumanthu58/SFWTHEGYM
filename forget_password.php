<?php
session_start();

require_once 'db_connection.php';

// Handle Step 1: Verify Email or Phone
if (isset($_POST['verify'])) {
    $identifier = $_POST['identifier'];

    // Query the registration table for matching email or phone
    $query = "SELECT * FROM regestration WHERE email = ? OR contact = ?";
    $params = array($identifier, $identifier);
    $stmt = sqlsrv_query($conn, $query, $params);

    if (sqlsrv_has_rows($stmt)) {
        // User found, generate OTP
        $_SESSION['otp'] = rand(100000, 999999);  // Generate OTP
        $_SESSION['identifier'] = $identifier;    // Save identifier (email or phone)

        // Send OTP via email if identifier is an email
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $sendgrid_api_key = $_ENV['SENDGRID_API_KEY'];
            $template_id = $_ENV['TEMPLATE_ID'];
            $sender_email = $_ENV['SENDER_EMAIL'];
            
            $data = [
                "personalizations" => [
                    [
                        "to" => [
                            ["email" => $identifier]
                        ],
                        "dynamic_template_data" => [
                            "otp" => $_SESSION['otp']
                        ]
                    ]
                ],
                "from" => [
                    "email" => $sender_email
                ],
                "template_id" => $template_id
            ];

            $url = 'https://api.sendgrid.com/v3/mail/send';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $sendgrid_api_key,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($ch);

            if ($response === false) {
                error_log("cURL Error: " . curl_error($ch));
                echo "<script>alert('Error sending OTP to email.');</script>";
            } else {
                echo "<script>alert('OTP has been sent to your email.');</script>";
            }
            curl_close($ch);
        } else {
            echo "<script>alert('Invalid email address.');</script>";
        }
    } else {
        echo "<script>alert('Email or phone number not found!');</script>";
    }
}

// Handle Step 2: Validate OTP and Reset Password
if (isset($_POST['reset_password'])) {
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($_SESSION['otp'] == $otp) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $identifier = $_SESSION['identifier'];

            $query = "UPDATE regestration SET password = ? WHERE email = ? OR contact = ?";
            $params = array($hashed_password, $identifier, $identifier);
            $stmt = sqlsrv_query($conn, $query, $params);

            if ($stmt) {
                unset($_SESSION['otp'], $_SESSION['identifier']); // Clear session variables
                echo "<script>alert('Password reset successful!');</script>";
                echo "<script>window.location.href='login.php';</script>";
            } else {
                error_log("SQL Error: " . print_r(sqlsrv_errors(), true));
                echo "<script>alert('Error resetting password.');</script>";
            }
        } else {
            echo "<script>alert('Passwords do not match.');</script>";
        }
    } else {
        echo "<script>alert('Invalid OTP!');</script>";
    }
}

// Handle "Change Email" option
if (isset($_POST['change_email'])) {
    unset($_SESSION['otp'], $_SESSION['identifier']); // Reset session
    echo "<script>window.location.href='forget_password.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <link rel="stylesheet" href="forget_password.css">
</head>
<body>
    <header>
        <h1>Forget Password</h1>
    </header>
    <main>
        <div class="form-container">
            <!-- Step 1: Verify Email or Phone -->
            <?php if (!isset($_SESSION['otp'])): ?>
                <form action="forget_password.php" method="POST">
                    <label for="identifier">Enter your Email:</label>
                    <input type="text" id="identifier" name="identifier" required>
                    <button type="submit" name="verify">Send OTP</button>
                </form>
            <?php endif; ?>

            <!-- Step 2: Enter OTP and Reset Password -->
            <?php if (isset($_SESSION['otp'])): ?>
                <form action="forget_password.php" method="POST">
                    <label for="otp">Enter OTP:</label>
                    <input type="text" id="otp" name="otp" required>
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <button type="submit" name="reset_password">Reset Password</button>
                </form>

                <!-- "Change Email" Option -->
                <form action="forget_password.php" method="POST">
                    <button type="submit" name="change_email">Change Email</button>
                </form>
            <?php endif; ?>
        </div>
    </main>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
