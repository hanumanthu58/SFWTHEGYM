<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SFW The Gym - Membership Packages</title>
    <link rel="stylesheet" href="membership.css">
</head>
<body>
    <header>
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
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <h1>SFW The Gym - Membership Packages</h1>

    </header>
    
    <section class="membership-container">
        <div class="package">
            <h2>Single Person</h2>
            <p>Monthly Package: ₹2000</p>
            <a href="paymentp.php?membership_type=Single&amount=2000" class="join-now-button">Join Now</a>
        </div>
        <div class="package">
            <h2>Couple (2 Persons)</h2>
            <p>Monthly Package: ₹4000</p>
            <a href="paymentp.php?membership_type=Couple&amount=4000" class="join-now-button">Join Now</a>
        </div>
        <div class="package">
            <h2>Family (3 Persons)</h2>
            <p>Monthly Package: ₹6000</p>
            <a href="paymentp.php?membership_type=Family&amount=6000" class="join-now-button">Join Now</a>
        </div>
        <div class="package">
            <h2>Single Person</h2>
            <p>Annual Package: ₹8999</p>
            <a href="paymentp.php?membership_type=Single&amount=8999" class="join-now-button">Join Now</a>
        </div>
        <div class="package">
            <h2>Couple (2 Persons)</h2>
            <p>Annual Package: ₹15999</p>
            <a href="paymentp.php?membership_type=Couple&amount=15999" class="join-now-button">Join Now</a>
        </div>
        <div class="package">
            <h2>Family (3 Persons)</h2>
            <p>Annual Package: ₹20999</p>
            <a href="paymentp.php?membership_type=Family&amount=20999" class="join-now-button">Join Now</a>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 SFW The Gym. All Rights Reserved.</p>
        <br><p>Developed and Managed DP Creation</p>
    </footer>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
