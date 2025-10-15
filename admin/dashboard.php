<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

include '../db.php';
include '../nav.php';

// Aggregates
$totalEvents       = $conn->query("SELECT COUNT(*) AS total FROM events")->fetch_assoc()['total'];
$upcomingEvents    = $conn->query("SELECT COUNT(*) AS total FROM events WHERE status='Upcoming'")->fetch_assoc()['total'];
$totalVisitors     = $conn->query("SELECT COUNT(*) AS total FROM visitors")->fetch_assoc()['total'];
$totalSubscriptions= $conn->query("SELECT COUNT(*) AS total FROM subscriptions")->fetch_assoc()['total'];
$approvedSubs      = $conn->query("SELECT COUNT(*) AS total FROM visitors WHERE status='Approved'")->fetch_assoc()['total'];
$cancelledSubs     = $conn->query("SELECT COUNT(*) AS total FROM visitors WHERE status='Cancelled'")->fetch_assoc()['total'];

// Events list for chart
$eventsChartData = [];
$eventsResult = $conn->query("SELECT e.event_name, COUNT(s.subscription_id) AS subs 
                              FROM events e 
                              LEFT JOIN subscriptions s ON e.event_id = s.event_id 
                              GROUP BY e.event_id");
while($row = $eventsResult->fetch_assoc()){
    $eventsChartData[] = $row;
}
?>

<br/><br/><br/>
<div class="container mt-5">
    <h2 class="text-primary mb-4">📊 Admin Dashboard</h2>

    <!-- Top aggregates -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <a href="../events/" class="text-decoration-none">
                <div class="card shadow text-center p-3 border-primary border-start border-4">
                    <h6 class="text-muted">🏟️ Total Events</h6>
                    <h2 class="text-primary"><?= $totalEvents ?></h2>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="../events/?status=Upcoming" class="text-decoration-none">
                <div class="card shadow text-center p-3 border-success border-start border-4">
                    <h6 class="text-muted">⏳ Upcoming Events</h6>
                    <h2 class="text-success"><?= $upcomingEvents ?></h2>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="../visitors/" class="text-decoration-none">
                <div class="card shadow text-center p-3 border-info border-start border-4">
                    <h6 class="text-muted">👥 Total Visitors</h6>
                    <h2 class="text-info"><?= $totalVisitors ?></h2>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="../subscriptions/" class="text-decoration-none">
                <div class="card shadow text-center p-3 border-warning border-start border-4">
                    <h6 class="text-muted">📜 Total Subscriptions</h6>
                    <h2 class="text-warning"><?= $totalSubscriptions ?></h2>
                </div>
            </a>
        </div>
    </div>

    <!-- Approved / Cancelled -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <a href="../visitors/?status=Approved" class="text-decoration-none">
                <div class="card shadow p-3 border-success border-start border-4 text-center">
                    <h6 class="text-muted">✅ Approved Visitors</h6>
                    <h2 class="text-success"><?= $approvedSubs ?></h2>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="../visitors/?status=Cancelled" class="text-decoration-none">
                <div class="card shadow p-3 border-danger border-start border-4 text-center">
                    <h6 class="text-muted">❌ Cancelled Visitors</h6>
                    <h2 class="text-danger"><?= $cancelledSubs ?></h2>
                </div>
            </a>
        </div>
    </div>

    <!-- Event Registrations Chart -->
<div class="card shadow p-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Registrations per Event</h5>
        <div>
            <select id="chartTypeSelect" class="form-select form-select-sm d-inline-block me-2" style="width:auto;">
                <option value="bar" selected>Bar</option>
                <option value="line">Line</option>
                <option value="pie">Pie</option>
                <option value="doughnut">Doughnut</option>
            </select>
            <button id="downloadChart" class="btn btn-sm btn-primary">📥 Download</button>
        </div>
    </div>
    <canvas id="eventsChart" ></canvas>
</div>
</div>

<?php include '../footer.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('eventsChart').getContext('2d');

// Initial chart
let eventsChart = new Chart(ctx, {
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

// Switch chart type dynamically
document.getElementById('chartTypeSelect').addEventListener('change', function() {
    const selectedType = this.value;
    eventsChart.destroy();
    eventsChart = new Chart(ctx, {
        type: selectedType,
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
            scales: selectedType === 'bar' || selectedType === 'line' ? { y: { beginAtZero: true } } : {}
        }
    });
});

// Download chart as image
document.getElementById('downloadChart').addEventListener('click', function() {
    const link = document.createElement('a');
    link.download = 'event_registrations_chart.png';
    link.href = eventsChart.toBase64Image();
    link.click();
});
</script>
