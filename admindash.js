document.addEventListener("DOMContentLoaded", () => {
    // Fetch data for dashboard cards and populate
    fetchDashboardData();
    
    // Fetch recent activities and populate
    fetchRecentActivities();

    // Fetch members list and populate the table
    fetchMembers();
});

// Simulating API Fetch with Mock Data
function fetchDashboardData() {
    const dashboardData = {
        activeMembers: 1204,
        activeTrainers: 16,
        newRegistrations: 25,
        earnings: 1256000 // Earnings in INR (1,256,000)
    };

    animateNumbers("active-members", dashboardData.activeMembers);
    animateNumbers("active-trainers", dashboardData.activeTrainers);
    animateNumbers("new-registrations", dashboardData.newRegistrations);
    formatCurrency("earnings", dashboardData.earnings);
}

function fetchRecentActivities() {
    const recentActivities = [
        "New member registration: John Doe",
        "Trainer updated the class schedule.",
        "Membership renewed: Alex Johnson",
        "Trainer Mike has scheduled a new class.",
        "Payment received from Emily Parker."
    ];

    const recentActivitiesList = document.getElementById("recent-activities-list");
    recentActivitiesList.innerHTML = '';
    recentActivities.forEach(activity => {
        const li = document.createElement("li");
        li.textContent = activity;
        recentActivitiesList.appendChild(li);
    });
}

function fetchMembers() {
    const memberList = [
        { name: "John Doe", membershipType: "Gold", joinDate: "2023-05-12", status: "Active" },
        { name: "Jane Smith", membershipType: "Silver", joinDate: "2022-08-20", status: "Active" }
    ];

    const memberTableBody = document.getElementById("member-table-body");
    memberTableBody.innerHTML = '';
    memberList.forEach(member => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${member.name}</td>
            <td>${member.membershipType}</td>
            <td>${member.joinDate}</td>
            <td>${member.status}</td>
            <td><button>View</button></td>
        `;
        memberTableBody.appendChild(row);
    });
}

// Animating numbers with incremental counting effect
function animateNumbers(elementId, targetNumber) {
    const element = document.getElementById(elementId).querySelector(".animate-number");
    let currentNumber = 0;
    const increment = Math.ceil(targetNumber / 100); // Smoother increment based on target value

    const interval = setInterval(() => {
        currentNumber += increment;
        if (currentNumber >= targetNumber) {
            currentNumber = targetNumber;
            clearInterval(interval);
        }
        element.textContent = currentNumber.toLocaleString();
    }, 20);
}

// Formatting earnings into INR
function formatCurrency(elementId, targetNumber) {
    const element = document.getElementById(elementId).querySelector(".animate-number");
    const formattedEarnings = new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR'
    }).format(targetNumber);
    element.textContent = formattedEarnings;
}
