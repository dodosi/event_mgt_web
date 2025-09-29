<?php include '../db.php'; ?>
<?php include '../header.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email     = $_POST['email'];
    $phone     = $_POST['phone'];
    $role      = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $email, $phone, $role);
    $stmt->execute();
    header("Location: index.php");
}
?>

<h4>Add New User</h4>
<form method="POST" class="card p-4 shadow-sm bg-white">
  <div class="mb-3">
    <label>Full Name</label>
    <input type="text" name="full_name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Phone</label>
    <input type="text" name="phone" class="form-control">
  </div>
  <div class="mb-3">
    <label>Role</label>
    <select name="role" class="form-select">
      <option value="Guest">Guest</option>
      <option value="VIP">VIP</option>
      <option value="Speaker">Speaker</option>
      <option value="Staff">Staff</option>
      <option value="Admin">Admin</option>
    </select>
  </div>
  <button type="submit" class="btn btn-success">Save</button>
  <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../footer.php'; ?>
