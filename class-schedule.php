<?php

// Start session to manage login status
session_start();

// Include database connection
include('db_connection.php');

// Fetch class schedule with trainer names
$sql = "SELECT * FROM class_schedule";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule - SFW The Gym</title>
    <link rel="stylesheet" href="class-schedule.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SFW The Gym</h2>
        <ul>
            <li><a href="admindash.php">Dashboard Overview</a></li>
            <li><a href="class-schedule.php" class="active">Class Schedule</a></li>
            <li><a href="admin-unavailability.php">Trainer Leave Request</a></li>
            <li><a href="accnouncment.php">Announcements</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Class Schedule</h1>
            <div class="admin-info">
                <span>Welcome</span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </header>

        <!-- Class Schedule Section -->
        <section class="schedule">
            <h2>Weekly Class Schedule</h2>
            <a href="add-class.php" class="add-class-btn">Add Classes</a>
            <?php if (sqlsrv_has_rows($stmt)) { ?>
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Class</th>
                            <th>Trainer</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['day']); ?></td>
                                <td><?php echo htmlspecialchars($row['time']); ?></td>
                                <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['trainer_name']); ?></td>
                                <td>
                                    <a href="edit-class.php?id=<?php echo $row['id']; ?>"><button>Edit</button></a>
                                    <a href="class-schedule-remove.php?id=<?php echo $row['id']; ?>"><button>Remove</button></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No classes are currently scheduled. Please add some classes.</p>
            <?php } ?>
        </section>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>

<?php
// Free resources and close the connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
