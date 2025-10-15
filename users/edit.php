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
<br/><br/><br/>
<div class="container mt-5">
  <div class="row mb-4 align-items-center">
    <div class="col-6">
      <h4 class="text-primary mb-0">✏️ User Management /Edit User</h4>
    </div>
    <div class="col-6 text-end">
      <a href="index.php" class="btn btn-primary">+ Back to Users</a>
    </div>
  </div>
  <hr/>

  <div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <form method="POST" class="card p-4 shadow-sm bg-white w-100" style="max-width: 900px;">
      <h4 class="mb-4 text-center">Edit User</h4>  
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
        <div class="d-flex justify-content-end mt-4">
        <button type="submit" class="btn btn-success me-2">Update User</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
      </div> 
</form>
  </div>
</div>
<?php include '../footer.php'; ?>
