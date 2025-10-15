<?php
session_start();
?>
<?php
include '../db.php';
include '../nav.php';
?>
<?php
$toastMessage = "";
$toastType = "";

if (isset($_GET['deleted'])) {
    if ($_GET['deleted'] == "1") {
        $toastMessage = "✅ Event deleted successfully!";
        $toastType = "success";
    } else {
        $toastMessage = "❌ Failed to delete event.";
        $toastType = "danger";
    }
}
?>

<br/><br/><br/>
<div class="container mt-5">
  <div class="row mb-4 align-items-center">
    <div class="col-6">
        <h4 class="text-primary mb-0">📅 Events</h4>
    </div>
    <div class="col-6 text-end">
        <?php if(isset($_SESSION['admin_id'])): ?>
             <a href="create.php" class="btn btn-primary">+ Add New Event</a>
        <?php endif; ?>
    </div>
</div>
<hr/>

<div class="row g-4">
        <?php
        $sql = "SELECT * FROM events ORDER BY event_date DESC";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary"><?= htmlspecialchars($row['event_name']) ?></h5>
                    <p class="card-text mb-1"><strong>Date:</strong> <?= date("d M Y H:i", strtotime($row['event_date'])) ?></p>
                    <p class="card-text mb-1"><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
                    <p class="card-text mb-2">
                        <span class="badge 
                        <?= $row['status'] == 'Upcoming' ? 'bg-success' : ($row['status'] == 'Ongoing' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                        <?= $row['status'] ?>
                        </span>
                    </p>
                    <div class="d-flex justify-content-between">
                        <a href="view.php?id=<?= $row['event_id'] ?>" class="btn btn-sm btn-info">👁 View</a>
                        
                        <?php if(isset($_SESSION['admin_id'])): ?>
                            <a href="edit.php?id=<?= $row['event_id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                            <a href="delete.php?id=<?= $row['event_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">🗑 Delete</a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
        <?php
            endwhile;
        else:
        ?>
        <div class="col-12 text-center text-muted">
            No events found. Click "Add New Event" to create one.
        </div>
        <?php endif; ?>
    </div>
</div>
<div class="mt-5"></div>
<?php if(!empty($toastMessage)): ?>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
  <div id="liveToast" class="toast align-items-center text-white bg-<?= $toastType ?> border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body"><?= $toastMessage ?></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<script>
  var toastEl = document.getElementById('liveToast');
  var toast = new bootstrap.Toast(toastEl);
  toast.show();
</script>
<?php endif; ?>

<?php include '../footer.php'; ?>

