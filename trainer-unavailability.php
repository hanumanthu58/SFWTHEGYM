<?php
session_start();
if (!isset($_SESSION['tid'])) {
    die("Please log in to submit your unavailability notice.");
}

$trainerid = $_SESSION['tid']; // Retrieve trainer ID from the session

// Include database connection
include('db_connection.php');

$status = "No request found"; // Default status

// Fetch the most recent status for the trainer
$sql = "SELECT TOP 1 status FROM trainer_unavailability WHERE trainer_id = ? ORDER BY id DESC";
$params = [$trainerid];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $status = htmlspecialchars($row['status']);
}
sqlsrv_free_stmt($stmt);

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = htmlspecialchars($_POST['message']);
    $sql = "INSERT INTO trainer_unavailability (trainer_id, message, status) VALUES (?, ?, 'pending')";
    $params = [$trainerid, $message];

    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    echo "<script>alert('Unavailability request submitted successfully!'); window.location.href='trainer-unavailability.php';</script>";
    sqlsrv_free_stmt($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Unavailability</title>
    <link rel="stylesheet" href="trainer-unavailability.css">
</head>

<body>
    <!-- Sidebar Section -->
    <div class="sidebar">
        <h2>SFW The Gym</h2>
        <ul>
            <li><a href="trainerdash.php" class="active">Dashboard Overview</a></li>
            <li><a href="manage-profile.php">Manage Profile</a></li>
            <li><a href="tclass-schedule.php">Class Schedule</a></li>
            <li><a href="announcements.php">Announcements</a></li>
            <li><a href="trainer-unavailability.php">For Leave</a></li>
        </ul>
    </div>

    <!-- Main Content Section -->
    <div class="content">
        <h1>Submit Unavailability or Health Issue</h1>
        <form method="POST">
            <label for="message">Message to Admin:</label>
            <textarea name="message" required></textarea>
            <button type="submit">Submit</button>
        </form>

        <!-- Status Section -->
        <h2>Your Request Status</h2>
        <p>Status: <?= $status; ?></p>

        <?php
        // Fetch all accepted or rejected requests
        $sql = "SELECT id, status, message, created_at FROM trainer_unavailability WHERE trainer_id = ? AND status IN ('accepted', 'rejected') ORDER BY created_at DESC";
        $params = [$trainerid];
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_has_rows($stmt)) {
            echo "<h3>Previous Requests</h3>";
            echo "<table border='1'>
                    <tr>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                    </tr>";

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $status = htmlspecialchars($row['status']);
                $message = htmlspecialchars($row['message']);
                $submitted_at = $row['created_at']->format('Y-m-d H:i:s');

                echo "<tr>
                        <td>$message</td>
                        <td>$status</td>
                        <td>$submitted_at</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No accepted or rejected requests found.</p>";
        }
        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);
        ?>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>

</html>
