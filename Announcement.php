<?php

// Include database connection
include('db_connection.php');

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
                            Posted on: 
                            <?= $announcement['created_at'] instanceof DateTime 
                                ? $announcement['created_at']->format('F j, Y, g:i a') 
                                : htmlspecialchars($announcement['created_at']); ?>
                        </small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No announcements to display.</p>
        <?php endif; ?>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
