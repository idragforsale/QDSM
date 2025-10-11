<?php
require '../config.php';
header('Content-Type: application/json');

$type_code = $_POST['type_code'] ?? '';

if(empty($type_code)){
    echo json_encode(['status'=>'error','message'=>'ข้อมูลไม่ถูกต้อง']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM tb_type WHERE type_code=?");
$stmt->bind_param('s',$type_code);
if($stmt->execute()){
    echo json_encode(['status'=>'success','message'=>'ลบข้อมูลเรียบร้อย']);
} else {
    echo json_encode(['status'=>'error','message'=>'เกิดข้อผิดพลาด']);
}
?>