// Fetch and display announcements dynamically
document.addEventListener('DOMContentLoaded', () => {
    fetch('fetch_announcements.php')
        .then(response => response.json())
        .then(announcements => {
            const announcementsList = document.getElementById('announcements');
            announcements.forEach(announcement => {
                const li = document.createElement('li');
                li.innerHTML = `<h3>${announcement.title}</h3><p>${announcement.message}</p><p><small>${announcement.created_at}</small></p>`;
                announcementsList.appendChild(li);
            });
        })
        .catch(error => console.error('Error fetching announcements:', error));
});
