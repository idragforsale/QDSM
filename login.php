<?php
session_start();

// ✅ 2. กำหนดค่าที่อนุญาต
$allowedHash  = "1ac5930b480f3a837c41a3558fd07d5382ccdaa4a804303df486696825619c72";
$allowedHcode = "10706";

// ✅ 3. ดึงข้อมูลจาก session
$profile = $_SESSION['provider_profile'];

$hashCid = $profile['hash_cid'] ?? null;
$hcode   = $profile['organization'][0]['hcode'] ?? null;

// ✅ 4. ถ้าไม่ตรงทั้งคู่ → กลับไป login
if ($hashCid == $allowedHash && $hcode == $allowedHcode) {
    header("Location: manage");
    exit;
} else {
    if ($hcode == $allowedHcode) {
        header("Location: index");
        exit;
    }
}

require "inc/config.php";
$login_url = 'https://it.nkh.go.th/provider-authen/redirect?project=https://it.nkh.go.th/qdsm/direct';
?>

<!DOCTYPE html>
<html lang="th" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ✅ SEO Meta Tags -->
    <title>เข้าสู่ระบบ QDSM - ระบบบริหารจัดการเอกสารคุณภาพ</title>
    <meta name="title" content="เข้าสู่ระบบ QDSM - ระบบบริหารจัดการเอกสารคุณภาพ">
    <meta name="description" content="เข้าสู่ระบบ QDSM (Quality Document System Management) ด้วย Provider ID เพื่อใช้งานระบบจัดการเอกสารคุณภาพของโรงพยาบาลหนองคาย">
    <link rel="canonical" href="https://it.nkh.go.th/qdsm/">

    <!-- ✅ Favicon -->
    <link rel="icon" href="/qdsm/favicon.ico" type="image/x-icon">

    <!-- ✅ Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://it.nkh.go.th/qdsm/">
    <meta property="og:title" content="เข้าสู่ระบบ QDSM - ระบบบริหารจัดการเอกสารคุณภาพ">
    <meta property="og:description" content="ระบบบริหารจัดการเอกสารคุณภาพ QDSM สำหรับเจ้าหน้าที่โรงพยาบาลหนองคาย">
    <meta property="og:image" content="https://it.nkh.go.th/qdsm/dist/images/preview.jpg">

    <!-- ✅ Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://it.nkh.go.th/qdsm/">
    <meta property="twitter:title" content="เข้าสู่ระบบ QDSM - ระบบบริหารจัดการเอกสารคุณภาพ">
    <meta property="twitter:description" content="ระบบบริหารจัดการเอกสารคุณภาพ QDSM สำหรับเจ้าหน้าที่โรงพยาบาลหนองคาย">
    <meta property="twitter:image" content="https://it.nkh.go.th/qdsm/dist/images/preview.jpg">

    <!-- ✅ Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "โรงพยาบาลหนองคาย",
        "url": "https://it.nkh.go.th/qdsm/",
        "logo": "https://it.nkh.go.th/qdsm/dist/images/logo.png",
        "sameAs": [
            "https://www.facebook.com/nkphospital"
        ]
    }
    </script>

    <!-- Google Fonts - Kanit -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500&family=Niramit:wght@100;200;300;400;500&family=Sarabun:wght@100;200;300;400;500&family=Prompt:wght@100;200;300;400;500&family=Mitr:wght@100;200;300;400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Animate CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<!-- Custom CSS -->
	<link href="/qdsm/dist/css/styles.css?v=<?=filemtime($_SERVER['DOCUMENT_ROOT'].'/qdsm/dist/css/styles.css') ?>" rel="stylesheet">

    <!-- JS Libraries (บางตัวโหลดใน head เพราะต้องใช้ก่อนโหลด body) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="body-center">

    <!-- ✅ ซ่อน H1 ไว้สำหรับ SEO -->
    <h1 class="visually-hidden">เข้าสู่ระบบ QDSM - ระบบบริหารจัดการเอกสารคุณภาพโรงพยาบาลหนองคาย</h1>

    <!-- Theme Customizer Gadget -->
    <?php include 'theme-gadget.php'; ?>
    
    <!-- Login Container -->
    <div class="login-container">
        <div class=""> <!--class=login-card"-->
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="bi bi-hospital"></i>
                </div>
                <div class="login-title">ยินดีต้อนรับ</div>
                <div>เข้าสู่ระบบ QDSM : Quality Document System Management</div>
            </div>

            <!-- Login Button (เปลี่ยนจาก button → a เพื่อ SEO) -->
            <a href="<?= $login_url ?>" class="btn btn-login">
                เข้าสู่ระบบด้วย Provider ID
                <i class="bi bi-arrow-right"></i>
            </a>

            <!-- Divider -->
            <div class="divider">
                <span>ติดต่อ</span>
            </div>

            <!-- Register Link -->
            <div class="register-link">
                แจ้งปัญหาการใช้งาน ติดต่อ <a href="tel:749">QIC</a>
            </div>
        </div>
    </div>
</body>

</html>
