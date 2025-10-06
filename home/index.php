<?php
include '../header.php';
?>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<style>
    /* Hover and animation effects */
    .card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: scale(1.05);
    }

    /* Section background styling */
    .features-section {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
        border-radius: 10px;
        padding: 50px 0;
    }

    .list-group-item {
        border: none;
        font-size: 1.05rem;
        padding-left: 1.5rem;
        background: transparent;
    }

    .list-group-item::before {
        content: "✔️";
        margin-right: 8px;
        color: #0d6efd;
    }
</style>

<div class="container mt-5 mb-5">
    <div class="text-center mb-5" data-aos="fade-down">
        <h1 class="text-primary fw-bold">Welcome to UGHE Guest Management System</h1>
        <p class="lead text-muted">
            Streamline event registration, ticketing, and attendance tracking — all in one platform.
        </p>
    </div>

    <!-- Features Cards Section -->
    <div class="features-section" data-aos="fade-up">
        <div class="row justify-content-center">
            <div class="col-md-4 text-center mb-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="text-primary mb-3">📅 Event Management</h4>
                        <p>Organize and manage multiple events efficiently. Add details, set dates, and track locations.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="text-primary mb-3">🎟️ Ticketing & Subscriptions</h4>
                        <p>Generate unique QR or barcode tickets for attendees and track registrations in real-time.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4" data-aos="zoom-in" data-aos-delay="300">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="text-primary mb-3">📝 Attendance Tracking</h4>
                        <p>Scan tickets or manually check-in attendees. Generate attendance reports instantly.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Features List -->
    <div class="text-center my-5" data-aos="fade-up">
        <h3 class="text-primary fw-semibold mb-4">System Features</h3>
        <ul class="list-group list-group-flush mx-auto text-start" style="max-width: 650px;">
            <li class="list-group-item">Admin and staff login with role-based access.</li>
            <li class="list-group-item">Visitor registration with multiple types (Guest, Platinum, VIP).</li>
            <li class="list-group-item">Automatic QR code generation for tickets.</li>
            <li class="list-group-item">Email confirmation with ticket QR code.</li>
            <li class="list-group-item">Real-time check-in and attendance dashboard.</li>
            <li class="list-group-item">Export reports to PDF or Excel.</li>
            <li class="list-group-item">Mobile-compatible interface for scanning and registration.</li>
        </ul>
    </div>

    <!-- Action Buttons -->
    <div class="text-center" data-aos="fade-up" data-aos-delay="200">
        <?php if(!isset($_SESSION['admin_id'])): ?>
            <a href="/event_mgt_web/admin/login.php" class="btn btn-primary btn-lg mx-2">
                <i class="bi bi-person-lock"></i> Admin Login
            </a>
        <?php endif; ?>
        <a href="/event_mgt_web/events/index.php" class="btn btn-outline-primary btn-lg mx-2">
            <i class="bi bi-calendar-event"></i> View Events
        </a>
    </div>
</div>

<script>
    AOS.init({
        duration: 1000,
        once: true
    });
</script>

<?php
include '../footer.php';
?>
