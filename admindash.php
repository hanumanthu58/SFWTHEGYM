<?php
// Include database connection
include('db_connection.php');

try {
    // Total Members Query
    $total_members_query = "SELECT COUNT(*) AS total_members FROM regestration"; // Correct table name
    $total_members_stmt = sqlsrv_query($conn, $total_members_query);
    $total_members_row = sqlsrv_fetch_array($total_members_stmt, SQLSRV_FETCH_ASSOC);
    $total_members = $total_members_row['total_members'];

    // Total Trainers Query
    $total_trainers_query = "SELECT COUNT(*) AS total_trainers FROM trainer";
    $total_trainers_stmt = sqlsrv_query($conn, $total_trainers_query);
    $total_trainers_row = sqlsrv_fetch_array($total_trainers_stmt, SQLSRV_FETCH_ASSOC);
    $total_trainers = $total_trainers_row['total_trainers'];

    // Recent Activities Query
    $recent_activities_query = "SELECT action_type, description, timestamp FROM recent_activity ORDER BY timestamp DESC";
    $recent_activities_stmt = sqlsrv_query($conn, $recent_activities_query);
    $recent_activities = [];
    
    while ($row = sqlsrv_fetch_array($recent_activities_stmt, SQLSRV_FETCH_ASSOC)) {
        $recent_activities[] = $row;
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    $total_members = 0;
    $total_trainers = 0;
    $recent_activities = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SFW The Gym</title>
    <link rel="stylesheet" href="admindash.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SFW The Gym</h2>
        <ul>
            <li><a href="admindash.php">Dashboard Overview</a></li>
            <li><a href="class-schedule.php">Class Schedule</a></li>
            <li><a href="admin-unavailability.php">Trainer Leave Request</a></li>
            <li><a href="accnouncment.php">Announcements</a></li> 
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Admin Dashboard</h1>
            <div class="admin-info">
                <span>Welcome, Admin</span>
                <a href="logina.php" class="logout-btn">Logout</a>
            </div>
        </header>

        <!-- Overview Cards with Dynamic Data -->
        <div class="overview-cards">
            <div class="card animated-card" id="active-members">
                <div>
                    <h2>Total Members</h2>
                    <p><?php echo htmlspecialchars($total_members); ?></p>
                </div>   
                <div>
                    <h2>Total Trainers</h2>
                    <p><?php echo htmlspecialchars($total_trainers); ?></p>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <section class="recent-activities">
            <h2>New Joinings</h2>
            <ul id="recent-activities-list">
                <?php
                    // Display recent activities if available
                    if (!empty($recent_activities)) {
                        foreach ($recent_activities as $activity) {
                            // Convert timestamp to DateTime and format it
                            if ($activity['timestamp'] instanceof DateTime) {
                                $formattedDate = $activity['timestamp']->format('Y-m-d H:i:s');
                            } else {
                                $formattedDate = htmlspecialchars($activity['timestamp']);
                            }
                            echo "<li><strong>" . htmlspecialchars($activity['action_type']) . ":</strong> " 
                            . htmlspecialchars($activity['description']) . " - <em>" . $formattedDate . "</em></li>";
                        }
                    } else {
                        echo "<li>No recent activities found.</li>";
                    }
                ?>
            </ul>
        </section>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

    <script src="admindash.js"></script> <!-- Link to JavaScript -->
</body>
</html>
