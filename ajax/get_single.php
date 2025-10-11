<?php
include '../config.php';

$response = array();

try {
    $id = $_POST['id'];
    
    $sql = "SELECT * FROM tb_document WHERE doc_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Format dates for datetime-local input
        if ($row['doc_eft_date']) {
            $row['doc_eft_date'] = date('Y-m-d\TH:i', strtotime($row['doc_eft_date']));
        }
        if ($row['doc_imp_date']) {
            $row['doc_imp_date'] = date('Y-m-d\TH:i', strtotime($row['doc_imp_date']));
        }
        
        $response['status'] = 'success';
        $response['data'] = $row;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'ไม่พบข้อมูล';
    }
    
    mysqli_stmt_close($stmt);
    
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

echo json_encode($response);
?>
