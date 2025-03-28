<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Registration</title>
    <link rel="stylesheet" href="admin_registration.css">
</head>
<body>
    <div class="container">
        <h1>Trainer Registration</h1>
        <form action="trainer_registration.php" method="post">
            <div class="form-group">
                <label for="adminId">Trainer ID</label>
                <input type="text" id="trainerId" name="trainerId" placeholder="Enter Trainer ID" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter Username" required>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter name" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Password" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter Email" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact Number</label>
                <input type="text" id="contact" name="contact" placeholder="Enter Contact Number" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Register</button>
            </div>
        </form>
        <div class="footer-links">
            <a href="logint.php" id="trainer">Trainer as Admin</a>
        </div>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
<?php
// Include database connection
include('db_connection.php');

session_start(); // Start session

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Get form data
        $trainerId = $_POST['trainerId'];
        $username = $_POST['username'];
        $name = $_POST['name'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
        $email = $_POST['email'];
        $contact = $_POST['contact'];

        // Insert data into trainer table
        $sql = "INSERT INTO trainer (trainer_id, username, name, password, email, contact) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$trainerId, $username, $name, $password, $email, $contact]);

        // Log registration into recent_activity table
        $activityType = "Trainer Registration";
        $description = "Trainer $name has been registered successfully.";
        $sqlActivity = "INSERT INTO recent_activity (user_id, action_type, description, timestamp) VALUES (?, ?, ?, GETDATE())";
        $stmtActivity = $conn->prepare($sqlActivity);
        $stmtActivity->execute([$trainerId, $activityType, $description]);

        $_SESSION['name'] = $name; // Store trainer name in session

        // Redirect to the success page
        echo "<script>alert('Trainer registered successfully!'); window.location.href='trainer_register.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>