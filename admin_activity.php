<?php

// Include database connection
include('db_connection.php');

// Fetch recent activities (using SQL Server syntax, removing LIMIT)
$query = "SELECT TOP 10 activity, activity_date FROM admin_activity ORDER BY activity_date DESC";
$result = sqlsrv_query($conn, $query);

if ($result === false) {
    die("Error fetching activities: " . print_r(sqlsrv_errors(), true));
}

// Example of logging registration activity in the admin_activity table
$user_id = $_SESSION['user_id'] ?? null; // Assuming user session is available

if ($user_id) {
    $log_registration_activity = "INSERT INTO admin_activity (user_id, action_type, activity_date) VALUES (?, 'user_registration', GETDATE())";
    $stmt = sqlsrv_query($conn, $log_registration_activity, [$user_id]);

    if (!$stmt) {
        echo "Error logging registration activity: " . print_r(sqlsrv_errors(), true);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Recent Activity</title>
    <link rel="stylesheet" href="admin-activity.css">
</head>

<body>
    <header>
        <h1>Admin - Recent Activity</h1>
    </header>
    
    <section>
        <h2>Recent Payments and Activities</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['activity']) . "</td>";
                    echo "<td>" . $row['activity_date']->format('Y-m-d H:i:s') . "</td>"; // Proper DateTime handling
                    echo "</tr>";
                }
                sqlsrv_free_stmt($result);
                ?>
            </tbody>
        </table>
    </section>

    <footer>
        <p>&copy; 2024 SFW The Gym. All Rights Reserved.</p>
    </footer>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>


</body>

</html>
