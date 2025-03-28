<?php
// Include the database connection
include 'db_connection.php';

// Admin action on the request
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($conn) {
        // Update the status of the request to 'accepted'
        $query = "UPDATE trainer_unavailability SET status = 'accepted' WHERE id = ?";
        $stmt = sqlsrv_query($conn, $query, array($id));

        if ($stmt) {
            // Inform the trainer (email or message)
            echo "<p>Request accepted. Trainer will be notified.</p>";
            // Optionally send an email or notification here
        } else {
            echo "<p>Error accepting the request.</p>";
            error_log(print_r(sqlsrv_errors(), true));
        }
    } else {
        echo "<p>Database connection failed.</p>";
    }
}
?>
