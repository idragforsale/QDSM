<?php
/*
ini_set('display_errors', 1);
error_reporting(E_ALL);
*/
require "inc/config.php";
session_start(); // ✅ เริ่ม session ก่อนใช้งาน


if (!isset($_GET['code'])) {
    echo '<!DOCTYPE html>
    <html lang="th">
    <head>
        <meta charset="UTF-8">
        <title>Redirect</title>
        <style>
            body {
                font-family: "Kanit", sans-serif;
                background-color: #f8f9fa;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 95vh;
                color: #ccc;
                font-size: 1rem;
            }
            .fade-text {
                color: #333;
                font-size: 1rem;
                animation: fadeLoop 3s infinite; /* 3 วินาทีต่อรอบ */
            }
            @keyframes fadeLoop {
                0%   { opacity: 0; }
                25%  { opacity: 1; }
                75%  { opacity: 1; }
                100% { opacity: 0; }
            }
        </style>
        <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;700&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="fade-text">Redirect URL</div>
    </body>
    </html>';
    exit;
}


$code = $_GET['code'];

// ========================
// Step 1: ขอ Access Token จาก Health ID พร้อม debug
// ========================
$token_url = HEALTH_ID_URL . "/api/v1/token";

$post_data = [
    "grant_type" => "authorization_code",
    "code" => $code,
    "redirect_uri" => REDIRECT_URI,
    "client_id" => HEALTH_CLIENT_ID,
    "client_secret" => HEALTH_CLIENT_SECRET,
];

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; PHP script)");

// Debug: เปิด verbose
curl_setopt($ch, CURLOPT_VERBOSE, true);
$verboseLog = fopen('curl_verbose.log', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verboseLog);


curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_CAINFO, "C:/inetpub/wwwroot/certs/cacert.pem");
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);


$response = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error_msg = curl_error($ch);

curl_close($ch);
fclose($verboseLog);

if ($error_msg) {
    die("cURL Error: " . htmlspecialchars($error_msg));
}

$health_token = json_decode($response, true);
if (empty($health_token['data']['access_token'])) {
    die("ไม่สามารถขอ Health ID token ได้: " . $response);
}

$health_access_token = $health_token['data']['access_token'];
// ========================
// Step 2: ขอ Provider Token
// ========================
$provider_token_url = PROVIDER_ID_URL . "/api/v1/services/token";

$post_data = [
    "client_id" => PROVIDER_CLIENT_ID,
    "secret_key" => PROVIDER_SECRET_KEY,
    "token_by" => "Health ID",
    "token" => $health_access_token,
];

$ch = curl_init($provider_token_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; PHP script)");

$response = curl_exec($ch);
$provider_token = json_decode($response, true);
if (empty($provider_token['data']['access_token'])) {
    die("ไม่สามารถขอ Provider ID token ได้: " . $response);
}

$provider_access_token = $provider_token['data']['access_token'];

// ========================
// Step 3: ดึงข้อมูล Profile
// ========================
$profile_url = PROVIDER_ID_URL . "/api/v1/services/profile";
$ch = curl_init($profile_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $provider_access_token,
    "client-id: " . PROVIDER_CLIENT_ID,
    "secret-key: " . PROVIDER_SECRET_KEY,
]);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; PHP script)");
$response = curl_exec($ch);
$profile = json_decode($response, true);
curl_close($ch);

if (empty($profile['data'])) {
    die("ไม่สามารถดึง Profile ได้: " . $response);
}

// ✅ เก็บ profile ลง session
$_SESSION['provider_profile'] = $profile['data'];

// ✅ Redirect ไปหน้า profile.php


header("Location: manage");
exit;
?>