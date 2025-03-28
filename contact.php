<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - SFW The Gym</title>
    <link rel="stylesheet" href="contact.css">
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
                <li><a href="why_sfw.php">Why SFW</a></li>
                <li><a href="contact.php" class="active">Contact Us</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-text">
            <h1>Contact Us</h1>
        </div>
    </section>

    <section class="contact-info">
        <h2>Let’s Start a Conversation</h2>
        <p><strong>Ask how we can help you:</strong></p>
        <ul>
            <li>Have any questions about our services? Contact us now!</li>
            <li>Explore gym memberships, personal trainers, or video-call sessions.</li>
        </ul>

        <div class="contact-details">
            <p><strong>Helpline:</strong> +91 99985 54009</p>
            <p><strong>For Franchisee:</strong> +91 97221 01010</p>
        </div>
    </section>

    <section class="branches">
        <h2>Our Branches</h2>
        <div class="branch-list">
            <div>
                <h3>Ahmedabad</h3>
                <ul>
                    <li>South Bopal</li>
                    <li>Vastrapur</li>
                    <li>Satellite</li>
                    <li>Gota</li>
                    <li>Paldi</li>
                    <li>Maninagar</li>
                    <li>Nana Chiloda</li>
                    <li>Hirawadi</li>
                    <li>Motera</li>
                    <li>Navrangnagar</li>
                    <li>Chandkheda</li>
                </ul>
            </div>
            <div>
                <h3>Vadodara</h3>
                <ul>
                    <li>Gotri</li>
                    <li>Manjalpur</li>
                    <li>Sama Savali</li>
                </ul>
            </div>
            <div>
                <h3>Other Cities</h3>
                <ul>
                    <li>Anand</li>
                    <li>Kalol</li>
                    <li>Nadiad</li>
                    <li>Maheshnana</li>
                </ul>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 SFW The Gym. All Rights Reserved.</p>
        <br><p>Developed and Managed DP Creation</p>
    </footer>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
