<?php
include '../db.php';

if(!isset($_POST['ticket_code'])){
    echo json_encode(['message'=>'No ticket code received']);
    exit;
}

$ticket_code = $_POST['ticket_code'];

// Lookup subscription and visitor
$sub = $conn->query("SELECT s.subscription_id, v.visitor_id, v.first_name, v.middle_name, v.last_name, v.email, v.visitor_type, e.event_name, e.event_id
                     FROM subscriptions s
                     JOIN visitors v ON s.visitor_id=v.visitor_id
                     JOIN events e ON s.event_id=e.event_id
                     WHERE s.ticket_code='$ticket_code' LIMIT 1");

if($sub && $sub->num_rows > 0){
    $data = $sub->fetch_assoc();

    // Check if already checked in
    $att = $conn->query("SELECT * FROM attendance WHERE subscription_id={$data['subscription_id']} AND event_id={$data['event_id']}");
    if($att->num_rows === 0){
        $conn->query("INSERT INTO attendance (subscription_id, visitor_id, event_id, method) VALUES ({$data['subscription_id']}, {$data['visitor_id']}, {$data['event_id']}, 'QR')");
        $msg = "<span class='text-success'>✅ Checked in successfully!</span>";
    } else {
        $msg = "<span class='text-info'>ℹ️ Visitor already checked in.</span>";
    }

    echo json_encode([
        'visitor_name' => $data['first_name'].' '.($data['middle_name']?$data['middle_name'].' ':'').$data['last_name'],
        'visitor_email'=> $data['email'],
        'visitor_type' => $data['visitor_type'],
        'event_name'   => $data['event_name'],
        'message'      => $msg
    ]);

} else {
    echo json_encode([
        'visitor_name'=>'N/A',
        'visitor_email'=>'N/A',
        'visitor_type'=>'N/A',
        'event_name'=>'N/A',
        'message'=> "<span class='text-danger'>❌ Ticket not found.</span>"
    ]);
}
