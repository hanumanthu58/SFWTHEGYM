<?php
session_start();
include('db_connection.php'); // Use centralized database connection

// Check if the trainer is logged in
if (!isset($_SESSION['tname'])) {
    echo "<script>alert('Please log in to view this page.'); window.location.href = 'logint.php';</script>";
    exit();
}

// Fetch the trainer's username from session
$trainer_username = $_SESSION['tname'];

// Fetch the trainer's actual name from the 'trainer' table using the username
$sqlFetchName = "SELECT name FROM trainer WHERE username = ?";
$stmtFetchName = sqlsrv_query($conn, $sqlFetchName, [$trainer_username]);

if ($stmtFetchName === false || !sqlsrv_has_rows($stmtFetchName)) {
    echo "<script>alert('Trainer not found.'); window.location.href = 'logint.php';</script>";
    exit();
}

$trainerData = sqlsrv_fetch_array($stmtFetchName, SQLSRV_FETCH_ASSOC);
$trainer_name = $trainerData['name'];
sqlsrv_free_stmt($stmtFetchName);

// Query to fetch classes for the specific trainer by name
$sql = "SELECT day, time, class_name, trainer_name 
        FROM class_schedule 
        WHERE trainer_name = ?";
$params = [$trainer_name];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Class Schedule - Trainer Dashboard</title>
    <link rel="stylesheet" href="tclass-schedule.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SFW The Gym</h2>
        <ul>
            <li><a href="trainerdash.php">Dashboard Overview</a></li>
            <li><a href="manage-profile.php">Manage Profile</a></li>
            <li><a href="tclass-schedule.php" class="active">Class Schedule</a></li>
            <li><a href="announcements.php">Announcements</a></li>
            <li><a href="trainer-unavailability.php">For Leave</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1><?php echo htmlspecialchars($trainer_name); ?>, Your Class Schedule</h1>
            <div class="trainer-info">
                <a href="logint.php" class="logout-btn">Logout</a>
            </div>
        </header>

        <!-- Class Schedule Table -->
        <section class="class-schedule">
            <h2>Upcoming Classes</h2>
            <table>
                <caption>List of Upcoming Classes</caption>
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Class</th>
                        <th>Trainer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if any classes are available for the trainer
                    if (sqlsrv_has_rows($stmt)) {
                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['day']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['class_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['trainer_name']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No classes found for your schedule.</td></tr>";
                    }
                    ?>
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
<?php
// Free statement and close connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
