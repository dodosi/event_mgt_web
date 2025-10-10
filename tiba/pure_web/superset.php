<?php

// Superset API endpoint for user creation
$apiUrl = 'http://173.212.221.182:8088/api/v1/user/';

// Superset admin credentials
$adminUsername = 'admin';
$adminPassword = 'admin';
$provider= 'db';
$refresh= true;
// User details to be created
$newUser = [
    'username' => 'new_user',
    'first_name' => 'New',
    'last_name' => 'User',
    'email' => 'new_user@example.com',
    'password' => 'new_user_password',
    'roles' => ['Public'],
    // Additional attributes if needed
    'active' => true,
    // 'changed_on' => '2023-01-01T00:00:00Z'
];

// Authenticate with Superset API
$authUrl = 'http://173.212.221.182:8088/api/v1/security/login';
$authData = json_encode(['username' => $adminUsername, 'password' => $adminPassword,'provider'=>$provider,'refresh'=>true]);
$authHeaders = ['Content-Type: application/json'];

$authCurl = curl_init($authUrl);
curl_setopt($authCurl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($authCurl, CURLOPT_HTTPHEADER, $authHeaders);
curl_setopt($authCurl, CURLOPT_POST, true);
curl_setopt($authCurl, CURLOPT_POSTFIELDS, $authData);

$authResponse = curl_exec($authCurl);
$authStatusCode = curl_getinfo($authCurl, CURLINFO_HTTP_CODE);

curl_close($authCurl);

if ($authStatusCode === 200) {
    $accessToken = json_decode($authResponse)->access_token;

    // Create user using Superset API
    $createHeaders = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ];

    $createCurl = curl_init($apiUrl);
    curl_setopt($createCurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($createCurl, CURLOPT_HTTPHEADER, $createHeaders);
    curl_setopt($createCurl, CURLOPT_POST, true);
    curl_setopt($createCurl, CURLOPT_POSTFIELDS, json_encode($newUser));

    $createResponse = curl_exec($createCurl);
    $createStatusCode = curl_getinfo($createCurl, CURLINFO_HTTP_CODE);

    curl_close($createCurl);

    if ($createStatusCode === 201) {
        echo 'User created successfully.';
    } else {
        echo 'Failed to create user. Status code: ' . $createStatusCode;
    }
} else {
    echo 'Authentication failed. Status code: ' . $authStatusCode;
}