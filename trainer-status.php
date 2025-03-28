<?php
session_start();
if (!isset($_SESSION['tname'])) {
    echo "<p>Please log in to view your request status.</p>";
    exit;
}

// Include database connection
include('db_connection.php');

// Validate trainer ID from session
if (!isset($_SESSION['trainer_id'])) {
    echo "<p>Error: Trainer ID not found. Please log in again.</p>";
    exit;
}

$trainer_id = $_SESSION['trainer_id'];
$query = "SELECT * FROM trainer_unavailability WHERE trainer_id = ? ORDER BY created_at DESC";
$stmt = sqlsrv_query($conn, $query, [$trainer_id]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Trainer Unavailability Status</title>
    <link rel="stylesheet" href="trainer_status.css" />
</head>
<body>
    <h1>Unavailability Request Status</h1>

    <?php
    if ($stmt === false) {
        echo "<p>Error fetching requests: " . print_r(sqlsrv_errors(), true) . "</p>";
    } elseif (sqlsrv_has_rows($stmt)) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<div class='request'>";
            echo "<p><strong>Message:</strong> " . nl2br(htmlspecialchars($row['message'])) . "</p>";
            echo "<p><strong>Status:</strong> " . ucfirst(htmlspecialchars($row['status'])) . "</p>";
            echo "<p><strong>Requested on:</strong> " . ($row['created_at'] instanceof DateTime 
                ? $row['created_at']->format('Y-m-d H:i:s') 
                : htmlspecialchars($row['created_at'])) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No unavailability requests found.</p>";
    }

    // Clean up
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    ?>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
