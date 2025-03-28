// script.js

function initiateVideoCall(trainerName) {
    // Trainer's phone number (in international format)
    var trainerPhoneNumber = "12222222";  // Replace with actual trainer's phone number
    
    // Redirect the user to WhatsApp with the trainer's phone number
    var whatsappUrl = 'https://wa.me/' + trainerPhoneNumber + '?text=Hi%20' + trainerName + ',%20I%20would%20like%20to%20video%20call%20you.%20';
    
    // Open the WhatsApp chat for the user to manually initiate the video call
    window.location.href = whatsappUrl;
}

function endCall() {
    // Hide the video call container
    document.querySelector('.video-call-container').style.display = 'none';
}
