<?php
// 1. อ่านไฟล์ JS ด้านบนจากไฟล์แยก
$js_code = file_get_contents('dist/js/index.js'); // ← สร้างไฟล์ JS จากโค้ดที่คุณให้มา

// 2. ส่งไปให้ API Minifier บีบ
$url = 'https://www.toptal.com/developers/javascript-minifier/api/raw';
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
    CURLOPT_POSTFIELDS => http_build_query([ "input" => $js_code ])
]);
$minified_js = curl_exec($ch);
curl_close($ch);

// 3. บันทึกเป็นไฟล์ใหม่ (เช่น search_script.min.js)
file_put_contents('dist/js/index.min.js', $minified_js);
echo "✅ Minify เรียบร้อย → index.min.js";
?>
