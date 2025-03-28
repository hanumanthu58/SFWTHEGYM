<?php
// Redirect to login.php after 1 second
 header("refresh:2.5;url=login.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to SFW THE GYM</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="splash-screen">
        <img src="gym image/GYM-in-Ahmedabad.png" alt="Gym Logo" class="logo">
        <h1 class="welcome-text">Welcome to SFW THE GYM</h1>
    </div>
</body>
</html>
<?php
require __DIR__ . '/vendor/autoload.php';

// Load .env variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Example: Accessing variables

