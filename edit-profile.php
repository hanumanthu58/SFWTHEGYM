<?php
session_start();

include("db_connection.php"); // Include your database connection file

// Ensure that the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Initialize default values
$user = [
    'name' => '',
    'bio' => '',
    'age' => '',
    'height' => '',
    'weight' => '',
    'profile_pic' => 'default-profile.png'
];

// Fetch user data from `user_profile` table
$sql = "SELECT * FROM user_profile WHERE userid = ?";
$params = array($userId);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt); // Free memory

// If data exists, populate $user
if ($result) {
    $user = array_merge($user, $result);
}

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];

    // Handle profile picture upload
    $profilePic = $_FILES['profile-pic']['name'];
    if ($profilePic) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($profilePic);
        move_uploaded_file($_FILES['profile-pic']['tmp_name'], $targetFile);
    } else {
        $targetFile = $user['profile_pic']; // Keep existing picture
    }

    // Check if profile exists
    if ($result) {
        // Update user profile
        $sql = "UPDATE user_profile SET name = ?, bio = ?, age = ?, height = ?, weight = ?, profile_pic = ? WHERE userid = ?";
        $params = array($name, $bio, $age, $height, $weight, $targetFile, $userId);
    } else {
        // Insert new user profile
        $sql = "INSERT INTO user_profile (userid, name, bio, age, height, weight, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = array($userId, $name, $bio, $age, $height, $weight, $targetFile);
    }

    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    sqlsrv_free_stmt($stmt);
    $_SESSION['profile_pic'] = $targetFile; // Update session profile pic
    header("Location: profile.php");
    exit();
}
?>

<!-- HTML Code for Edit Profile Form -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="edit-profile.css">
</head>

<body>
    <div class="edit-profile-container">
        <h1>Edit Profile</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea name="bio" id="bio" rows="4"><?php echo htmlspecialchars($user['bio']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" name="age" id="age" value="<?php echo htmlspecialchars($user['age']); ?>" required>
            </div>

            <div class="form-group">
                <label for="height">Height (cm):</label>
                <input type="number" name="height" id="height" value="<?php echo htmlspecialchars($user['height']); ?>" required>
            </div>

            <div class="form-group">
                <label for="weight">Weight (kg):</label>
                <input type="number" name="weight" id="weight" value="<?php echo htmlspecialchars($user['weight']); ?>" required>
            </div>

            <div class="form-group">
                <label for="profile-pic">Profile Picture:</label>
                <input type="file" name="profile-pic" id="profile-pic">
                <div class="profile-pic-preview-container">
                    <img src="<?php echo $user['profile_pic']; ?>" alt="Profile Picture" class="profile-pic-preview">
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn">Save Changes</button>
            </div>
        </form>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>

</html>
