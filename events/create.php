<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/login.php");
    exit;
}
?>
<?php 
include '../db.php';
include '../nav.php'; 

$toastMessage = "";
$toastType = ""; // success or danger

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = trim($_POST['event_name']);
    $event_date = trim($_POST['event_date']);
    $location   = trim($_POST['location']);
    $description= trim($_POST['description']);
    $status     = trim($_POST['status']);

    if (!empty($event_name) && !empty($event_date) && !empty($location)) {
        $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, location, description, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $event_name, $event_date, $location, $description, $status);
        
        if ($stmt->execute()) {
            $toastMessage = "✅ Event added successfully!";
            $toastType = "success";
        } else {
            $toastMessage = "❌ Failed to add event. Please try again.";
            $toastType = "danger";
        }
        $stmt->close();
    } else {
        $toastMessage = "⚠️ Please fill in all required fields.";
        $toastType = "warning";
    }
}
?> 

<br/><br/><br/>
<div class="container mt-5">
  <div class="row mb-4 align-items-center">
    <div class="col-6">
        <h4 class="text-primary mb-0">📅 Add New Event</h4>
    </div>
    <div class="col-6 text-end">
        <a href="index.php" class="btn btn-primary">+ Back to Events</a>
    </div>
  </div>
  <hr/>

  <div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <form method="POST" class="card p-4 shadow-sm bg-white w-100" style="max-width: 900px;">
      <h4 class="mb-4 text-center">Add New Event</h4>

      <div class="row g-3">
        <!-- Left Column -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Event Name</label>
            <input type="text" name="event_name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="Upcoming">Upcoming</option>
              <option value="Ongoing">Ongoing</option>
              <option value="Completed">Completed</option>
              <option value="Cancelled">Cancelled</option>
            </select>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Date & Time</label>
            <input type="datetime-local" name="event_date" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="5"></textarea>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <button type="submit" class="btn btn-success">Save Event</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<!-- Toast Container -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
  <div id="liveToast" class="toast align-items-center text-white bg-<?php echo $toastType; ?> border-0 <?php echo !empty($toastMessage) ? 'show' : ''; ?>" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <?php echo $toastMessage; ?>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<?php include '../footer.php'; ?>

<!-- Bootstrap Toast JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  var toastEl = document.getElementById('liveToast');
  if (toastEl.classList.contains('show')) {
    var toast = new bootstrap.Toast(toastEl);
    toast.show();
  }
});
</script>
