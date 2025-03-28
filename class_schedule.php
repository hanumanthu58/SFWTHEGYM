<?php

// Include database connection
include('db_connection.php');

try {
    // Query to fetch class schedule
    $query = "SELECT day, time, class_name, trainer_name FROM class_schedule";
    $stmt = sqlsrv_query($conn, $query);

    if ($stmt === false) {
        throw new Exception("Query failed: " . print_r(sqlsrv_errors(), true));
    }

    $classes = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $classes[] = $row;
    }

    sqlsrv_free_stmt($stmt);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Class Schedule</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="class-schedule-container">
        <h1>Class Schedule</h1>

        <?php if ($classes): ?>
            <ul>
                <?php foreach ($classes as $class): ?>
                    <li>
                        <strong><?= htmlspecialchars($class['day']) ?>:</strong>
                        <?= htmlspecialchars($class['class_name']) ?> - <?= htmlspecialchars($class['time']) ?>
                        (Trainer: <?= htmlspecialchars($class['trainer_name']) ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No classes scheduled.</p>
        <?php endif; ?>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
