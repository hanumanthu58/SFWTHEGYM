<?php
// Include database connection
include('db_connection.php');

// Check if class ID is provided via the URL
if (isset($_GET['id'])) {
    $class_id = $_GET['id'];

    // Prepare the delete query
    $deleteQuery = "DELETE FROM class_schedule WHERE id = ?";
    $params = [$class_id];
    
    // Execute the delete query
    $deleteStmt = sqlsrv_query($conn, $deleteQuery, $params);

    if ($deleteStmt === false) {
        die("Error deleting class schedule: " . print_r(sqlsrv_errors(), true));
    }

    // Redirect after successful deletion
    header("Location: class-schedule.php"); // Redirect back to the class schedule page
    exit;
} else {
    die("No class ID provided in the URL.");
}

// Close the database connection
sqlsrv_close($conn);
?>
