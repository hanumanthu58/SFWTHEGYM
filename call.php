<?php
    session_start();
    
    include("db_connection.php");

    $sql = "SELECT name, contact, specialties, profilepic FROM DashboardTrainerProfile";
    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        echo "<p>Error fetching trainers: " . print_r(sqlsrv_errors(), true) . "</p>";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Page - SFW The Gym</title>
    <link rel="stylesheet" href="call.css">
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
                    <a>Services <span class="arrow"></span></a>
                    <div class="dropdown-content">
                        <a href="membership.php">Membership</a>
                        <a href="trainer.php">Personal Trainer</a>
                        <a href="call.php">Video Call to Trainer</a>
                    </div>
                </li>
                <li><a href="why_sfw.php">WhySFW</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </nav>
    </header>

    <section class="trainer-section">
        <h1>Meet Our Trainers</h1>
        <?php
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $profilePic = !empty($row['profilepic']) && file_exists($row['profilepic']) ? htmlspecialchars($row['profilepic']) : 'gym image/male-user-image.webp';
            echo "<div class='trainer-card'>
                    <img src='" . htmlspecialchars($profilePic) . "' alt='Trainer'>
                    <h3>" . htmlspecialchars($row['name']) . "</h3>
                    <p>" . htmlspecialchars($row['specialties']) . "</p>
                    <button class='video-call-btn' onclick='window.location.href=`initiate_call.php?trainer_name=" . urlencode($row['name']) . "`'>Video Call</button>
                  </div>";
        }
        ?>
    </section>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
