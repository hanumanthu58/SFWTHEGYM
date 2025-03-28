<?php
session_start();

include('db_connection.php'); // Include the centralized connection file

// Check if the trainer is logged in
if (!isset($_SESSION['tname'])) {
    echo "<script>alert('Please log in to view this page.'); window.location.href = 'logint.php';</script>";
    exit();
}

// Fetch announcements from the database
$sql = "SELECT title, description, created_at FROM announcements ORDER BY created_at DESC";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$announcements = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $announcements[] = $row;
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link rel="stylesheet" href="announcements.css">
</head>
<body>
    <div class="announcement-container">
        <h1>Announcements</h1>
        <?php if (!empty($announcements)): ?>
            <ul class="announcement-list">
                <?php foreach ($announcements as $announcement): ?>
                    <li class="announcement-item">
                        <h2><?= htmlspecialchars($announcement['title']); ?></h2>
                        <p><?= nl2br(htmlspecialchars($announcement['description'])); ?></p>
                        <small>
                            Posted on: <?= date("F j, Y, g:i a", strtotime($announcement['created_at']->format('Y-m-d H:i:s'))); ?>
                        </small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No announcements to display.</p>
        <?php endif; ?>
    </div>
    <div class="btd">
        <button onclick="goBack()">Back to Dashboard</button>
    </div>

    <script>
        function goBack() {
            window.location.href = 'trainerdash.php';
        }
    </script>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
