<?php
session_start();
?>
<?php include '../db.php'; ?>
<?php include '../header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Users</h3>
  <a href="create.php" class="btn btn-primary">+ Add New User</a>
</div>

<table id="usersTable" class="table table-striped table-bordered">
  <thead class="table-primary">
    <tr>
      <th>ID</th>
      <th>Full Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Role</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $sql = "SELECT * FROM users";
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc()):
    ?>
    <tr>
      <td><?= $row['user_id'] ?></td>
      <td><?= $row['full_name'] ?></td>
      <td><?= $row['email'] ?></td>
      <td><?= $row['phone'] ?></td>
      <td><?= $row['role'] ?></td>
      <td>
        <a href="edit.php?id=<?= $row['user_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="delete.php?id=<?= $row['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include '../footer.php'; ?>
