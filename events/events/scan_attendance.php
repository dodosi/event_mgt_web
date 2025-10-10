<?php
include '../db.php';
header('Content-Type: application/json');

if(!isset($_POST['ticket_code'])) {
    echo json_encode(['status'=>'error','message'=>'Ticket code missing']);
    exit;
}

$ticket_code = $_POST['ticket_code'];

// Find the visitor + subscription
$stmt = $conn->prepare("
    SELECT s.subscription_id, v.visitor_id, e.event_id
    FROM subscriptions s
    JOIN visitors v ON s.visitor_id = v.visitor_id
    JOIN events e ON s.event_id = e.event_id
    WHERE s.ticket_code = ?
");
$stmt->bind_param("s", $ticket_code);
$stmt->execute();
$stmt->store_result();
if($stmt->num_rows === 0){
    echo json_encode(['status'=>'error','message'=>'Ticket not found']);
    exit;
}
$stmt->bind_result($subscription_id, $visitor_id, $event_id);
$stmt->fetch();

// Check if already checked in
$check = $conn->prepare("SELECT * FROM attendance WHERE subscription_id=? AND event_id=?");
$check->bind_param("ii",$subscription_id,$event_id);
$check->execute();
$check->store_result();

if($check->num_rows > 0){
    echo json_encode(['status'=>'info','message'=>'Visitor already checked in']);
    exit;
}

// Insert attendance
$insert = $conn->prepare("INSERT INTO attendance (subscription_id, visitor_id, event_id, method) VALUES (?, ?, ?, 'Scanner')");
$insert->bind_param("iii",$subscription_id,$visitor_id,$event_id);
$insert->execute();

echo json_encode(['status'=>'success','message'=>'✅ Attendance recorded successfully!']);
?>
