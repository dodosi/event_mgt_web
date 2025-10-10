<?php
// Include database connection and header
include '../db.php';
include '../header.php';
?>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary">📅 Manage Events</h2>
    <a href="create.php" class="btn btn-primary">+ Add New Event</a>
  </div>

  <table id="eventsTable" class="table table-striped table-bordered shadow-sm">
    <thead class="table-primary">
      <tr>
        <th>ID</th>
        <th>Event Name</th>
        <th>Date</th>
        <th>Location</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT * FROM events ORDER BY event_date DESC";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
      ?>
      <tr>
        <td><?= $row['event_id'] ?></td>
        <td><?= htmlspecialchars($row['event_name']) ?></td>
        <td><?= date("d M Y H:i", strtotime($row['event_date'])) ?></td>
        <td><?= htmlspecialchars($row['location']) ?></td>
        <td>
          <span class="badge 
            <?= $row['status'] == 'Upcoming' ? 'bg-success' : ($row['status'] == 'Ongoing' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
            <?= $row['status'] ?>
          </span>
        </td>
        <td>
          <div class="btn-group" role="group">
            <a href="view.php?id=<?= $row['event_id'] ?>" class="btn btn-sm btn-info">👁 View</a>
            <a href="edit.php?id=<?= $row['event_id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
            <a href="delete.php?id=<?= $row['event_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this event?')">🗑 Delete</a>
          </div>
        </td>
      </tr>
      <?php
        endwhile;
      else:
      ?>
      <tr>
        <td colspan="6" class="text-center text-muted">No events found. Click "Add New Event" to create one.</td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include '../footer.php'; ?>

<!-- ✅ DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
  $(document).ready(function () {
    $('#eventsTable').DataTable({
      pageLength: 10,
      lengthMenu: [5, 10, 25, 50],
      order: [[2, "desc"]],
      language: {
        search: "🔍 Search Events:",
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ events"
      }
    });
  });
</script>

<!-- ✅ DataTables CSS (if not already in header.php) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
