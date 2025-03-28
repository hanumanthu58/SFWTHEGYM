<?php

// Include database connection
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $adminId = $_POST['adminId'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $email = $_POST['email'];
    $contact = $_POST['contact'];

    // SQL query to insert data
    $sql = "INSERT INTO admin (admin_id, username, name, password, email, contact) VALUES (?, ?, ?, ?, ?, ?)";
    $params = array($adminId, $username, $name, $password, $email, $contact);

    // Execute the query
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        // Output SQL error
        echo "<script>alert('Error registering admin: " . htmlspecialchars(print_r(sqlsrv_errors(), true)) . "');</script>";
    } else {
        echo "<script>alert('Admin registered successfully!'); window.location.href='admin_registration.php';</script>";
    }

    // Free statement and close connection
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Registration</title>
    <link rel="stylesheet" href="admin_registration.css" />
</head>
<body>
    <div class="container">
        <h1>Admin Registration</h1>
        <form action="admin_registration.php" method="post">
            <div class="form-group">
                <label for="adminId">Admin ID</label>
                <input type="text" id="adminId" name="adminId" placeholder="Enter Admin ID" required />
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter Username" required />
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter Name" required />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Password" required />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter Email" required />
            </div>
            <div class="form-group">
                <label for="contact">Contact Number</label>
                <input type="text" id="contact" name="contact" placeholder="Enter Contact Number" required />
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Register</button>
            </div>
        </form>
        <div class="footer-links">
            <a href="logina.php" id="admin">Login as Admin</a>
        </div>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
