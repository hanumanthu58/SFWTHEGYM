<?php
session_start();
include 'db_connection.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access! Please log in.";
    exit();
}

// Validate trainer name input
if (!isset($_GET['trainer_name'])) {
    echo "Invalid request! Trainer name is required.";
    exit();
}

$trainer_name = $_GET['trainer_name'];

// Fetch the phone number securely from the database
$sql = "SELECT contact FROM DashboardTrainerProfile WHERE name = ?";
$params = [$trainer_name];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false || !sqlsrv_has_rows($stmt)) {
    echo "Trainer not found!";
    exit();
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$trainerPhoneNumber = $row['contact'];

// Redirect to WhatsApp with secure parameters
$whatsappLink = 'https://wa.me/' . urlencode($trainerPhoneNumber) . '?text=Hi%20' . urlencode($trainer_name) . ',%20I%20would%20like%20to%20video%20call%20you.';
header("Location: $whatsappLink");
exit();
?>
