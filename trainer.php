<?php
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
    <title>Personal Trainer Packages - SFW The Gym</title>
    <link rel="stylesheet" href="trainer.css?v=2.0">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="indexhp.php"><img src="gym image/GYM-in-Ahmedabad.png" alt="SFW The GYM Logo"></a>
        </div>
        <nav>
            <ul>
                <li><a href="indexhp.php">Home</a></li>
                <li class="dropdown">
                    <a href="#">Services <span class="arrow"></span></a>
                    <div class="dropdown-content">
                        <a href="membership.php">Membership</a>
                        <a href="trainer.php">Personal Trainer</a>
                        <a href="call.php">Video Call to Trainer</a>
                    </div>
                </li>
                <li><a href="why_sfw.php">Why SFW</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="profile.php">
                    <img src="<?php echo htmlspecialchars($profileImagePath ?? 'default-profile.png'); ?>" alt="Profile Icon" class="profile-icon"> 
                </a>
            </li>
            </ul>
        </nav>
    </header>

    <section class="packages-section">
        <h1>Personal Trainer Packages</h1>
        <div class="package-card">
            <h2>1 Month</h2>
            <p><i class="fas fa-rupee-sign"></i> 15,000</p>
            <a href="paymentp.php?membership_type=Family&amount=15000" class="signup-btn">Join Now</a>
        </div>
        <div class="package-card">
            <h2>6 Months</h2>
            <p><i class="fas fa-rupee-sign"></i> 30,000</p>
            <a href="paymentp.php?membership_type=Family&amount=30000" class="signup-btn">Join Now</a>
        </div>
        <div class="package-card">
            <h2>1 Year</h2>
            <p><i class="fas fa-rupee-sign"></i> 60,000</p>
            <a href="paymentp.php?membership_type=Family&amount=60000" class="signup-btn">Join Now</a>
        </div>
    </section>

    <footer>
        <div class="footer-logo">
            <img src="gym image/GYM-in-Ahmedabad.png" alt="SFW The GYM Logo" class="footer-logo-img">
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
            <p>Monday – Saturday: 6:00 AM – 10:00 PM</p>
            <p>Sunday: 6:00 AM – 10:00 AM</p>
        </div>
    </footer>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
