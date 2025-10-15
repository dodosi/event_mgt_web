<?php
$password = "test";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash; // Store this $hash in the database
?>