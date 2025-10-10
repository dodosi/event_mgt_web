<?php
$file=$_GET['file_name'];
$file = 'uploads/'.$file; // Specify the path to your file

if (file_exists($file)) {
    // Set the appropriate headers for the file download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Content-Length: ' . filesize($file));
    header('Cache-Control: private, must-revalidate');
    header('Pragma: public');
    header('Expires: 0');
    
    // Read the file and output it to the browser
    readfile($file);
    exit;
} else {
    // Handle file not found or any other error
    echo 'File not found';
}
?>
