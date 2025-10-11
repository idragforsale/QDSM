<?php
require '../config.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? 'insert';
$dep_code = $_POST['dep_code'] ?? '';
$dep_name_th = $_POST['dep_name_th'] ?? '';
$dep_name_en = $_POST['dep_name_en'] ?? '';
$dep_name_short = $_POST['dep_name_short'] ?? '';
$old_dep_code = $_POST['old_dep_code'] ?? '';

if(!$dep_code || !$dep_name_th || !$dep_name_en || !$dep_name_short){
    echo json_encode(['status'=>'warning','message'=>'กรอกข้อมูลไม่ครบ']);
    exit;
}

// ตรวจสอบชื่อซ้ำ
if($action==='insert'){
    $stmt = $conn->prepare("SELECT COUNT(*) as c FROM tb_department WHERE dep_name_th=? OR dep_name_short=?");
    $stmt->bind_param("ss",$dep_name_th,$dep_name_short);
} else {
    $stmt = $conn->prepare("SELECT COUNT(*) as c FROM tb_department WHERE (dep_name_th=? OR dep_name_short=?) AND dep_code<>?");
    $stmt->bind_param("sss",$dep_name_th,$dep_name_short,$old_dep_code);
}
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
if($res['c']>0){
    echo json_encode(['status'=>'warning','message'=>'ชื่อแผนกหรือชื่อย่อซ้ำ']);
    exit;
}

if($action==='insert'){
    $stmt = $conn->prepare("INSERT INTO tb_department(dep_code,dep_name_th,dep_name_en,dep_name_short) VALUES(?,?,?,?)");
    $stmt->bind_param("ssss",$dep_code,$dep_name_th,$dep_name_en,$dep_name_short);
    $stmt->execute();
    echo json_encode(['status'=>'success','message'=>'เพิ่มแผนกเรียบร้อย']);
} else {
    $stmt = $conn->prepare("UPDATE tb_department SET dep_code=?, dep_name_th=?, dep_name_en=?, dep_name_short=? WHERE dep_code=?");
    $stmt->bind_param("sssss",$dep_code,$dep_name_th,$dep_name_en,$dep_name_short,$old_dep_code);
    $stmt->execute();
    echo json_encode(['status'=>'success','message'=>'แก้ไขแผนกเรียบร้อย']);
}
?>