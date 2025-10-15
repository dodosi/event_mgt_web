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

$toastMessage = "";
$toastType = ""; // success or danger

 
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
<br/><br/><br/>
<div class="container mt-5">
  <div class="row mb-4 align-items-center">
    <div class="col-6">
        <h4 class="text-primary mb-0">📅 Add New Event</h4>
    </div>
    <div class="col-6 text-end">
        <a href="index.php" class="btn btn-primary">+ Back to Events</a>
    </div>
  </div>
  <hr/>

  <div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <form method="POST" class="card p-4 shadow-sm bg-white w-100" style="max-width: 900px;">
      <h4 class="mb-4 text-center">Add New User</h4> 
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
      <div class="d-flex justify-content-between mt-4">
        <button type="submit" class="btn btn-success">Save User</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
      </div>


</form>
  </div>
</div>
<?php include '../footer.php'; ?>
