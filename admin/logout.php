<?php
    session_start();

    // Destroy all session data
    session_unset();
    session_destroy();

    // Redirect to login page
     //header("Location: login.php");
     header("Location: ../home/");
    exit;
?>
