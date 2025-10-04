<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Guest Management System</title>
  <!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <style>
      body { background: #f4f9ff; }
      .navbar { background: #0d6efd; }
      .navbar-brand, .nav-link { color: #fff !important; }
      .container { margin-top: 40px; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Guest System</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
    <li class="nav-item"><a class="nav-link" href="/event_mgt/events/index.php">Events</a></li>

          <?php if(isset($_SESSION['admin_id'])): ?>
              <li class="nav-item"><a class="nav-link" href="/event_mgt/users/index.php">Users</a></li>
              
              <!-- Attendence Dropdown -->
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="attendanceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Attendence
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="attendanceDropdown">
                      <li><a class="dropdown-item" href="/event_mgt/attendence/checkin.php">Manual Check-In</a></li>
                      <li><a class="dropdown-item" href="/event_mgt/attendence/attendence_scan.php">Scan QR</a></li>
                  </ul>
              </li>

              <li class="nav-item"><a class="nav-link" href="/event_mgt/admin/dashboard.php">Dashboard</a></li>
              <li class="nav-item"><a class="nav-link" href="/event_mgt/admin/logout.php">Logout</a></li>
          <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="/event_mgt/admin/login.php">Login</a></li>
          <?php endif; ?>
      </ul>

    </div>
  </div>
</nav>
<div class="container">
