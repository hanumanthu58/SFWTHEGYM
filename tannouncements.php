<?php
// Include database connection
include('db_connection.php');

// Fetch announcements
$sql = "SELECT title, message, created_at FROM Announcements ORDER BY created_at DESC";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die("Error fetching announcements: " . print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Announcements - Dashboard</title>
    <link rel="stylesheet" href="tannouncements.css">
</head>
<body>
    <!-- Sidebar Section -->
    <div class="sidebar">
        <h2>SFW The Gym</h2>
        <ul>
            <li><a href="trainerdash.php" class="active">Dashboard Overview</a></li>
            <li><a href="manage-profile.php">Manage Profile</a></li>
            <li><a href="clients.php">Clients</a></li>
            <li><a href="tclass-schedule.php">Class Schedule</a></li>
            <li><a href="tmessages.php">Messages</a></li>
            <li><a href="tearnings.php">Earnings</a></li>
            <li><a href="announcements.php">Announcements</a></li>
        </ul>
    </div>

    <!-- Main Content Section -->
    <div class="main-content">
        <header>
            <h1>Announcements</h1>
            <div class="trainer-info">
                <span>Welcome, Trainer</span>
                <a href="profile.html"><img src="gym image/male-user-image.webp" alt="Trainer Profile" class="profile-pic"></a>
                <a href="logint.html" class="logout-btn">Logout</a>
            </div>
        </header>

        <!-- Announcements Section -->
        <section class="announcement-list">
            <h2>Latest Announcements</h2>
            <ul id="announcements">
                <?php
                $hasAnnouncements = false;

                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $hasAnnouncements = true;
                    echo "<li>";
                    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                    echo "<p>" . nl2br(htmlspecialchars($row['message'])) . "</p>";
                    
                    if ($row['created_at'] instanceof DateTime) {
                        echo "<small>Posted on: " . $row['created_at']->format('F j, Y, g:i a') . "</small>";
                    } else {
                        echo "<small>Posted on: " . htmlspecialchars($row['created_at']) . "</small>";
                    }
                    echo "</li>";
                }

                if (!$hasAnnouncements) {
                    echo "<p>No announcements to display.</p>";
                }

                sqlsrv_free_stmt($stmt);
                ?>
            </ul>
        </section>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
