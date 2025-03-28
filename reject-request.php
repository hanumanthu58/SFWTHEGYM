<?php
// Include database connection
include 'db_connection.php';

// Check if an ID is provided in the GET request
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Update the status of the request to 'rejected'
    $query = "UPDATE trainer_unavailability SET status = 'rejected' WHERE id = ?";
    $stmt = sqlsrv_query($conn, $query, array($id));

    if ($stmt) {
        $message = "Request rejected. Trainer will be notified.";
    } else {
        $message = "Error rejecting the request.";
    }
} else {
    $message = "Invalid request.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Request Action</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }
        .message {
            font-size: 20px;
            color: #333;
            padding: 20px;
            border: 1px solid #ccc;
            display: inline-block;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="message">
        <p><?php echo $message; ?></p>
    </div>
</body>
</html>
