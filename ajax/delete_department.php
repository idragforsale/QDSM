<?php
require '../config.php';
header('Content-Type: application/json');

$dep_code = $_POST['dep_code'] ?? '';
if(!$dep_code){
    echo json_encode(['status'=>'error','message'=>'ไม่พบรหัสแผนก']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM tb_department WHERE dep_code=?");
$stmt->bind_param("s",$dep_code);
if($stmt->execute()){
    echo json_encode(['status'=>'success','message'=>'ลบแผนกเรียบร้อย']);
} else {
    echo json_encode(['status'=>'error','message'=>'ลบแผนกไม่สำเร็จ']);
}
?>