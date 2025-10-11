<?php
session_start();

// ✅ 1. ถ้าไม่มี session → กลับไป login
if (!isset($_SESSION['provider_profile'])) {
    header("Location: login");
    exit;
}

// ✅ 2. กำหนดค่าที่อนุญาต
$allowedHcode = "10706";
	
// ✅ 3. ดึงข้อมูลจาก session
$profile = $_SESSION['provider_profile'];
$hcode   = $profile['organization'][0]['hcode'] ?? null;
	
// ✅ 4. ถ้าไม่ตรงทั้งคู่ → กลับไป login
if ($hcode !== $allowedHcode) {
    header("Location: login");
    exit;
}
?>