<?php
session_start();
include '../config.php';

require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// ตั้งค่า header สำหรับ streaming
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');
ob_implicit_flush(true);

// ฟังก์ชันส่ง log
function sendLog($message, $type = 'info') {
    echo json_encode(['log' => $message, 'type' => $type]) . "\n";
    if (ob_get_level() > 0) ob_flush();
    flush();
}

// ฟังก์ชันอัพเดต progress
function updateProgress($percent, $message = '') {
    echo json_encode(['progress' => $percent, 'message' => $message]) . "\n";
    if (ob_get_level() > 0) ob_flush();
    flush();
}

// ฟังก์ชันส่งผลลัพธ์
function sendComplete($success, $skipped, $errors) {
    echo json_encode([
        'complete' => true,
        'success' => $success,
        'skipped' => $skipped,
        'errors' => $errors
    ]) . "\n";
    if (ob_get_level() > 0) ob_flush();
    flush();
}

// ตรวจสอบการอัพโหลดไฟล์
if (!isset($_FILES['file'])) {
    sendLog('ไม่พบไฟล์ที่อัพโหลด', 'error');
    exit;
}

$file = $_FILES['file'];
$uploadDir = '../uploads/temp/';

// สร้างโฟลเดอร์ถ้ายังไม่มี
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// ย้ายไฟล์ไปยังโฟลเดอร์ชั่วคราว
$fileName = time() . '_' . basename($file['name']);
$uploadPath = $uploadDir . $fileName;

if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
    sendLog('ไม่สามารถอัพโหลดไฟล์ได้', 'error');
    exit;
}

sendLog('อัพโหลดไฟล์สำเร็จ: ' . $file['name'], 'success');
updateProgress(10, 'กำลังอ่านไฟล์...');

try {
    // โหลด spreadsheet
    $spreadsheet = IOFactory::load($uploadPath);
    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestRow();
    
    sendLog("พบข้อมูล {$highestRow} แถว", 'info');
    updateProgress(20, 'เริ่มต้นการนำเข้าข้อมูล...');
    
    $successCount = 0;
    $skipCount = 0;
    $errorCount = 0;
    
    // เริ่มจากแถว 2 (ข้าม header)
    for ($row = 2; $row <= $highestRow; $row++) {
        try {
            // อ่านข้อมูลจากแต่ละคอลัมน์
            // คอลัมน์: A=QIC ID, B=Type, C=Code, D=Topic, E=Dept, F=Eff Date, G=Exp Date
            $qicId = trim($sheet->getCell("A{$row}")->getValue());
            $type = trim($sheet->getCell("B{$row}")->getValue());
            $code = trim($sheet->getCell("C{$row}")->getValue());
            $topic = trim($sheet->getCell("D{$row}")->getValue());
            $dept = trim($sheet->getCell("E{$row}")->getValue());
            $effDate = $sheet->getCell("F{$row}")->getValue();
            $expDate = $sheet->getCell("G{$row}")->getValue();
            
            // ตรวจสอบข้อมูลที่จำเป็น
            if (empty($qicId) || empty($code) || empty($topic)) {
                sendLog("แถว {$row}: ข้ามเนื่องจากข้อมูลไม่ครบ", 'warning');
                $skipCount++;
                continue;
            }
            
            // แปลงวันที่จาก Excel format
            $effDateFormatted = null;
            if (!empty($effDate)) {
                if (is_numeric($effDate)) {
                    // Excel serial date
                    $effDateFormatted = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($effDate)->format('Y-m-d');
                } else {
                    $effDateFormatted = date('Y-m-d', strtotime($effDate));
                }
            }
            
            $expDateFormatted = null;
            if (!empty($expDate)) {
                if (is_numeric($expDate)) {
                    $expDateFormatted = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($expDate)->format('Y-m-d');
                } else {
                    $expDateFormatted = date('Y-m-d', strtotime($expDate));
                }
            }
            
            // ตรวจสอบว่ามีข้อมูลซ้ำหรือไม่ (เช็คจาก doc_code)
            $checkSql = "SELECT doc_id FROM tb_document WHERE doc_code = ?";
            $checkStmt = mysqli_prepare($conn, $checkSql);
            mysqli_stmt_bind_param($checkStmt, "s", $code);
            mysqli_stmt_execute($checkStmt);
            $checkResult = mysqli_stmt_get_result($checkStmt);
            
            if (mysqli_num_rows($checkResult) > 0) {
                sendLog("แถว {$row}: ข้าม {$code} (มีอยู่ในระบบแล้ว)", 'warning');
                $skipCount++;
                mysqli_stmt_close($checkStmt);
                continue;
            }
            mysqli_stmt_close($checkStmt);
            
            // Insert ข้อมูล
            $sql = "INSERT INTO tb_document (
                        doc_qic_id, 
                        doc_type, 
                        doc_code, 
                        doc_topic, 
                        doc_dept, 
                        doc_eft_date, 
                        doc_exp_date,
                        doc_imp_date,
                        doc_note
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
            
            $stmt = mysqli_prepare($conn, $sql);
            $note = "นำเข้าจาก Excel - " . date('Y-m-d H:i:s');
            
            mysqli_stmt_bind_param($stmt, "ssssssss", 
                $qicId, 
                $type, 
                $code, 
                $topic, 
                $dept, 
                $effDateFormatted, 
                $expDateFormatted,
                $note
            );
            
            if (mysqli_stmt_execute($stmt)) {
                sendLog("แถว {$row}: นำเข้า {$code} สำเร็จ", 'success');
                $successCount++;
            } else {
                sendLog("แถว {$row}: ผิดพลาด - " . mysqli_error($conn), 'error');
                $errorCount++;
            }
            
            mysqli_stmt_close($stmt);
            
        } catch (Exception $e) {
            sendLog("แถว {$row}: ข้อผิดพลาด - " . $e->getMessage(), 'error');
            $errorCount++;
        }
        
        // อัพเดท progress
        $progress = 20 + (($row / $highestRow) * 70);
        updateProgress(round($progress), "กำลังประมวลผลแถว {$row}/{$highestRow}");
        
        // หน่วงเวลานิดหน่อยเพื่อให้เห็น progress
        usleep(50000); // 0.05 วินาที
    }
    
    // ลบไฟล์ชั่วคราว
    unlink($uploadPath);
    
    updateProgress(100, 'เสร็จสิ้น');
    sendLog("=== สรุปผลการนำเข้า ===", 'info');
    sendLog("สำเร็จ: {$successCount} รายการ", 'success');
    sendLog("ข้าม: {$skipCount} รายการ", 'warning');
    sendLog("ผิดพลาด: {$errorCount} รายการ", 'error');
    
    sendComplete($successCount, $skipCount, $errorCount);
    
} catch (Exception $e) {
    sendLog('เกิดข้อผิดพลาด: ' . $e->getMessage(), 'error');
    
    // ลบไฟล์ชั่วคราวถ้ามี
    if (file_exists($uploadPath)) {
        unlink($uploadPath);
    }
    
    sendComplete(0, 0, 1);
}

mysqli_close($conn);
?>