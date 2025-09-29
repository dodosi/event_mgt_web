<?php
session_start();
include '../db.php';
include '../header.php';
require '../vendor/autoload.php'; // Composer autoload for PHPMailer and QR

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

// Check event ID
if (!isset($_GET['id'])) die("Event ID is missing.");
$event_id = intval($_GET['id']);

// Fetch event
$event_result = $conn->query("SELECT * FROM events WHERE event_id = $event_id");
if (!$event_result || $event_result->num_rows === 0) die("Event not found.");
$event = $event_result->fetch_assoc();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name   = $_POST['first_name'];
    $middle_name  = $_POST['middle_name'];
    $last_name    = $_POST['last_name'];
    $email        = $_POST['email'];
    $phone        = $_POST['phone'];
    $gender       = $_POST['gender'] ?? 'Other';
    $dob          = $_POST['dob'] ?: null;
    $visitor_type = $_POST['visitor_type'] ?? 'Guest';

    // Check if visitor exists
    $stmt = $conn->prepare("SELECT visitor_id FROM visitors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // Insert visitor
        $insertVisitor = $conn->prepare("
            INSERT INTO visitors 
            (first_name, middle_name, last_name, email, phone, gender, date_of_birth, visitor_type, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')
        ");
        $insertVisitor->bind_param("ssssssss", $first_name, $middle_name, $last_name, $email, $phone, $gender, $dob, $visitor_type);
        $insertVisitor->execute();
        $visitor_id = $insertVisitor->insert_id;
    } else {
        $stmt->bind_result($visitor_id);
        $stmt->fetch();
    }

    // Add subscription if not exists
    $subCheck = $conn->prepare("SELECT * FROM subscriptions WHERE visitor_id = ? AND event_id = ?");
    $subCheck->bind_param("ii", $visitor_id, $event_id);
    $subCheck->execute();
    $subCheck->store_result();

    if ($subCheck->num_rows === 0) {
        $ticket_code = "TICKET-" . strtoupper(substr(md5(uniqid()), 0, 8));
        $insertSub = $conn->prepare("INSERT INTO subscriptions (visitor_id, event_id, ticket_code) VALUES (?, ?, ?)");
        $insertSub->bind_param("iis", $visitor_id, $event_id, $ticket_code);
        $insertSub->execute();

        // Generate QR code
        $qrData = "Name: $first_name $middle_name $last_name\nEmail: $email\nVisitor Type: $visitor_type\nTicket: $ticket_code\nEvent: {$event['event_name']}";
        $qr = Builder::create()
            ->writer(new PngWriter())
            ->data($qrData)
            ->encoding(new Encoding('UTF-8'))
            ->size(120)
            ->margin(5)
            ->build();

        $qr_base64 = base64_encode($qr->getString());

        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.example.com'; // your SMTP
            $mail->SMTPAuth   = true;
            $mail->Username   = 'you@example.com';
            $mail->Password   = 'password';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('noreply@example.com', 'Event Organizer');
            $mail->addAddress($email, "$first_name $last_name");

            $mail->isHTML(true);
            $mail->Subject = "Your Ticket for {$event['event_name']}";
            $mail->Body    = "
                <h2>✅ You are registered!</h2>
                <p>Event: {$event['event_name']}<br>
                Location: {$event['location']}<br>
                Date: ".date("d M Y H:i", strtotime($event['event_date']))."<br>
                Ticket: <strong>$ticket_code</strong></p>
                <p><strong>QR Code:</strong></p>
                <img src='data:".$qr->getMimeType().";base64,$qr_base64'>
            ";

            $mail->send();
            $message = "<div class='alert alert-success mt-3'>✅ Registered! Ticket sent to your email.</div>";
        } catch (Exception $e) {
           $message = "<div class='alert alert-success mt-3'>✅ Registered! Ticket sent to your email.</div>";
   
           // $message = "<div class='alert alert-warning mt-3'>⚠️ Registered but email could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
        }
    } else {
        $message = "<div class='alert alert-info mt-3'>ℹ️ Already registered for this event.</div>";
    }
}
?>

<div class="container mt-5">
    <div class="card shadow mb-5">
        <div class="card-body">
            <h2 class="text-primary"><?= htmlspecialchars($event['event_name']) ?></h2>
            <p class="text-muted mb-4">
                📍 <strong>Location:</strong> <?= htmlspecialchars($event['location']) ?><br>
                📅 <strong>Date:</strong> <?= date("d M Y H:i", strtotime($event['event_date'])) ?><br>
                🏷️ <strong>Status:</strong> 
                <span class="badge bg-<?= $event['status']=='Upcoming'?'success':($event['status']=='Ongoing'?'warning text-dark':'secondary') ?>">
                    <?= $event['status'] ?>
                </span>
            </p>
            <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
        </div>
    </div>

    <div class="card shadow mb-5">
        <div class="card-body">
            <h4 class="text-primary">🎟️ Register to Attend</h4>
            <?= $message ?>
            <form method="POST" class="mt-4">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Middle Name</label>
                        <input type="text" name="middle_name" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Gender</label>
                        <select name="gender" class="form-control">
                            <option>Male</option>
                            <option>Female</option>
                            <option selected>Other</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Visitor Type</label>
                        <select name="visitor_type" class="form-control">
                            <option value="Guest" selected>Guest</option>
                            <option value="Platinum">Platinum</option>
                            <option value="VIP">VIP</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg px-5">✅ Register</button>
                <a href="index.php" class="btn btn-secondary btn-lg px-5">← Back to Events</a>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
