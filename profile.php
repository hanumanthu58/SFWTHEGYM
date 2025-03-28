<?php
// profile.php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in to view your profile.</p>";
    exit;
}
require_once 'db_connection.php';

$userId = $_SESSION['user_id'];

// Fetch user name from `regestration` table
$userQuery = "SELECT name FROM regestration WHERE userid = ?";
$userParams = array($userId);
$userStmt = sqlsrv_query($conn, $userQuery, $userParams);

if ($userStmt === false) {
    die("Error fetching user data: " . print_r(sqlsrv_errors(), true));
}
$user = sqlsrv_fetch_array($userStmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($userStmt); // Free the statement

// Fetch profile info from `user_profile` table
$profileQuery = "SELECT * FROM user_profile WHERE userid = ?";
$profileParams = array($userId);
$profileStmt = sqlsrv_query($conn, $profileQuery, $profileParams);

if ($profileStmt === false) {
    die("Error fetching profile data: " . print_r(sqlsrv_errors(), true));
}

$profile = sqlsrv_fetch_array($profileStmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($profileStmt); // Free the statement

// If profile not found, insert a new profile
if (!$profile) {
    $insertProfileQuery = "INSERT INTO user_profile (userid, name) VALUES (?, ?)";
    $insertParams = array($userId, $user['name']);
    $insertStmt = sqlsrv_query($conn, $insertProfileQuery, $insertParams);

    if ($insertStmt === false) {
        die("Error inserting default profile: " . print_r(sqlsrv_errors(), true));
    }
    sqlsrv_free_stmt($insertStmt); // Free the statement

    // Reload the profile data after insertion
    $profileStmt = sqlsrv_query($conn, $profileQuery, $profileParams);
    $profile = sqlsrv_fetch_array($profileStmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($profileStmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="profile-container">
        <?php include('profile_info.php'); ?>
        <div class="edit-profile-btn-container">
            <button class="edit-profile-btn" onclick="window.location.href='edit-profile.php'">Edit Profile</button>
        </div>
        <?php include('class_schedule.php'); ?>
        <?php include('Announcement.php'); ?>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
