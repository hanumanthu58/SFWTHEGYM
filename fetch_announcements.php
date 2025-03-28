<?php
// Include database connection
include('db_connection.php');

// Fetch announcements (Correct table name and SQL case sensitivity)
$sql = "SELECT title, message, created_at FROM announcements ORDER BY created_at DESC";
$query = sqlsrv_query($conn, $sql);

if ($query === false) {
    die(json_encode(["error" => "Failed to fetch announcements: " . print_r(sqlsrv_errors(), true)]));
}

$announcements = [];
while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
    $announcements[] = $row;
}

echo json_encode($announcements);

// Close the database connection
sqlsrv_free_stmt($query);
sqlsrv_close($conn);
?>
