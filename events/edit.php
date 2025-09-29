<?php include '../db.php'; ?>
<?php include '../header.php'; ?>

<?php
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM events WHERE event_id = $id");
$event = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $location   = $_POST['location'];
    $description= $_POST['description'];
    $status     = $_POST['status'];

    $stmt = $conn->prepare("UPDATE events SET event_name=?, event_date=?, location=?, description=?, status=? WHERE event_id=?");
    $stmt->bind_param("sssssi", $event_name, $event_date, $location, $description, $status, $id);
    $stmt->execute();
    header("Location: index.php");
}
?>

<h4>Edit Event</h4>
<form method="POST" class="card p-4 shadow-sm bg-white">
  <div class="mb-3">
    <label>Event Name</label>
    <input type="text" name="event_name" class="form-control" value="<?= $event['event_name'] ?>" required>
  </div>
  <div class="mb-3">
    <label>Date & Time</label>
    <input type="datetime-local" name="event_date" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($event['event_date'])) ?>" required>
  </div>
  <div class="mb-3">
    <label>Location</label>
    <input type="text" name="location" class="form-control" value="<?= $event['location'] ?>" required>
  </div>
  <div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control" rows="4"><?= $event['description'] ?></textarea>
  </div>
  <div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-select">
      <option <?= $event['status'] == 'Upcoming' ? 'selected' : '' ?> value="Upcoming">Upcoming</option>
      <option <?= $event['status'] == 'Ongoing' ? 'selected' : '' ?> value="Ongoing">Ongoing</option>
      <option <?= $event['status'] == 'Completed' ? 'selected' : '' ?> value="Completed">Completed</option>
      <option <?= $event['status'] == 'Cancelled' ? 'selected' : '' ?> value="Cancelled">Cancelled</option>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Update Event</button>
  <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../footer.php'; ?>
