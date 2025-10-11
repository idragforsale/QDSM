<?php
include '../config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = array();

try {
    if (!isset($_POST['action'])) throw new Exception('ไม่พบ action ที่ต้องการ');

    $action = $_POST['action'];
    $doc_id = $_POST['doc_id'] ?? '';
    $doc_qic_id = $_POST['doc_qic_id'] ?? '';
    $doc_type = $_POST['doc_type'] ?? '0';
    $doc_code = $_POST['doc_code'] ?? '';
    $doc_topic = $_POST['doc_topic'] ?? '';
    $doc_dept = $_POST['doc_dept'] ?? '';
	$doc_eft_date = !empty($_POST['doc_eft_date']) ? $_POST['doc_eft_date'] : null;
    $doc_imp_date = !empty($_POST['doc_imp_date']) ? $_POST['doc_imp_date'] : date('Y-m-d');
    $doc_note = $_POST['doc_note'] ?? '';

    // ข้อมูล edit history
    $edit_date = !empty($_POST['edit_date']) ? $_POST['edit_date'] : date('Y-m-d');
    $edit_prepared_by = $_POST['edit_prepared_by'] ?? '';
    $edit_reviewed_by = $_POST['edit_reviewed_by'] ?? '';
    $edit_approved_by = $_POST['edit_approved_by'] ?? '';
    $edit_remark = $_POST['edit_remark'] ?? '';

    // ตรวจสอบ switch
    $saveToHistory = isset($_POST['saveToHistory']) && $_POST['saveToHistory'] == 'on' ? true : false;

	if (empty($doc_qic_id) || empty($doc_code) || empty($doc_topic) || empty($doc_eft_date) || empty($doc_type) || empty($doc_dept)) {
		throw new Exception('กรุณากรอกข้อมูลที่จำเป็น: ลำดับ QIC, เลขที่, เรื่อง, วันที่บังคับใช้, ประเภทเอกสาร และหน่วยงาน');
	}


    // Handle file upload
    $uploadedFile = '';
    $currentFile = '';
    if ($action == 'update' && !empty($doc_id)) {
        $sql = "SELECT doc_file FROM tb_document WHERE doc_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $doc_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) $currentFile = $row['doc_file'];
    }

    if (isset($_FILES['doc_file']) && $_FILES['doc_file']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        if (!is_writable($uploadDir)) throw new Exception('โฟลเดอร์ uploads ไม่สามารถเขียนได้');

        $originalFileName = $_FILES['doc_file']['name'];
        $fileSize = $_FILES['doc_file']['size'];
        $tmpName = $_FILES['doc_file']['tmp_name'];

        if ($fileSize > 10*1024*1024) throw new Exception('ขนาดไฟล์ใหญ่เกิน 10MB');

        $fileName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $originalFileName);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ['pdf','doc','docx','xls','xlsx'];
        if (!in_array($fileExt, $allowedTypes)) throw new Exception('ชนิดไฟล์ไม่รองรับ');

        $newFileName = time().'_'.$fileName;
        $targetPath = $uploadDir . $newFileName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            $uploadedFile = $newFileName;
            if ($action=='update' && !empty($currentFile) && file_exists($uploadDir.$currentFile)) {
                unlink($uploadDir.$currentFile);
            }
        } else {
            throw new Exception('ไม่สามารถอัพโหลดไฟล์ได้');
        }
    }

    // --- INSERT ---
    if ($action == 'insert') {
        $sql = "INSERT INTO tb_document (doc_qic_id, doc_type, doc_code, doc_topic, doc_dept, doc_eft_date, doc_imp_date, doc_file, doc_note)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssss", $doc_qic_id, $doc_type, $doc_code, $doc_topic, $doc_dept, $doc_eft_date, $doc_imp_date, $uploadedFile, $doc_note);
        mysqli_stmt_execute($stmt);

        if ($saveToHistory) {
            $sqlEdit = "INSERT INTO tb_document_edit (edit_qic_no, edit_round, edit_date, edit_remark, edit_prepared_by, edit_reviewed_by, edit_approved_by)
                        VALUES (?, 1, ?, ?, ?, ?, ?)";
            $stmtEdit = mysqli_prepare($conn, $sqlEdit);
            mysqli_stmt_bind_param($stmtEdit, "ssssss", $doc_qic_id, $edit_date, $edit_remark, $edit_prepared_by, $edit_reviewed_by, $edit_approved_by);
            mysqli_stmt_execute($stmtEdit);
            mysqli_stmt_close($stmtEdit);
        }

        $response = ['status'=>'success','message'=>'เพิ่มข้อมูลสำเร็จ'];
    }

    // --- UPDATE ---
    else if ($action == 'update') {
        if (empty($doc_id)) throw new Exception('ไม่พบ ID ของข้อมูลที่ต้องการแก้ไข');

        if (!empty($uploadedFile)) {
            $sql = "UPDATE tb_document SET doc_qic_id=?, doc_type=?, doc_code=?, doc_topic=?, doc_dept=?, doc_eft_date=?, doc_imp_date=?, doc_file=?, doc_note=? WHERE doc_id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssssssssi", $doc_qic_id, $doc_type, $doc_code, $doc_topic, $doc_dept, $doc_eft_date, $doc_imp_date, $uploadedFile, $doc_note, $doc_id);
        } else {
            $sql = "UPDATE tb_document SET doc_qic_id=?, doc_type=?, doc_code=?, doc_topic=?, doc_dept=?, doc_eft_date=?, doc_imp_date=?, doc_note=? WHERE doc_id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssssi", $doc_qic_id, $doc_type, $doc_code, $doc_topic, $doc_dept, $doc_eft_date, $doc_imp_date, $doc_note, $doc_id);
        }
        mysqli_stmt_execute($stmt);

        if ($saveToHistory) {
            $sqlRound = "SELECT MAX(edit_round) as max_round FROM tb_document_edit WHERE edit_qic_no = ?";
            $stmtRound = mysqli_prepare($conn, $sqlRound);
            mysqli_stmt_bind_param($stmtRound, "s", $doc_qic_id);
            mysqli_stmt_execute($stmtRound);
            $resultRound = mysqli_stmt_get_result($stmtRound);
            $maxRound = 0;
            if ($row = mysqli_fetch_assoc($resultRound)) $maxRound = intval($row['max_round']);
            $edit_round = $maxRound+1;

            $sqlEdit = "INSERT INTO tb_document_edit (edit_qic_no, edit_round, edit_date, edit_remark, edit_prepared_by, edit_reviewed_by, edit_approved_by)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmtEdit = mysqli_prepare($conn, $sqlEdit);
            mysqli_stmt_bind_param($stmtEdit, "sisssss", $doc_qic_id, $edit_round, $edit_date, $edit_remark, $edit_prepared_by, $edit_reviewed_by, $edit_approved_by);
            mysqli_stmt_execute($stmtEdit);
            mysqli_stmt_close($stmtEdit);
        }

        $response = ['status'=>'success','message'=>'อัพเดทข้อมูลสำเร็จ'];
    }

    else throw new Exception('Action ไม่ถูกต้อง');

} catch(Exception $e) {
    $response = ['status'=>'error','message'=>$e->getMessage()];
    if (!empty($uploadedFile) && file_exists('../uploads/'.$uploadedFile)) unlink('../uploads/'.$uploadedFile);
}

header('Content-Type: application/json');
echo json_encode($response);
