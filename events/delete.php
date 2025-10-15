<?php
include '../db.php';

$id = $_GET['id'];
$success = $conn->query("DELETE FROM events WHERE event_id = $id");

// Redirect to index.php with status in URL
header("Location: index.php?deleted=" . ($success ? "1" : "0"));
exit;
