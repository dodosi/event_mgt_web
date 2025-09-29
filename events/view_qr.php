<?php
include '../db.php';
require '../vendor/autoload.php'; // Composer autoload

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

// Check visitor_id in URL
if(!isset($_GET['visitor_id'])){
    die("Visitor ID missing.");
}

$visitor_id = intval($_GET['visitor_id']);

// Fetch visitor info
$visitor_query = $conn->query("
    SELECT v.*, s.ticket_code, e.event_name, e.location, e.event_date
    FROM visitors v
    JOIN subscriptions s ON v.visitor_id = s.visitor_id
    JOIN events e ON s.event_id = e.event_id
    WHERE v.visitor_id = $visitor_id
");

if(!$visitor_query || $visitor_query->num_rows === 0){
    die("Visitor not found.");
}

$visitor = $visitor_query->fetch_assoc();

// Prepare QR data
$qrData = "Name: " . $visitor['first_name'] . " " . $visitor['middle_name'] . " " . $visitor['last_name'] . "\n" .
          "Email: " . $visitor['email'] . "\n" .
          "Visitor Type: " . $visitor['visitor_type'] . "\n" .
          "Ticket: " . $visitor['ticket_code'] . "\n" .
          "Event: " . $visitor['event_name'];

// Generate QR code (smaller)
$qr = Builder::create()
    ->writer(new PngWriter())
    ->data($qrData)
    ->encoding(new Encoding('UTF-8'))
    ->size(120)
    ->margin(5)
    ->build();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Ticket - <?= htmlspecialchars($visitor['event_name']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .ticket-card {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 20px;
            border: 2px solid #007bff;
            border-radius: 15px;
            max-width: 600px;
            margin: auto;
            background: #f8f9fa;
        }
        .ticket-qr {
            flex: 0 0 130px;
            text-align: center;
        }
        .ticket-info {
            flex: 1;
            padding-left: 20px;
        }
        .ticket-info h2 {
            margin-bottom: 10px;
            color: #007bff;
        }
        .ticket-info p {
            margin-bottom: 5px;
        }
        @media print {
            body * { visibility: hidden; }
            .ticket-card, .ticket-card * { visibility: visible; }
            .ticket-card { margin: 0; }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="ticket-card shadow-lg">
        <div class="ticket-qr">
            <img src="data:<?= $qr->getMimeType() ?>;base64,<?= base64_encode($qr->getString()) ?>" 
                 alt="QR Code" style="width:120px; height:120px;">
        </div>
        <div class="ticket-info">
            <h2><?= htmlspecialchars($visitor['event_name']) ?></h2>
            <p><strong>Visitor:</strong> <?= htmlspecialchars($visitor['first_name'] . ' ' . ($visitor['middle_name']?$visitor['middle_name'].' ':'') . $visitor['last_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($visitor['email']) ?></p>
            <p><strong>Visitor Type:</strong> <?= htmlspecialchars($visitor['visitor_type']) ?></p>
            <p><strong>Ticket:</strong> <?= htmlspecialchars($visitor['ticket_code']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($visitor['location']) ?></p>
            <p><strong>Date:</strong> <?= date("d M Y H:i", strtotime($visitor['event_date'])) ?></p>

            <div class="mt-3">
                <button onclick="window.print()" class="btn btn-primary">🖨️ Print Ticket</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
