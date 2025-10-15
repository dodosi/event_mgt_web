<?php
require 'vendor/autoload.php'; // Make sure PHPMailer is installed via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // SMTP settings for Hostinger
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'events@ukudox.com';
    $mail->Password   = 'Ukudox@2025';
    $mail->SMTPSecure = 'tls'; // use 'ssl' if using port 465
    $mail->Port       = 587;   // 465 for SSL

    // Email content
    $mail->setFrom('events@ukudox.com', 'Event Test');
    $mail->addAddress('ukudox@gmail.com', 'Test Recipient');

    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHP';
    $mail->Body    = '<h3>✅ This is a test email from Hostinger SMTP</h3><p>If you received this, it works!</p>';

    $mail->send();
    echo "✅ Test email sent successfully!";
} catch (Exception $e) {
    echo "❌ Email could not be sent. Error: {$mail->ErrorInfo}";
}
