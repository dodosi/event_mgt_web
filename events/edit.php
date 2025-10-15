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

$id = $_GET['id'];

echo $_SESSION['admin_id'];
echo $id;


$result = $conn->query("SELECT * FROM events WHERE event_id = $id");
$event = $result->fetch_assoc();

$toastMessage = "";
$toastType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = trim($_POST['event_name']);
    $event_date = trim($_POST['event_date']);
    $location   = trim($_POST['location']);
    $description= trim($_POST['description']);
    $status     = trim($_POST['status']);

    if (!empty($event_name) && !empty($event_date) && !empty($location)) {
        $stmt = $conn->prepare("UPDATE events SET event_name=?, event_date=?, location=?, description=?, status=? WHERE event_id=?");
        $stmt->bind_param("sssssi", $event_name, $event_date, $location, $description, $status, $id);
        if ($stmt->execute()) {
            $toastMessage = "✅ Event updated successfully!";
            $toastType = "success";
        } else {
            $toastMessage = "❌ Failed to update event. Please try again.";
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
      <h4 class="text-primary mb-0">✏️ Edit Event</h4>
    </div>
    <div class="col-6 text-end">
      <a href="index.php" class="btn btn-primary">+ Back to Events</a>
    </div>
  </div>
  <hr/>

  <div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <form method="POST" class="card p-4 shadow-sm bg-white w-100" style="max-width: 900px;">
      <h4 class="mb-4 text-center">Edit Event</h4>

      <div class="row g-3">
        <!-- Left Column -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Event Name</label>
            <input type="text" name="event_name" class="form-control" value="<?= htmlspecialchars($event['event_name']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($event['location']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option <?= $event['status'] == 'Upcoming' ? 'selected' : '' ?> value="Upcoming">Upcoming</option>
              <option <?= $event['status'] == 'Ongoing' ? 'selected' : '' ?> value="Ongoing">Ongoing</option>
              <option <?= $event['status'] == 'Completed' ? 'selected' : '' ?> value="Completed">Completed</option>
              <option <?= $event['status'] == 'Cancelled' ? 'selected' : '' ?> value="Cancelled">Cancelled</option>
            </select>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Date & Time</label>
            <input type="datetime-local" name="event_date" class="form-control" 
              value="<?= date('Y-m-d\TH:i', strtotime($event['event_date'])) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($event['description']) ?></textarea>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end mt-4">
        <button type="submit" class="btn btn-success me-2">Update Event</button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  var toastEl = document.getElementById('liveToast');
  if (toastEl.classList.contains('show')) {
    var toast = new bootstrap.Toast(toastEl);
    toast.show();
  }
});
</script>
