<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
    <link rel="stylesheet" href="razorpay.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <header>
        <h1>Secure Payment via Razorpay</h1>
    </header>
    <main>
        <div class="payment-container">
            <h2>Proceed with Razorpay</h2>
            <button id="rzp-button">Pay ₹500</button>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 SFW The Gym. All rights reserved.</p>
    </footer>
    <script>
        // Read the amount from the query parameter
        const params = new URLSearchParams(window.location.search);
        const amount = params.get('amount');
    
        if (!amount) {
            alert("No amount provided! Redirecting to membership page.");
            window.location.href = "membership.php"; // Redirect if no amount
        }
    
        var options = {
            "key": "FGRbCKbw90tURf4eVNA91EXi", // Replace with your Razorpay Key ID
            "amount": (parseInt(amount) * 100).toString(), // Convert to paise
            "currency": "INR",
            "name": "SFW The Gym",
            "description": "Membership Payment",
            "image": "gym image/GYM-in-Ahmedabad.png",
            "handler": function (response) {
                alert("Payment Successful: " + response.razorpay_payment_id);
                window.location.href = `process_payment.php?payment_id=${response.razorpay_payment_id}`;
            },
            "theme": {
                "color": "#528FF0"
            }
        };
    
        var rzp = new Razorpay(options);
        document.getElementById('rzp-button').innerText = `Pay ₹${amount}`; // Update button text
        document.getElementById('rzp-button').onclick = function (e) {
            rzp.open();
            e.preventDefault();
        }
    </script>
    
    
    
</body>
</html>
