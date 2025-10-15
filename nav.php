<?php
// Get the current script path and folder
$current_file = basename($_SERVER['PHP_SELF']);          // e.g. index.php
$current_dir  = basename(dirname($_SERVER['PHP_SELF'])); // e.g. events, attendence, admin

?>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>EventEase – Simplify Your Event Planning</title>

  <!-- Favicon  -->
  <link rel="icon" type="image/x-icon" href="../assets/img/event.png" /> 

  <!-- Font Awesome -->
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

  <!-- Core theme CSS -->
  <link href="../css/styles.css" rel="stylesheet" />
  <link href="../css/styles.css" rel="stylesheet" />

  <!-- Bootstrap Bundle (for dropdowns) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    .nav-link.active,
    .dropdown-item.active {
      background-color: #1abc9c !important;
      color: white !important;
      border-radius: 0.25rem;
    }
  </style>
</head>

<nav class="navbar navbar-expand-lg text-uppercase fixed-top" style="background-color:#1abc9c;">
  <div class="container">
    <!-- Company Logo with styled app name -->
    <a class="navbar-brand d-flex align-items-center" href="#page-top">
        <img src="../assets/img/ughe.png" alt="Company Logo" style="height:40px; width:auto;" class="me-2">
        <span style="font-family: 'aptos', cursive; font-size:1.5rem; color:#fff;">
            ~ EventEase
        </span>
    </a>

    <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded"
      type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
      aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      Menu <i class="fas fa-bars"></i>
    </button>

    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ms-auto">
<?php if (!isset($_SESSION['admin_id'])){ ?>
        <!-- Home -->
        <li class="nav-item mx-0 mx-lg-1">
          <a class="nav-link py-3 px-0 px-lg-3 rounded <?php if ($current_file == 'home.php' || $current_dir == 'home') echo 'active'; ?>"
            href="../home">Home</a>
        </li>
<?php };?>


        <?php if (isset($_SESSION['admin_id'])): 
          
          ?>
          
                  <!-- Dashboard -->
        <li class="nav-item mx-0 mx-lg-1">
          <a class="nav-link py-3 px-0 px-lg-3 rounded <?php if ($current_file == 'dashboard.php') echo 'active'; ?>"
            href="../admin/dashboard.php">Dashboard</a>
        </li>

        <!-- Events -->
        <li class="nav-item mx-0 mx-lg-1">
          <a class="nav-link py-3 px-0 px-lg-3 rounded <?php if ($current_dir == 'events') echo 'active'; ?>"
            href="../events/">Events</a>
        </li>

          <!-- Attendance Dropdown -->
          <?php
          $attendance_pages = ['checkin.php', 'attendence_scan.php'];
          $is_attendance_active = ($current_dir == 'attendence') || in_array($current_file, $attendance_pages);
          ?>
          <li class="nav-item dropdown mx-0 mx-lg-1">
            <a class="nav-link dropdown-toggle py-3 px-0 px-lg-3 rounded <?php if ($is_attendance_active) echo 'active'; ?>"
              href="#" id="attendanceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Attendance
            </a>
            <ul class="dropdown-menu" aria-labelledby="attendanceDropdown">
              <li><a class="dropdown-item <?php if ($current_file == 'checkin.php') echo 'active'; ?>"
                href="../attendence/checkin.php">Manual Check-In</a></li>
              <li><a class="dropdown-item <?php if ($current_file == 'attendence_scan.php') echo 'active'; ?>"
                href="../attendence/attendence_scan.php">Scan QR</a></li>
            </ul>
          </li>

          <!-- Users -->
          <li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded <?php if ($current_file == 'users.php') echo 'active'; ?>"
              href="../users">Users</a>
          </li>

          <!-- Logout -->
          <li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded" href="../admin/logout.php">Logout</a>
          </li>

        <?php else: ?>

          <!-- Login -->
          <li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded <?php if (($current_dir == 'admin' && $current_file == 'index.php')|| $current_file == 'login.php') echo 'active'; ?>"
              href="../admin/">Login</a>
          </li>

        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
