<?php


session_start();
include 'db_connection.php'; // Using db_connection.php for database connection

if (!$conn) {
    die("Database connection failed: " . print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Admin Dashboard</title>
    <link rel="stylesheet" href="accnouncment.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SFW The Gym</h2>
        <ul>
            <li><a href="admindash.php">Dashboard Overview</a></li>
            <li><a href="class-schedule.php">Class Schedule</a></li>
            <li><a href="admin-unavailability.php">Trainer Leave Request</a></li>
            <li><a href="accnouncment.php" class="active">Announcements</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Announcements</h1>
            <div class="admin-info">
                <span>Welcome</span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </header>

        <!-- Announcement Form -->
        <section class="announcement-form">
            <h2>Create New Announcement</h2>
            <form id="announcement-form" method="POST" action="create_announcement.php">
                <label for="announcement-title">Title:</label>
                <input type="text" id="announcement-title" name="title" required>

                <label for="announcement-message">Message:</label>
                <textarea id="announcement-message" name="message" rows="5" required></textarea>

                <button type="submit">Post Announcement</button>
            </form>
        </section>

        <!-- Existing Announcements -->
        <section class="announcement-list">
            <h2>Existing Announcements</h2>
            <ul id="announcements">
            <?php
            // Query to fetch data from the Announcements table
            $query = "SELECT * FROM Announcements"; 

            // Execute the query
            $stmt = sqlsrv_query($conn, $query);

            if ($stmt === false) {
                die("Query failed: " . print_r(sqlsrv_errors(), true));
            }

            // Fetch and display announcements
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<li>";
                echo "<strong>Title:</strong> " . htmlspecialchars($row['title']) . "<br>";
                echo "<strong>Description:</strong> " . htmlspecialchars($row['description']) . "<br>";

                if ($row['created_at'] instanceof DateTime) {
                    echo "<strong>Created At:</strong> " . $row['created_at']->format('Y-m-d H:i:s') . "<br>";
                } else {
                    echo "<strong>Created At:</strong> " . htmlspecialchars($row['created_at']) . "<br>";
                }

                echo "<hr>";
                echo "</li>";
            }

            // Free resources
            sqlsrv_free_stmt($stmt);
            ?>
            </ul>
        </section>
    </div>
   
    <script src="announcements.js"></script>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
