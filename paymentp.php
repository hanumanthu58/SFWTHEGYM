<?php
session_start();

require __DIR__ . '/vendor/autoload.php';

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in!");
}

// Razorpay API Keys from .env
$razorpayKey = $_ENV['RAZORPAY_KEY'];
$razorpaySecret = $_ENV['RAZORPAY_SECRET'];

// Database Connection using .env
$serverName = $_ENV['DB_SERVER'];
$connectionOptions = [
    "Database" => $_ENV['DB_NAME'],
    "Uid" => $_ENV['DB_USER'],
    "PWD" => $_ENV['DB_PASS'],
];
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die("Database connection failed: " . print_r(sqlsrv_errors(), true));
}

// Validate and sanitize amount
if (!isset($_GET['amount']) || !is_numeric($_GET['amount'])) {
    die("Invalid amount!");
}

$user_id = $_SESSION['user_id'];
$amount = floatval($_GET['amount']) * 100; // Convert to paise

// Fetch user details
$sql_user = "SELECT name, email, contact FROM regestration WHERE userid = ?";
$user_stmt = sqlsrv_query($conn, $sql_user, [$user_id]);
if ($user_stmt === false || !sqlsrv_fetch($user_stmt)) {
    die("User not found!");
}

$user_name = sqlsrv_get_field($user_stmt, 0);
$user_email = sqlsrv_get_field($user_stmt, 1);
$user_phone = sqlsrv_get_field($user_stmt, 2);

// Create Razorpay Order
$orderData = [
    "amount" => $amount,
    "currency" => "INR",
    "receipt" => "txn_" . uniqid(),
    "payment_capture" => 1
];

$ch = curl_init("https://api.razorpay.com/v1/orders");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $razorpayKey . ":" . $razorpaySecret);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
$response = json_decode(curl_exec($ch));
curl_close($ch);

if (!isset($response->id)) {
    die("Error creating Razorpay order. Check Network Connection");
}

$order_id = $response->id;
sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment</title>
    <link rel="stylesheet" href="payment.css" />
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <div class="payment-container">
        <h1>Pay with Razorpay</h1>
        <p>Amount to Pay: ₹<?php echo htmlspecialchars($amount / 100); ?></p>
        <button id="pay-btn" class="pay-btn">Pay Now</button>
    </div>
    <script>
        var options = {
            "key": "<?php echo $razorpayKey; ?>",
            "amount": "<?php echo $amount; ?>",
            "currency": "INR",
            "name": "SFW THE GYM",
            "description": "Test Transaction",
            "order_id": "<?php echo $order_id; ?>",
            "handler": function (response) {
                fetch("payment.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        payment_id: response.razorpay_payment_id,
                        order_id: "<?php echo $order_id; ?>",
                        user_id: "<?php echo $user_id; ?>",
                        amount: "<?php echo $amount / 100; ?>"
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        alert("Payment Successful! ID: " + response.razorpay_payment_id);
                    } else {
                        alert("Payment Failed!");
                    }
                });
            },
            "prefill": {
                "name": "<?php echo $user_name; ?>",
                "email": "<?php echo $user_email; ?>",
                "contact": "<?php echo $user_phone; ?>"
            },
            "theme": { "color": "#3399cc" }
        };

        var rzp1 = new Razorpay(options);
        document.getElementById('pay-btn').onclick = function (e) {
            rzp1.open();
            e.preventDefault();
        };
    </script>
    <script>
    window.addEventListener('load', function() {
        document.getElementById('global-loading').style.display = 'none';
    });
</script>

</body>
</html>
