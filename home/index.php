<?php
include '../header.php';
?>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h1 class="text-primary">Welcome to UGHE Guest Management System</h1>
        <p class="lead text-muted">Streamline event registration, ticketing, and attendance tracking all in one place.</p>
    </div>

    <div class="row mb-5">
        <div class="col-md-4 text-center">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="text-primary mb-3">📅 Event Management</h4>
                    <p>Organize and manage multiple events efficiently. Add event details, set dates, locations, and statuses.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="text-primary mb-3">🎟️ Ticketing & Subscriptions</h4>
                    <p>Generate unique QR or barcode tickets for attendees. Track registrations and subscription types in real-time.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="text-primary mb-3">📝 Attendance Tracking</h4>
                    <p>Scan tickets at the entrance or manually check-in attendees. Generate attendance reports instantly.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mb-5">
        <h3 class="text-primary mb-3">System Features</h3>
        <ul class="list-group list-group-flush d-inline-block text-start">
            <li class="list-group-item">✅ Admin and staff login with role-based access.</li>
            <li class="list-group-item">✅ Visitor registration with multiple types (Guest, Platinum, VIP).</li>
            <li class="list-group-item">✅ Automatic QR code generation for tickets.</li>
            <li class="list-group-item">✅ Email confirmation with ticket QR code.</li>
            <li class="list-group-item">✅ Real-time check-in and attendance dashboard.</li>
            <li class="list-group-item">✅ Export reports to PDF or Excel.</li>
            <li class="list-group-item">✅ Mobile-compatible interface for scanning and registration.</li>
        </ul>
    </div>

    <div class="text-center">
        <?php if(!isset($_SESSION['admin_id'])): ?>
            <a href="/event_mgt/admin/login.php" class="btn btn-primary btn-lg mx-2">Admin Login</a>
        <?php endif; ?>
        <a href="/event_mgt/events/index.php" class="btn btn-outline-primary btn-lg mx-2">View Events</a>
    </div>
</div>

<?php
include '../footer.php';
?>
