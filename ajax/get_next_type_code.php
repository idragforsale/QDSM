<?php
require '../config.php';
header('Content-Type: application/json');

try {
    $row = $conn->query("SELECT MAX(CAST(type_code AS UNSIGNED)) AS max_code FROM tb_type")->fetch_assoc();
    $max = $row['max_code'] ?? 0;
    $next = $max + 1;

    $response = ['status'=>'success','next_code'=>$next];
} catch(Exception $e){
    $response = ['status'=>'error','message'=>$e->getMessage()];
}

echo json_encode($response);
