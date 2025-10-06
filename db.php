<?php
$host = "localhost";
$user = "root";
$pass = "sethkevin";
$db   = "event_mgt";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
