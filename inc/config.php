<?php
date_default_timezone_set('Asia/Bangkok');
// เลือก Environment: "UAT" หรือ "PRD"
define("ENVIRONMENT", "PRD"); // เปลี่ยนเป็น "UAT" ถ้าต้องการทดสอบ

// URL และ Credentials ขึ้นกับ Environment
if (ENVIRONMENT === "UAT") {
    // Health ID
    define("HEALTH_ID_URL", "https://uat-moph.id.th");
    define("HEALTH_CLIENT_ID", "0197a008-5b0f-7189-be77-c3719e4a1566");
    define("HEALTH_CLIENT_SECRET", "758d8ab78203354fcb5ad578abace8d4718a4896");

    // Provider ID
    define("PROVIDER_ID_URL", "https://uat-provider.id.th");
    define("PROVIDER_CLIENT_ID", "ae133ad8-83bb-41b8-b689-4d3b903a67c4");
    define("PROVIDER_SECRET_KEY", "sIWTuCRieY9YlM6Pj9d7bJmfCgwYb4SX");
} else { // PRD
    // Health ID
    define("HEALTH_ID_URL", "https://moph.id.th");
    define("HEALTH_CLIENT_ID", "0197a008-c5b0-7c61-9acc-9a6a1131726e");
    define("HEALTH_CLIENT_SECRET", "c8d97599706dd4846bce231045e7a51b71c97f6c");

    // Provider ID
    define("PROVIDER_ID_URL", "https://provider.id.th");
    define("PROVIDER_CLIENT_ID", "3e696e3c-7d30-45b9-aa0b-beb5739a9493");
    define("PROVIDER_SECRET_KEY", "EAoroHcPQ4tB2O9OtZZ1C7ZqgxBtyw1n");
}

// Callback URL (ต้องตรงกับที่ลงทะเบียนใน Health ID / Provider ID)
define("REDIRECT_URI", "https://it.nkh.go.th/provider-authen");


    define('MOPH_NOTIFY_BASE', 'https://morpromt2f.moph.go.th');
    define('MOPH_NOTIFY_SEND', MOPH_NOTIFY_BASE . '/api/notify/send');

    // ==== ใส่ Client_ID และ Secret ของคุณ ====
    define('MOPH_NOTIFY_CLIENT_KEY', '12c5009a75de28bbce639bef6e505bc4712585b5');
    define('MOPH_NOTIFY_SECRET_KEY', 'HPILESYZG6UNNIXED4NVQKEKOTMY');


?>
