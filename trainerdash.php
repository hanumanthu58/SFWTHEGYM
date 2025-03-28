<?php
session_start();

include('db_connection.php');

if (!isset($_SESSION['tname'])) {
    echo "<script>alert('Please login to access this page.'); window.location.href = 'logint.php';</script>";
    exit();
}

$username = $_SESSION['tname']; // Trainer's name from session

// Fetch contact and email from trainer table
$sqlTrainer = "SELECT Contact, Email FROM trainer WHERE Name = ?";
$stmtTrainer = sqlsrv_query($conn, $sqlTrainer, [$username]);

if ($stmtTrainer === false) {
    die("Error fetching trainer details: " . print_r(sqlsrv_errors(), true));
}

$trainerDetails = sqlsrv_fetch_array($stmtTrainer, SQLSRV_FETCH_ASSOC);

// Fetch or insert data into DashboardTrainerProfile
$sqlProfile = "SELECT * FROM DashboardTrainerProfile WHERE Name = ?";
$stmtProfile = sqlsrv_query($conn, $sqlProfile, [$username]);

if ($stmtProfile === false) {
    die("Error fetching profile details: " . print_r(sqlsrv_errors(), true));
}

$profileDetails = sqlsrv_fetch_array($stmtProfile, SQLSRV_FETCH_ASSOC);

// Insert default profile if not exists
if (!$profileDetails) {
    $sqlInsert = "INSERT INTO DashboardTrainerProfile (Name, Contact, Email) VALUES (?, ?, ?)";
    $insertStmt = sqlsrv_query($conn, $sqlInsert, [$username, $trainerDetails['Contact'], $trainerDetails['Email']]);

    if ($insertStmt === false) {
        die("Error inserting profile: " . print_r(sqlsrv_errors(), true));
    }

    $profileDetails = [
        "Name" => $username,
        "ProfilePic" => null,
        "Bio" => null,
        "Contact" => $trainerDetails['Contact'],
        "Specialties" => null,
        "Email" => $trainerDetails['Email']
    ];
}

// Free statements
sqlsrv_free_stmt($stmtTrainer);
sqlsrv_free_stmt($stmtProfile);
sqlsrv_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Trainer Dashboard</title>
    <link rel="stylesheet" href="trainerdash.css" />
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SFW The Gym</h2>
        <ul>
            <li><a href="trainerdash.php" class="active">Dashboard Overview</a></li>
            <li><a href="manage-profile.php">Manage Profile</a></li>
            <li><a href="tclass-schedule.php">Class Schedule</a></li>
            <li><a href="announcements.php">Announcements</a></li>
            <li><a href="trainer-unavailability.php">For Leave</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Trainer Dashboard</h1>
            <div class="trainer-info">
                <span>Welcome, <?= htmlspecialchars($profileDetails['Name']); ?></span>
                <a href="logint.php" class="logout-btn">Logout</a>
            </div>
        </header>

        <!-- Profile Section -->
        <section class="profile-section">
            <h2>Manage Personal Profile</h2>
            <div class="profile-card">
                <img src="<?= $profileDetails['ProfilePic'] ?: 'gym image/male-user-image.webp'; ?>" alt="Profile Picture" class="profile-pic-large" />
                
                <div class="profile-details">
                    <h3><?= htmlspecialchars($profileDetails['Name']); ?></h3>
                    <p>Specialty: <?= htmlspecialchars($profileDetails['Specialties'] ?: 'N/A'); ?></p>
                    <p>Contact: <?= htmlspecialchars($profileDetails['Contact']); ?></p>
                    <p>Email: <?= htmlspecialchars($profileDetails['Email']); ?></p>
                    <p>Bio: <?= htmlspecialchars($profileDetails['Bio'] ?: 'No bio available'); ?></p>
                </div>
            </div>
        </section>
    </div>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
