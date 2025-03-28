php<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Clients - Trainer Dashboard</title>
    <link rel="stylesheet" href="clients.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SFW The Gym</h2>
        <ul>
            <li><a href="trainerdash.php" class="active">Dashboard Overview</a></li>
            <li><a href="manage-profile.php">Manage Profile</a></li>
            <li><a href="cilents.php">Clients</a></li>
            <li><a href="tclass-schedule.php">Class Schedule</a></li>
            <li><a href="tmessages.php">Messages</a></li>
            <li><a href="tearnings.php">Earnings</a></li>
            <li><a href="tannouncements.php">Announcements</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>My Clients</h1>
            <div class="trainer-info">
                <span>Welcome, <?php echo htmlspecialchars($profileDetails['Name']); ?></span>
                <a href="logint.php" class="logout-btn">Logout</a>
            </div>
        </header>

        <!-- Clients List -->
        <section class="clients-list">
            <h2>Assigned Clients</h2>
            <ul id="clients">
                <li>
                    <div class="client-info">
                        <h3>Client Name 1</h3>
                        <p>Goal: Weight Loss</p>
                        <p>Last Session: 2024-10-18</p>
                        <a href="client-profile.html" class="view-profile-btn">View Profile</a>
                    </div>
                </li>
                <li>
                    <div class="client-info">
                        <h3>Client Name 2</h3>
                        <p>Goal: Muscle Gain</p>
                        <p>Last Session: 2024-10-15</p>
                        <a href="client-profile.html" class="view-profile-btn">View Profile</a>
                    </div>
                </li>
                <li>
                    <div class="client-info">
                        <h3>Client Name 3</h3>
                        <p>Goal: Endurance Training</p>
                        <p>Last Session: 2024-10-12</p>
                        <a href="client-profile.html" class="view-profile-btn">View Profile</a>
                    </div>
                </li>
                <!-- Add more clients as needed -->
            </ul>
        </section>
    </div>
</body>
</html>
