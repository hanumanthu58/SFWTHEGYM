<?php
session_start();

include('db_connection.php');

// Ensure the trainer is logged in
if (!isset($_SESSION['tname'])) {
    echo "<script>alert('Please log in to manage your profile.'); window.location.href = 'logint.php';</script>";
    exit();
}

$trainerName = $_SESSION['tname'];
$profileData = [];

// Fetch profile data
$sqlFetch = "SELECT * FROM DashboardTrainerProfile WHERE Name = ?";
$paramsFetch = [$trainerName];
$stmtFetch = sqlsrv_query($conn, $sqlFetch, $paramsFetch);

if ($stmtFetch === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($stmtFetch)) {
    $profileData = sqlsrv_fetch_array($stmtFetch, SQLSRV_FETCH_ASSOC);
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $specialty = $_POST['specialty'];
    $bio = $_POST['bio'];
    $profilePic = $profileData['ProfilePic'] ?? '';

    // Handle profile picture upload
    if (!empty($_FILES['profile_pic']['name'])) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES['profile_pic']['name']);
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
            $profilePic = $targetFile;
        }
    }

    // Check if profile exists
    if ($profileData) {
        $sqlUpdate = "UPDATE DashboardTrainerProfile SET Email = ?, Contact = ?, Specialties = ?, Bio = ?, ProfilePic = ? WHERE Name = ?";
        $paramsUpdate = [$email, $phone, $specialty, $bio, $profilePic, $trainerName];
        $stmtUpdate = sqlsrv_query($conn, $sqlUpdate, $paramsUpdate);

        if ($stmtUpdate === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    } else {
        $sqlInsert = "INSERT INTO DashboardTrainerProfile (Name, Email, Contact, Specialties, Bio, ProfilePic) VALUES (?, ?, ?, ?, ?, ?)";
        $paramsInsert = [$trainerName, $email, $phone, $specialty, $bio, $profilePic];
        $stmtInsert = sqlsrv_query($conn, $sqlInsert, $paramsInsert);

        if ($stmtInsert === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    echo "<script>alert('Profile updated successfully!'); window.location.href = 'manage-profile.php';</script>";
    exit();
}

// Close connection
sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Profile</title>
    <link rel="stylesheet" href="manage-profile.css" />
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SFW The Gym</h2>
        <ul>
            <li><a href="trainerdash.php">Dashboard Overview</a></li>
            <li><a href="manage-profile.php" class="active">Manage Profile</a></li>
            <li><a href="tclass-schedule.php">Class Schedule</a></li>
            <li><a href="announcements.php">Announcements</a></li>
            <li><a href="trainer-unavailability.php">For Leave</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Manage Profile</h1>
        <form class="profile-form" method="POST" action="manage-profile.php" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($trainerName); ?>" readonly />

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($profileData['Email'] ?? ''); ?>" required />

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($profileData['Contact'] ?? ''); ?>" required />

            <label for="specialty">Specialties:</label>
            <input type="text" id="specialty" name="specialty" value="<?= htmlspecialchars($profileData['Specialties'] ?? ''); ?>" required />

            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio" required><?= htmlspecialchars($profileData['Bio'] ?? ''); ?></textarea>

            <label for="profile_pic">Profile Picture:</label>
            <input type="file" id="profile_pic" name="profile_pic" accept="image/*" />
            
            <?php if (!empty($profileData['ProfilePic'])): ?>
                <p>Current Picture:</p>
                <img src="<?= htmlspecialchars($profileData['ProfilePic']); ?>" alt="Profile Picture" width="100" />
            <?php endif; ?>

            <button type="submit">Update Profile</button>
        </form>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
