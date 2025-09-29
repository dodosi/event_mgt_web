<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

include '../db.php';
include '../header.php';

// 1️⃣ Total Events
$totalEvents = $conn->query("SELECT COUNT(*) AS total FROM events")->fetch_assoc()['total'];

// 2️⃣ Upcoming Events
$upcomingEvents = $conn->query("SELECT COUNT(*) AS total FROM events WHERE status='Upcoming'")->fetch_assoc()['total'];

// 3️⃣ Total Visitors Registered
$totalVisitors = $conn->query("SELECT COUNT(*) AS total FROM visitors")->fetch_assoc()['total'];

// 4️⃣ Total Subscriptions
$totalSubscriptions = $conn->query("SELECT COUNT(*) AS total FROM subscriptions")->fetch_assoc()['total'];

// 5️⃣ Approved
$approvedSubs = $conn->query("SELECT COUNT(*) AS total FROM visitors WHERE status='Approved'")->fetch_assoc()['total'];

// 6️⃣ Cancelled
$cancelledSubs = $conn->query("SELECT COUNT(*) AS total FROM visitors WHERE status='Cancelled'")->fetch_assoc()['total'];

// 7️⃣ Events list for chart (name vs registered)
$eventsChartData = [];
$eventsResult = $conn->query("SELECT e.event_name, COUNT(s.subscription_id) AS subs FROM events e LEFT JOIN subscriptions s ON e.event_id = s.event_id GROUP BY e.event_id");
while($row = $eventsResult->fetch_assoc()){
    $eventsChartData[] = $row;
}

?>

<div class="container mt-5">
    <h2 class="text-primary mb-4">📊 Admin Dashboard</h2>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card shadow text-center p-3">
                <h5>Total Events</h5>
                <h2><?= $totalEvents ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow text-center p-3">
                <h5>Upcoming Events</h5>
                <h2><?= $upcomingEvents ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow text-center p-3">
                <h5>Total Visitors</h5>
                <h2><?= $totalVisitors ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow text-center p-3">
                <h5>Total Subscriptions</h5>
                <h2><?= $totalSubscriptions ?></h2>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h5>Approved Visitors</h5>
                <h2><?= $approvedSubs ?></h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h5>Cancelled Visitors</h5>
                <h2><?= $cancelledSubs ?></h2>
            </div>
        </div>
    </div>

    <div class="card shadow p-4">
        <h5 class="mb-3">Registrations per Event</h5>
        <canvas id="eventsChart" height="100"></canvas>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('eventsChart').getContext('2d');
const eventsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($eventsChartData, 'event_name')) ?>,
        datasets: [{
            label: 'Number of Registrations',
            data: <?= json_encode(array_column($eventsChartData, 'subs')) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
</script>

<?php include '../footer.php'; ?>
