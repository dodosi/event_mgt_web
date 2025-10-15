<!DOCTYPE html>
<html lang="en">
   <?php include '../nav.php'; ?>
   <body id="page-top">
      <!-- Navigation-->
      <?php include '../nav.php'; ?>

      <style>
         .portfolio-item img {
            display: block;
            margin: 0 auto;
            max-width: 220px;
            height: 220px;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            background-color: #fff;
            padding: 10px;
         }
         .masthead-avatar {
            width: 180px;
            height: 180px;
            object-fit: contain;
         }
      </style>

      <!-- Masthead-->
      <header class="masthead bg-primary text-white text-center">
         <div class="container d-flex align-items-center flex-column">
            <!-- <img class="masthead-avatar mb-5" src="../assets/img/event.png" alt="Event Management System" /> -->
            <h1 class="masthead-heading text-uppercase mb-0">Event Management System</h1>
            <div class="divider-custom divider-light">
               <div class="divider-custom-line"></div>
               <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
               <div class="divider-custom-line"></div>
            </div>
            <p class="masthead-subheading font-weight-light mb-0">
               Manage | Register | Attend Events Seamlessly
            </p>
         </div>
      </header>

      <!-- Portfolio Section-->
      <section class="page-section portfolio" id="portfolio">
         <div class="container">
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Our Features</h2>
            <div class="divider-custom">
               <div class="divider-custom-line"></div>
               <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
               <div class="divider-custom-line"></div>
            </div>

            <div class="row justify-content-center text-center">
               <!-- Feature 1-->
               <div class="col-md-6 col-lg-4 mb-5">
                  <img src="../assets/img/event.png" alt="Event Registration" class="img-fluid">
                  <h5 class="mt-3">Easy Event Registration</h5>
                  <p class="text-muted small">Register for events quickly using your ticket or QR code.</p>
               </div>

               <!-- Feature 2-->
               <div class="col-md-6 col-lg-4 mb-5">
                  <img src="../assets/img/qr.PNG" alt="QR Code Tickets" class="img-fluid">
                  <h5 class="mt-3">QR Code Tickets</h5>
                  <p class="text-muted small">Each ticket is securely generated with a QR code for fast check-ins.</p>
               </div>

               <!-- Feature 3-->
               <div class="col-md-6 col-lg-4 mb-5">
                  <img src="../assets/img/dashboard.png" alt="Admin Management" class="img-fluid">
                  <h5 class="mt-3">Admin Dashboard</h5>
                  <p class="text-muted small">Manage events, participants, and attendance in one place.</p>
               </div>

               <!-- Feature 4-->
               <div class="col-md-6 col-lg-4 mb-5">
                  <img src="../assets/img/attendence.png" alt="Event Attendees" class="img-fluid">
                  <h5 class="mt-3">Real-Time Attendance</h5>
                  <p class="text-muted small">Capture attendance automatically via scanning or manually.</p>
               </div>

               <!-- Feature 5-->
               <div class="col-md-6 col-lg-4 mb-5">
                  <img src="../assets/img/email.png" alt="Email Notifications" class="img-fluid">
                  <h5 class="mt-3">Instant Email Confirmation</h5>
                  <p class="text-muted small">Receive automatic email confirmation with your QR code ticket.</p>
               </div>

               <!-- Feature 6-->
               <div class="col-md-6 col-lg-4 mb-5">
                  <img src="../assets/img/cloud.png" alt="Cloud Based" class="img-fluid">
                  <h5 class="mt-3">Cloud Based System</h5>
                  <p class="text-muted small">Access your event data securely anytime, anywhere.</p>
               </div>
            </div>
         </div>
      </section>

      <!-- About Section -->
      <section class="page-section bg-primary text-white mb-0" id="about">
         <div class="container">
            <h2 class="page-section-heading text-center text-uppercase text-white">About the System</h2>
            <div class="divider-custom divider-light">
               <div class="divider-custom-line"></div>
               <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
               <div class="divider-custom-line"></div>
            </div>
            <div class="row">
               <div class="col-lg-4 ms-auto">
                  <p class="lead">
                     The Event Management System helps organizations plan, register, and manage events efficiently. 
                     It provides automated ticket generation, QR-based check-ins, and real-time attendance tracking.
                  </p>
               </div>
               <div class="col-lg-4 me-auto">
                  <p class="lead">
                     Whether you're organizing a conference, seminar, or concert — this system streamlines 
                     registration, enhances security, and provides a seamless experience for both event organizers and attendees.
                  </p>
               </div>
            </div>
            <div class="text-center mt-4">
               <a class="btn btn-xl btn-outline-light" href="events/list.php">
                  <i class="fas fa-calendar-alt me-2"></i>
                  View Upcoming Events
               </a>
            </div>
         </div>
      </section>

      <!-- Footer -->
      <footer class="footer text-center">
         <div class="container">
            <div class="row">
               <div class="col-lg-4 mb-5 mb-lg-0">
                  <h4 class="text-uppercase mb-4">Location</h4>
                  <p class="lead mb-0">Event Management HQ<br> Kigali, Rwanda</p>
               </div>
               <div class="col-lg-4 mb-5 mb-lg-0">
                  <h4 class="text-uppercase mb-4">Follow Us</h4>
                  <a class="btn btn-outline-light btn-social mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                  <a class="btn btn-outline-light btn-social mx-1" href="#"><i class="fab fa-twitter"></i></a>
                  <a class="btn btn-outline-light btn-social mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
               </div>
               <div class="col-lg-4">
                  <h4 class="text-uppercase mb-4">About EMS</h4>
                  <p class="lead mb-0">
                     A modern, cloud-powered platform for managing and automating event registration and attendance.
                  </p>
               </div>
            </div>
         </div>
      </footer>

      <div class="copyright py-4 text-center text-white bg-dark">
         <div class="container"><small>Copyright &copy; Event Management System <?= date('Y') ?></small></div>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
   </body>
</html>
