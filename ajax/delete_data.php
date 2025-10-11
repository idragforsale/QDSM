<?php
include '../config.php';

$response = array();

try {
    $id = $_POST['id'];
    
    // Get file name before delete to remove file
    $sqlGetFile = "SELECT doc_file FROM tb_document WHERE doc_id = ?";
    $stmt = mysqli_prepare($conn, $sqlGetFile);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $fileName = $row['doc_file'];
    mysqli_stmt_close($stmt);
    
    // Delete record
    $sql = "DELETE FROM tb_document WHERE doc_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Delete file if exists
        if (!empty($fileName) && file_exists('../uploads/' . $fileName)) {
            unlink('../uploads/' . $fileName);
        }
        
        $response['status'] = 'success';
        $response['message'] = 'ลบข้อมูลสำเร็จ';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'เกิดข้อผิดพลาด: ' . mysqli_error($conn);
    }
    
    mysqli_stmt_close($stmt);
    
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

echo json_encode($response);
?>
