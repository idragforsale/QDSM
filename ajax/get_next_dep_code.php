<?php
require '../config.php';
header('Content-Type: application/json');

// ดึง dep_code สูงสุด
$res = $conn->query("SELECT dep_code FROM tb_department ORDER BY dep_code DESC LIMIT 1");
if($res && $row = $res->fetch_assoc()){
    $last_code = $row['dep_code'];

    // สมมติรหัสเป็นตัวเลข 3 หลัก
    $num = intval($last_code) + 1;

    // ทำให้เป็น 3 หลัก เช่น 001, 002, ...
    $next_code = str_pad($num, 3, '0', STR_PAD_LEFT);

    echo json_encode(['status'=>'success','next_code'=>$next_code]);
} else {
    echo json_encode(['status'=>'error','message'=>'ไม่สามารถดึงรหัสล่าสุดได้']);
}
?>