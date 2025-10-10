<?php include '../db.php'; ?>
<?php include '../header.php'; ?>

<?php
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE user_id = $id");
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email     = $_POST['email'];
    $phone     = $_POST['phone'];
    $role      = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, phone=?, role=? WHERE user_id=?");
    $stmt->bind_param("ssssi", $full_name, $email, $phone, $role, $id);
    $stmt->execute();
    header("Location: index.php");
}
?>

<h4>Edit User</h4>
<form method="POST" class="card p-4 shadow-sm bg-white">
  <div class="mb-3">
    <label>Full Name</label>
    <input type="text" name="full_name" class="form-control" value="<?= $user['full_name'] ?>" required>
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>
  </div>
  <div class="mb-3">
    <label>Phone</label>
    <input type="text" name="phone" class="form-control" value="<?= $user['phone'] ?>">
  </div>
  <div class="mb-3">
    <label>Role</label>
    <select name="role" class="form-select">
      <option <?= $user['role']=='Guest'?'selected':'' ?> value="Guest">Guest</option>
      <option <?= $user['role']=='VIP'?'selected':'' ?> value="VIP">VIP</option>
      <option <?= $user['role']=='Speaker'?'selected':'' ?> value="Speaker">Speaker</option>
      <option <?= $user['role']=='Staff'?'selected':'' ?> value="Staff">Staff</option>
      <option <?= $user['role']=='Admin'?'selected':'' ?> value="Admin">Admin</option>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Update</button>
  <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../footer.php'; ?>
