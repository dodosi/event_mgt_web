<?php 
include '../db.php';
include '../nav.php'; 
?>
<br><br><br><br><br><br>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $location   = $_POST['location'];
    $description= $_POST['description'];
    $status     = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, location, description, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $event_name, $event_date, $location, $description, $status);
    $stmt->execute();
    header("Location: index.php");
}
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
  <form method="POST" class="card p-4 shadow-sm bg-white" style="width: 100%; max-width: 500px;">
    <h4 class="mb-4 text-center">Add New Event</h4>
    
    <div class="mb-3">
      <label>Event Name</label>
      <input type="text" name="event_name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Date & Time</label>
      <input type="datetime-local" name="event_date" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Location</label>
      <input type="text" name="location" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Description</label>
      <textarea name="description" class="form-control" rows="4"></textarea>
    </div>

    <div class="mb-3">
      <label>Status</label>
      <select name="status" class="form-select">
        <option value="Upcoming">Upcoming</option>
        <option value="Ongoing">Ongoing</option>
        <option value="Completed">Completed</option>
        <option value="Cancelled">Cancelled</option>
      </select>
    </div>

    <div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-success">Save Event</button>
      <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>

<?php include '../footer.php'; ?>
