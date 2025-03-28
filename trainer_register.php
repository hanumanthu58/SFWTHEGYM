<?php
session_start();

// Check if name is stored in the session
if (!isset($_SESSION['name'])) {
    echo "<div class='container'><h1>Error: Trainer details not found!</h1></div>";
    exit;
}

$trainerName = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Registration Success</title>
    <style>
        .container {
          
            text-align: center;
            padding-top: 200px;
            color: white;
            animation: fadeInMoveUp 2s ease-in-out forwards;
        } 
        body{
          background-color:#776f6d;
        }
        @keyframes fadeInMoveUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Trainer <?php echo htmlspecialchars($trainerName); ?> registered successfully!</h1>
    </div>
</body>
</html>
