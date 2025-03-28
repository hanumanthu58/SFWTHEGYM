<?php


session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("You must be logged in as an admin to view this page.");
}

// Include database connection
include('db_connection.php');

// Handle accept or reject
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $request_id = $_GET['id'];
    $status = $action === 'accept' ? 'accepted' : 'rejected';

    // Update status in the database for the specific request
    $sql = "UPDATE trainer_unavailability SET status = ? WHERE id = ?";
    $params = [$status, $request_id];

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        echo "<script>alert('Error: " . htmlspecialchars(print_r(sqlsrv_errors(), true)) . "');</script>";
    } else {
        echo "<script>alert('Request $status successfully.'); window.location.href='admin-unavailability.php';</script>";
    }

    sqlsrv_free_stmt($stmt);
}

// Fetch all pending unavailability requests
$sql = "SELECT * FROM trainer_unavailability WHERE status = 'pending'";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Trainer Unavailability Requests</title>
    <link rel="stylesheet" href="admin-unavailability.css" />
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SFW The Gym Admin</h2>
        <ul>
            <li><a href="admindash.php">Dashboard</a></li>
            <li><a href="admin-unavailability.php" class="active">Trainer Unavailability</a></li>
            <li><a href="accnouncment.php">Announcements</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <!-- Content Section -->
    <div class="content">
        <h1>Trainer Unavailability Requests</h1>
        <table>
            <thead>
                <tr>
                    <th>Trainer ID</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Submitted At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['trainer_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <?php
                            // Safely format the DateTime
                            if ($row['created_at'] instanceof DateTime) {
                                echo $row['created_at']->format('Y-m-d H:i:s');
                            } else {
                                echo htmlspecialchars($row['created_at']);
                            }
                            ?>
                        </td>
                        <td>
                            <a href="?action=accept&id=<?php echo $row['id']; ?>">Accept</a> |
                            <a href="?action=reject&id=<?php echo $row['id']; ?>">Reject</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php
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
