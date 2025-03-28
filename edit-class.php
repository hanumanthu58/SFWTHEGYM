<?php

// Include database connection
include('db_connection.php');

// Initialize variables
$message = '';
$class = [
    'id' => '',
    'day' => '',
    'time' => '',
    'class_name' => '',
    'trainer_name' => ''
];

// Handle POST request to update the class schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $day = $_POST['day'] ?? '';
    $time = $_POST['time'] ?? '';
    $class_name = $_POST['class_name'] ?? '';
    $trainer_name = $_POST['trainer_name'] ?? '';

    if (!$id || !$day || !$time || !$class_name || !$trainer_name) {
        $message = "All fields are required.";
    } else {
        $updateQuery = "UPDATE class_schedule SET day = ?, time = ?, class_name = ?, trainer_name = ? WHERE id = ?";
        $params = [$day, $time, $class_name, $trainer_name, $id];
        $updateStmt = sqlsrv_query($conn, $updateQuery, $params);

        if ($updateStmt === false) {
            die("Error updating class schedule: " . print_r(sqlsrv_errors(), true));
        }

        $message = "Class schedule updated successfully.";
    }
}

// Handle GET request to fetch class details
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $class_id = $_GET['id'];
    $classQuery = "SELECT * FROM class_schedule WHERE id = ?";
    $classStmt = sqlsrv_query($conn, $classQuery, [$class_id]);

    if ($classStmt === false) {
        die("Error fetching class details: " . print_r(sqlsrv_errors(), true));
    }

    $class = sqlsrv_fetch_array($classStmt, SQLSRV_FETCH_ASSOC);
    if (!$class) {
        die("Class not found or invalid ID provided.");
    }
}

// Fetch trainer names
$trainerQuery = "SELECT name FROM trainer";
$trainerStmt = sqlsrv_query($conn, $trainerQuery);

if ($trainerStmt === false) {
    die("Error fetching trainer names: " . print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class Schedule</title>
    <link rel="stylesheet" href="class-schedule.css">
</head>
<body>
    <div class="container">
        <h1>Edit Class Schedule</h1>

        <?php if ($message): ?>
            <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="edit-class.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($class['id'] ?? ''); ?>">

            <label for="day">Day:</label>
            <select name="day" id="day" required>
                <?php
                $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                foreach ($days as $dayOption) {
                    $selected = ($class['day'] === $dayOption) ? 'selected' : '';
                    echo "<option value='$dayOption' $selected>$dayOption</option>";
                }
                ?>
            </select>

            <label for="time">Time:</label>
            <input type="text" name="time" id="time" value="<?php echo htmlspecialchars($class['time'] ?? ''); ?>" required>

            <label for="class_name">Class Name:</label>
            <input type="text" name="class_name" id="class_name" value="<?php echo htmlspecialchars($class['class_name'] ?? ''); ?>" required>

            <label for="trainer_name">Trainer:</label>
            <select name="trainer_name" id="trainer_name" required>
                <?php while ($trainer = sqlsrv_fetch_array($trainerStmt, SQLSRV_FETCH_ASSOC)) { ?>
                    <option value="<?php echo htmlspecialchars($trainer['name']); ?>" 
                        <?php echo ($class['trainer_name'] === $trainer['name']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($trainer['name']); ?>
                    </option>
                <?php } ?>
            </select>

            <button type="submit">Update</button>
        </form>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>

<?php
// Free resources
if (isset($classStmt)) {
    sqlsrv_free_stmt($classStmt);
}
sqlsrv_free_stmt($trainerStmt);
sqlsrv_close($conn);
?>
