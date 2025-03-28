<?php
// Include database connection
include('db_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class Schedule - SFW The Gym</title>
    <link rel="stylesheet" href="add-class.css">
</head>
<body>
    <div class="form-container">
        <h1>Add Class Schedule</h1>
        <form action="add-class.php" method="post">
            <label for="day">Day:</label>
            <select name="day" id="day" required>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
            <label for="id">ID:</label>
            <input type="text" name="id" id="id" placeholder="Enter ID" required>

            <label for="time">Time:</label>
            <input type="text" name="time" id="time" placeholder="e.g., 6:00 AM - 7:00 AM" required>

            <label for="class_name">Class Name:</label>
            <input type="text" name="class_name" id="class_name" placeholder="e.g., Yoga, HIIT" required>

            <label for="trainer_name">Trainer:</label>
            <select name="trainer_name" id="trainer_name" required>
                <option value="">Select Trainer</option>
                <?php
                // Fetch trainer names using the connection from db_connection.php
                if ($conn) {
                    $trainerQuery = "SELECT name FROM trainer";
                    $trainerResult = sqlsrv_query($conn, $trainerQuery);

                    while ($trainer = sqlsrv_fetch_array($trainerResult, SQLSRV_FETCH_ASSOC)) {
                        echo "<option value='{$trainer['name']}'>{$trainer['name']}</option>";
                    }

                    sqlsrv_free_stmt($trainerResult);
                } else {
                    echo "<option value=''>Error fetching trainers</option>";
                }
                ?>
            </select>

            <button type="submit">Add Class</button>
        </form>
    </div>
</body>
</html>

<?php
// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $day = $_POST['day'];
    $time = $_POST['time'];
    $class_name = $_POST['class_name'];
    $trainer_name = $_POST['trainer_name'];

    $sql = "INSERT INTO class_schedule (id, day, time, class_name, trainer_name) VALUES (?, ?, ?, ?, ?)";
    $params = [$id, $day, $time, $class_name, $trainer_name];

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        echo "<script>alert('Class added successfully!'); window.location.href='class-schedule.php';</script>";
    } else {
        echo "Error: " . print_r(sqlsrv_errors(), true);
    }

    sqlsrv_free_stmt($stmt);
}

sqlsrv_close($conn);
?>
