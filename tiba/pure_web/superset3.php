<?php

// Define the path to the CSV file
$csvFilePath = 'roles.csv';

// Open the CSV file for reading
$file = fopen($csvFilePath, 'r');

// Skip the header row
fgetcsv($file);

// Read the CSV file line by line
while (($line = fgetcsv($file)) !== false) {
    // Get the values from the CSV line
    $id = $line[0];
    $user_id = $line[1];
    $role_id = $line[2];

    // Generate the SQL insert query
    $query = "<br>INSERT INTO ab_user_role (id, user_id,role_id) " .
              "VALUES ('$id', '$user_id', '$role_id');";

    // Output the generated query
    echo $query . "\n";
}

// Close the file
fclose($file);
?>
