<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

include '../db.php';
include '../header.php';

// Ensure visitor ID is provided
if (!isset($_GET['id'])) {
    die("Visitor ID is missing.");
}

$visitor_id = intval($_GET['id']);

// Fetch visitor record
$visitor_result = $conn->query("SELECT * FROM visitors WHERE visitor_id = $visitor_id");
if (!$visitor_result || $visitor_result->num_rows === 0) {
    die("Visitor not found.");
}
$visitor = $visitor_result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visitor_type = $_POST['visitor_type'] ?? 'Guest';
    $stmt = $conn->prepare("UPDATE visitors SET visitor_type=? WHERE visitor_id=?");
    $stmt->bind_param("si", $visitor_type, $visitor_id);
    $stmt->execute();
    header("Location: subscriptions.php");
    exit;
}
?>

<div class="container mt-5">
    <div class="card shadow border-0">
        <div class="card-body">
            <h3 class="text-primary mb-4">Edit Visitor Type for <?= htmlspecialchars($visitor['first_name'] . ' ' . $visitor['last_name']) ?></h3>
            <form method="POST">
                <div class="mb-3">
                    <label>Visitor Type</label>
                    <select name="visitor_type" class="form-control">
                        <option value="Guest" <?= $visitor['visitor_type'] == 'Guest' ? 'selected' : '' ?>>Guest</option>
                        <option value="Platinum" <?= $visitor['visitor_type'] == 'Platinum' ? 'selected' : '' ?>>Platinum</option>
                        <option value="VIP" <?= $visitor['visitor_type'] == 'VIP' ? 'selected' : '' ?>>VIP</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="subscriptions.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
