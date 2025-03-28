document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('trainer-profile-form');
    
    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission
        
        // Here, you would typically send the form data to the backend for updating
        alert('Profile updated successfully!'); // Placeholder for actual update logic
        
        // Clear the form fields or update the displayed information
        form.reset();
    });
});
