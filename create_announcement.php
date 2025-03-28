<?php
session_start();

include 'db_connection.php'; // Using the new connection file

if (!$conn) {
    die("Database connection failed: " . print_r(sqlsrv_errors(), true));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $message = $_POST['message'];

    // Insert into the database
    $query = "INSERT INTO Announcements (title, description, created_at) VALUES (?, ?, GETDATE())";
    $params = array($title, $message);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die("Error inserting announcement: " . print_r(sqlsrv_errors(), true));
    }

    // Redirect back to the announcements page
    header("Location: accnouncment.php");
    exit;
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
    <div class="sidebar">
        <h2>SFW The Gym</h2>
        <ul>
            <li><a href="admindash.php">Dashboard Overview</a></li>
            <li><a href="class-schedule.php">Class Schedule</a></li>
            <li><a href="payment.php">Payment Reports</a></li>
            <li><a href="accnouncment.php" class="active">Announcements</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h1>Announcements</h1>
            <div class="admin-info">
                <span>Welcome, Admin</span>
                <a href="profile.html"><img src="gym image/male-user-image.webp" alt="Admin Profile" class="profile-pic"></a>
                <a href="logina.php" class="logout-btn">Logout</a>
            </div>
        </header>

        <section class="announcement-form">
            <h2>Create New Announcement</h2>
            <form method="POST" action="create_announcement.php">
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
                $sql = "SELECT title, description FROM Announcements ORDER BY created_at DESC";
                $stmt = sqlsrv_query($conn, $sql);

                if ($stmt === false) {
                    echo "<li>Error fetching announcements: " . print_r(sqlsrv_errors(), true) . "</li>";
                } else {
                    $hasAnnouncements = false;

                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        $hasAnnouncements = true;
                        echo "<li>
                                <h3>" . htmlspecialchars($row['title']) . "</h3>
                                <p>" . htmlspecialchars($row['description']) . "</p>
                              </li>";
                    }

                    if (!$hasAnnouncements) {
                        echo "<li>No announcements available at the moment.</li>";
                    }
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

    <script src="announcements.js"></script>
</body>
</html>
