<?php
include '../db.php';

if (!isset($_GET['id'], $_GET['action'])) die("Invalid request.");
$visitor_id = intval($_GET['id']);
$action = $_GET['action'];

$status = '';
if ($action==='approve') $status='Approved';
elseif ($action==='cancel') $status='Cancelled';
else die("Invalid action.");

$stmt = $conn->prepare("UPDATE visitors SET status=? WHERE visitor_id=?");
$stmt->bind_param("si", $status, $visitor_id);
$stmt->execute();

header("Location: subscriptions.php");
exit;
