<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../config.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ประเภทการส่งออก: excel หรือ json
$type = $_GET['type'] ?? 'excel';

// ดึงข้อมูลเอกสารทั้งหมด (ปรับ SQL ได้ถ้าต้องการ filter)
$sql = "SELECT d.doc_id, d.doc_code, d.doc_topic, d.doc_type, t.type_name, d.doc_dept, dept.dep_name_th, d.doc_eft_date, d.doc_imp_date
        FROM tb_document d
        LEFT JOIN tb_type t ON d.doc_type = t.type_code
        LEFT JOIN tb_department dept ON d.doc_dept = dept.dep_code
        ORDER BY d.doc_id DESC";
$result = $conn->query($sql);

$data = [];
while($row = $result->fetch_assoc()){
    $data[] = $row;
}

// ส่ง JSON
if($type === 'json'){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status'=>'success','data'=>$data], JSON_UNESCAPED_UNICODE);
    exit;
}

// ส่ง Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// หัวตาราง
$sheet->setCellValue('A1','ID');
$sheet->setCellValue('B1','เลขที่เอกสาร');
$sheet->setCellValue('C1','หัวเรื่องเอกสาร');
$sheet->setCellValue('D1','ประเภทเอกสาร');
$sheet->setCellValue('E1','ชื่อประเภท');
$sheet->setCellValue('F1','รหัสหน่วยงาน');
$sheet->setCellValue('G1','ชื่อหน่วยงาน');
$sheet->setCellValue('H1','วันที่บังคับใช้');
$sheet->setCellValue('I1','วันที่นำเข้าระบบ');

$rowNum = 2;
foreach($data as $row){
    $sheet->setCellValue("A$rowNum",$row['doc_id']);
    $sheet->setCellValue("B$rowNum",$row['doc_code']);
    $sheet->setCellValue("C$rowNum",$row['doc_topic']);
    $sheet->setCellValue("D$rowNum",$row['doc_type']);
    $sheet->setCellValue("E$rowNum",$row['type_name']);
    $sheet->setCellValue("F$rowNum",$row['doc_dept']);
    $sheet->setCellValue("G$rowNum",$row['dep_name_th']);
    $sheet->setCellValue("H$rowNum",$row['doc_eft_date']);
    $sheet->setCellValue("I$rowNum",$row['doc_imp_date']);
    $rowNum++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="document.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
