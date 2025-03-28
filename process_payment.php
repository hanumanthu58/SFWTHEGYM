<?php
if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    // Verify Payment Using Razorpay API
    $api_key = "YOUR_RAZORPAY_KEY";
    $api_secret = "YOUR_RAZORPAY_SECRET";

    $url = "https://api.razorpay.com/v1/payments/" . $payment_id;

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . base64_encode($api_key . ":" . $api_secret)
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error: " . $err;
    } else {
        $response_data = json_decode($response, true);

        if ($response_data['status'] == 'captured') {
            // Payment successful - Store details in the database
            $amount = $response_data['amount'] / 100; // Convert to INR
            $email = $response_data['email'];
            $contact = $response_data['contact'];

            // Database connection (Update with your credentials)
            $conn = new mysqli("localhost", "root", "", "gym_db");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("INSERT INTO payments (payment_id, amount, email, contact) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siss", $payment_id, $amount, $email, $contact);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            echo "Payment successful! Payment ID: " . $payment_id;
        } else {
            echo "Payment failed! Please try again.";
        }
    }
} else {
    echo "No payment ID received.";
}
?>
