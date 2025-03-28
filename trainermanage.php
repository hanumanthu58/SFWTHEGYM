<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Trainers - SFW The Gym</title>
    <link rel="stylesheet" href="trainermanage.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SFW The Gym</h2>
        <ul>
            <li><a href="admindash.php">Dashboard Overview</a></li>
            <li><a href="trainermanage.php">Manage Trainers</a></li>
            <li><a href="tclass-schedule.php">Class Schedule</a></li>
            <li><a href="payment.php">Payment Reports</a></li>
            <li><a href="accnouncment.php">Announcements</a></li>
            
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Manage Trainers</h1>
            <div class="admin-info">
                <span>Welcome, Admin</span>
                <a href="profile.html">
                    <img src="gym image\male-user-image.webp" alt="Admin Profile" class="profile-pic">
                </a>
                <a href="logina.html" class="logout-btn">Logout</a>
            </div>
        </header>

        <!-- Trainer Management Section -->
        <section class="trainer-management">
            <h2>Trainer List</h2>

            <!-- Add Trainer Button -->
            <button class="add-trainer-btn">+ Add New Trainer</button>

            <!-- Trainer Table -->
            <table>
                <thead>
                    <tr>
                        <th>Trainer Name</th>
                        <th>Specialization</th>
                        <th>Experience (Years)</th>
                        <th>Availability</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="trainer-table-body">
                    <tr>
                        <td>John Doe</td>
                        <td>Strength Training</td>
                        <td>5</td>
                        <td>Full-Time</td>
                        <td>
                            <button class="edit-btn">Edit</button>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>Yoga & Pilates</td>
                        <td>3</td>
                        <td>Part-Time</td>
                        <td>
                            <button class="edit-btn">Edit</button>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
