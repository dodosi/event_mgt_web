<?php
session_start();
include '../db.php';
include '../nav.php';
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
       // ✅ Generate QR code and save it as a temporary file
        $qr = Builder::create()
            ->writer(new PngWriter())
            ->data($qrData)
            ->encoding(new Encoding('UTF-8'))
            ->size(300) // bigger for email
            ->margin(10)
            ->build();

        $qrPath = __DIR__ . "/temp_qr_" . uniqid() . ".png";
        $qr->saveToFile($qrPath);


        $qr_base64 = base64_encode($qr->getString());

        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'events@ukudox.com';
            $mail->Password   = 'Ukudox@2025';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('events@ukudox.com', 'Event Registration');
            $mail->addAddress($email, "$first_name $last_name");

            // ✅ Embed QR code as inline image
            $mail->addEmbeddedImage($qrPath, 'ticket_qr');

            $mail->isHTML(true);
            $mail->Subject = "🎟️ Your Ticket for {$event['event_name']}";
           $mail->Body = "
                <div style='font-family: Arial, sans-serif; padding: 20px;'>
                    <h2>✅ You are registered!</h2>
                    <p><strong>Event:</strong> {$event['event_name']}</p>
                    <p><strong>Location:</strong> {$event['location']}</p>
                    <p><strong>Date:</strong> " . date("d M Y H:i", strtotime($event['event_date'])) . "</p>
                    <p><strong>Ticket:</strong> <span style='color: #007bff;'>$ticket_code</span></p>
                    <hr>
                    <p>📲 <strong>Your QR Code:</strong></p>
                    <img src='cid:ticket_qr' alt='QR Code' style='width:200px;'>
                    <br><br>

                    <!-- ✅ View Event Details -->
                    <a href='https://events.ukudox.com/events/view.php?id=$event_id' 
                    style='display:inline-block;padding:10px 20px;background:#28a745;color:white;text-decoration:none;border-radius:5px;margin-right:10px;'>
                    🔗 View Event Details
                    </a>

                    <!-- ✅ View QR Online -->
                    <a href='https://events.ukudox.com/events/view_qr.php?visitor_id=$visitor_id' 
                    style='display:inline-block;padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;'>
                    📱 View Your QR Online
                    </a>
                </div>
            ";


            $mail->send();
            $message = "<div class='alert alert-success mt-3'>✅ Registered! Ticket sent to your email.</div>";

            // ✅ Clean up temp file
            unlink($qrPath);

        } catch (Exception $e) {
            $message = "<div class='alert alert-warning mt-3'>⚠️ Registered but email could not be sent. Error: {$mail->ErrorInfo}</div>";
        }

    } else {
        $message = "<div class='alert alert-info mt-3'>ℹ️ Already registered for this event.</div>";
    }
}
?>
<br/><br/><br/>
<div class="container mt-5">
  <div class="row mb-4 align-items-center">
    <div class="col-6">
        <h4 class="text-primary mb-0">📅 Event Management</h4>
    </div>
    <div class="col-6 text-end">
        <a href="index.php" class="btn btn-primary">+ Back to Events</a>
    </div>
  </div>
  <hr/>
<div class="container mt-5">
    <div class="card shadow mb-5">
        <div class="card-body"> 
                <!-- Event Name -->
                <p class="text-muted mb-4">
                    🏷️ <strong>Event Name:</strong> <span class="text-primary fs-4"><?= htmlspecialchars($event['event_name']) ?></span>
                       <br/>
                    📍 <strong>Location:</strong> <?= htmlspecialchars($event['location']) ?><br>
                    📅 <strong>Date:</strong> <?= date("d M Y H:i", strtotime($event['event_date'])) ?><br>
                    🔖 <strong>Status:</strong>  
                    <span class="badge bg-<?= $event['status']=='Upcoming'?'success':($event['status']=='Ongoing'?'warning text-dark':'secondary') ?>">
                        <?= $event['status'] ?>
                    </span>
                </p>

                <!-- Description -->
                <?php if(!empty($event['description'])): ?>
                <p class="text-muted mb-4">
                    ✏️ <strong>Description:</strong>
                    <span class="text-body"><?= nl2br(htmlspecialchars($event['description'])) ?></span>
                </p>
                <?php else: ?>
                <p class="mb-0 text-muted">
                    ✏️ <em>No description provided.</em>
                </p>
                <?php endif; ?>
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
