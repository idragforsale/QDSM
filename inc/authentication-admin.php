<?php
session_start();

// ✅ 1. ถ้าไม่มี session → กลับไป login
if (!isset($_SESSION['provider_profile'])) {
    header("Location: login");
    exit;
}

// ✅ 2. กำหนดค่าที่อนุญาต
$allowedHash  = "1ac5930b480f3a837c41a3558fd07d5382ccdaa4a804303df486696825619c72";
$allowedHcode = "10706";

// ✅ 3. ดึงข้อมูลจาก session
$profile = $_SESSION['provider_profile'];

$hashCid = $profile['hash_cid'] ?? null;
$hcode   = $profile['organization'][0]['hcode'] ?? null;

// ✅ 4. ถ้าไม่ตรงทั้งคู่ → กลับไป login
if ($hashCid !== $allowedHash || $hcode !== $allowedHcode) {
    header("Location: login");
    exit;
}

?>