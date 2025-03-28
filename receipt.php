<?php
session_start();
if (!isset($_GET['payment_id']) || !isset($_SESSION['user_id'])) {
    die("Invalid access!");
}
// Include database connection
include('db_connection.php');

// Fetch user details
$user_id = $_SESSION['user_id'];
$payment_id = htmlspecialchars($_GET['payment_id']);

// Validate user
$sql_user = "SELECT name, email, contact FROM regestration WHERE userid = ?";
$user_stmt = sqlsrv_query($conn, $sql_user, [$user_id]);

if ($user_stmt === false || !sqlsrv_fetch($user_stmt)) {
    die("User not found!");
}

$user_name = sqlsrv_get_field($user_stmt, 0);
$user_email = sqlsrv_get_field($user_stmt, 1);
$user_phone = sqlsrv_get_field($user_stmt, 2);

// Insert payment details
$amount = 500; // Change to dynamic if necessary
$status = "Completed";

$sql_insert = "INSERT INTO payment (user_id, amount, payment_date, status) VALUES (?, ?, GETDATE(), ?)";
$params_insert = [$user_id, $amount, $status];
$stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);

if ($stmt_insert === false) {
    die("Error inserting payment: " . print_r(sqlsrv_errors(), true));
}

// Fetch the payment record
$sql_payment = "SELECT amount, FORMAT(payment_date, 'yyyy-MM-dd HH:mm:ss') FROM payment WHERE user_id = ? ORDER BY id DESC";
$payment_stmt = sqlsrv_query($conn, $sql_payment, [$user_id]);

if ($payment_stmt === false || !sqlsrv_fetch($payment_stmt)) {
    die("Payment record not found!");
}

$amount_paid = sqlsrv_get_field($payment_stmt, 0);
$payment_date = sqlsrv_get_field($payment_stmt, 1);

// Close database connection
sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <link rel="stylesheet" href="receipt.css">
</head>
<body>
    <div class="receipt-container">
        <h1>Payment Receipt</h1>
        <p><strong>Payment ID:</strong> <?= $payment_id ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($user_name) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user_email) ?></p>
        <p><strong>Phone Number:</strong> <?= htmlspecialchars($user_phone) ?></p>
        <p><strong>Amount Paid:</strong> ₹<?= htmlspecialchars($amount_paid) ?></p>
        <p><strong>Payment Date:</strong> <?= htmlspecialchars($payment_date) ?></p>

        <button onclick="window.print()">Download Receipt</button>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
