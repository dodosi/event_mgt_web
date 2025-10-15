<?php
require '../vendor/endroid/qr-code/src/Autoloader.php';
include '../db.php';


use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

if(!isset($_GET['visitor_id'])){
    die("Visitor ID missing.");
}

$visitor_id = intval($_GET['visitor_id']);

// Fetch visitor info
$visitor = $conn->query("SELECT v.*, s.ticket_code, e.event_name FROM visitors v
                        JOIN subscriptions s ON v.visitor_id = s.visitor_id
                        JOIN events e ON s.event_id = e.event_id
                        WHERE v.visitor_id = $visitor_id")->fetch_assoc();

if(!$visitor){
    die("Visitor not found.");
}

// Prepare data for QR code
$qrData = "Name: ".$visitor['first_name']." ".$visitor['middle_name']." ".$visitor['last_name']."\n".
          "Email: ".$visitor['email']."\n".
          "Visitor Type: ".$visitor['visitor_type']."\n".
          "Ticket: ".$visitor['ticket_code']."\n".
          "Event: ".$visitor['event_name'];

// Generate QR code
$qr = Builder::create()
    ->writer(new PngWriter())
    ->data($qrData)
    ->encoding(new Encoding('UTF-8'))
    ->size(300)
    ->margin(10)
    ->build();

header('Content-Type: '.$qr->getMimeType());
echo $qr->getString();
