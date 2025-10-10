<?php

// Define the path to the CSV file
$csvFilePath = 'mycsv.csv';

// Open the CSV file for reading
$file = fopen($csvFilePath, 'r');

// Skip the header row
fgetcsv($file);

// Read the CSV file line by line
while (($line = fgetcsv($file)) !== false) {
    // Get the values from the CSV line
    $id = $line[0];
    $firstName = $line[1];
    $lastName = $line[2];
    $active = $line[3];
    $email = $line[4];
    $username = $line[5];
    $password = $line[6];

    // Generate the SQL insert query
    $query = "<br>INSERT INTO ab_user (id, first_name, last_name, active, email, username, password) " .
        "VALUES ('$id', '$firstName', '$lastName', $active, '$email', '$username', '$password');";

    // Output the generated query
    echo $query . "\n";
}

// Close the file
fclose($file);
?>
