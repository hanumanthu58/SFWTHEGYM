<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to access this page.'); window.location.href = 'login.php';</script>";
    exit;
}

include("db_connection.php"); // Include the database connection file
$user_id = $_SESSION['user_id']; // Fetch user ID from session

// Fetch the user's profile picture path from the database using SQLSRV
$sql = "SELECT profile_pic FROM user_profile WHERE userid = ?";
$params = [$user_id];

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("SQL Execution Failed: " . print_r(sqlsrv_errors(), true));
}

$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$username = $_SESSION['username'] ?? 'Guest';

// Default profile picture if not found
$profileImagePath = $user['profile_pic'] ?? "default-profile.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SFW The Gym</title>
    <link rel="stylesheet" href="indexhp.css?v=2.0">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

</head>
<body>

<header>
    <div class="logo">
        <img src="gym image/GYM-in-Ahmedabad.png" alt="SFW The GYM Logo">
    </div>

    <!-- Dark Mode Toggle -->
    <button id="darkModeToggle"></button>

    <!-- Hamburger icon for mobile menu -->
    <div class="menu-toggle" onclick="toggleMenu()">☰</div>

    <nav>
        <ul>
            <li><a href="indexhp.php">Home</a></li>
            <li class="dropdown">
                <a href="#">Services <span class="arrow">▼</span></a>
                <div class="dropdown-content">
                    <a href="membership.php">Membership</a>
                    <a href="trainer.php">Personal Trainer</a>
                    <a href="call.php">Video Call to Trainer</a>
                </div>
            </li>
            <li><a href="why_sfw.php">Why SFW</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li>
                <a href="profile.php">
                    <img src="<?php echo htmlspecialchars($profileImagePath); ?>" alt="Profile Icon" class="profile-icon"> 
                </a>
            </li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="banner">
    <div class="overlay">
        <h1>SFW THE GYM</h1>
        <h3>Welcome Back, <?php echo htmlspecialchars($username); ?>!</h3>
        <p>Your fitness journey begins here. Explore our world-class facilities and expert trainers.</p>
        <a href="membership.php" class="join-btn">Join Now</a>
    </div>
</section>

<section class="stats">
    <div class="stat-box">
        <i class="fas fa-users"></i>
        <h3>20,000+</h3>
        <p>Members</p>
    </div>
    <div class="stat-box">
        <i class="fas fa-dumbbell"></i>
        <h3>20+</h3>
        <p>Programs</p>
    </div>
    <div class="stat-box">
        <i class="fas fa-calendar"></i>
        <h3>100+</h3>
        <p>Classes</p>
    </div>
    <div class="stat-box">
        <i class="fas fa-user-tie"></i>
        <h3>150+</h3>
        <p>Trainers</p>
    </div>
</section>

<footer>
    <div class="footer-logo">
        <img src="gym image/GYM-in-Ahmedabad.png" alt="SFW The GYM Logo">
        <p>Your Fitness Journey Starts Here</p>
    </div>

    <div class="footer-location">
  <!-- Google Maps Link with Map Marker Icon -->
  <a href="https://maps.app.goo.gl/5EYuFnCRLj62hJR89" target="_blank" title="Find us on Google Maps">
    <i class="fas fa-map-marker-alt"></i>
  </a>

  <!-- Instagram Link with Instagram Icon -->
  <a href="https://www.instagram.com/team_sfw_hirawadi_hiral?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" title="Follow us on Instagram">
    <i class="fab fa-instagram"></i>
  </a>

  <br><p>Developed and Managed DP Creation</p>
</div>


    <div class="footer-hours">
        <h4>OPENING HOURS</h4>
        <p>MONDAY – SATURDAY:<br>
            6:00 AM – 10:00 PM<br>
            3:00 PM – 05:00 PM (Ladies Time)<br>
            5:00 PM – 10:00 PM</p>
        <p>SUNDAY:<br>
            6:00 AM – 10:00 AM</p>
    </div>
</footer>

<script>
    function toggleMenu() {
        document.querySelector("nav ul").classList.toggle("open");
    }

    // Dark Mode Toggle
    const darkModeToggle = document.getElementById("darkModeToggle");
    darkModeToggle.addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");
        localStorage.setItem("darkMode", document.body.classList.contains("dark-mode"));
    });

    // Load Dark Mode State
    if (localStorage.getItem("darkMode") === "true") {
        document.body.classList.add("dark-mode");
    }
</script>
<script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
