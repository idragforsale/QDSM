<?php
include '../config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = [];

try {
    if (!isset($_POST['action'])) throw new Exception('ไม่พบ action ที่ต้องการ');

    $action = $_POST['action'];
    $type_code = trim($_POST['type_code'] ?? '');
    $type_name = trim($_POST['type_name'] ?? '');
    $type_short_name = trim($_POST['type_short_name'] ?? '');
    $old_type_code = trim($_POST['old_type_code'] ?? '');

    // ✅ แก้ตรงนี้
    if($type_code === '' || $type_name === '' || $type_short_name === ''){
        throw new Exception('กรอกข้อมูลไม่ครบ');
    }

    if($action == 'insert') {
        $sql = "SELECT type_code FROM tb_type WHERE type_code=? OR type_name=? OR type_short_name=?";
        $stmtCheck = $conn->prepare($sql);
        $stmtCheck->bind_param('sss', $type_code, $type_name, $type_short_name);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if($stmtCheck->num_rows > 0){
            throw new Exception('ข้อมูลซ้ำ: รหัส, ชื่อประเภท หรือชื่อย่อ มีอยู่แล้ว');
        }
        $stmtCheck->close();

        $stmt = $conn->prepare("INSERT INTO tb_type(type_code,type_name,type_short_name) VALUES(?,?,?)");
        $stmt->bind_param('sss', $type_code, $type_name, $type_short_name);
        if($stmt->execute()){
            $response = ['status'=>'success','message'=>'เพิ่มข้อมูลเรียบร้อย'];
        } else {
            throw new Exception('เกิดข้อผิดพลาด: '.$conn->error);
        }
    }
    else if($action == 'update') {
        if($old_type_code === '') throw new Exception('ไม่พบรหัสเก่าที่จะอัปเดต');

        $stmt = $conn->prepare("UPDATE tb_type SET type_code=?, type_name=?, type_short_name=? WHERE type_code=?");
        $stmt->bind_param('ssss', $type_code, $type_name, $type_short_name, $old_type_code);
        if($stmt->execute()){
            $response = ['status'=>'success','message'=>'แก้ไขข้อมูลเรียบร้อย'];
        } else {
            throw new Exception('เกิดข้อผิดพลาด: '.$conn->error);
        }
    }
    else throw new Exception('Action ไม่ถูกต้อง');

} catch(Exception $e){
    $response = ['status'=>'error','message'=>$e->getMessage()];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
