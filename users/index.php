<?php
session_start();
?>
<?php include '../db.php'; ?>
<?php include '../nav.php'; ?>
<br/><br/><br/>  

<div class="container mt-5">
  <div class="row mb-4 align-items-center">
    <div class="col-6">
        <h4 class="text-primary mb-0">📅 Users Management</h4>
    </div>
    <div class="col-6 text-end">
        <a href="create.php" class="btn btn-primary">+ Add New User</a>
    </div>
  </div>
  <hr/>

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
</div>
<br/><br/><br/> 
<?php include '../footer.php'; ?>
