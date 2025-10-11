<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../config.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$type = $_GET['type'] ?? 'excel';

// --- ดึงข้อมูลจากฐานข้อมูล ---
$sql = "SELECT dep_code, dep_name_th, dep_name_en, dep_name_short FROM tb_department ORDER BY dep_code";
$result = $conn->query($sql);

$data = [];
while($row = $result->fetch_assoc()){
    $data[] = $row;
}

// --- ส่ง JSON ---
if($type === 'json'){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status'=>'success','data'=>$data], JSON_UNESCAPED_UNICODE);
    exit;
}

// --- ส่ง Excel ---
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1','รหัสแผนก');
$sheet->setCellValue('B1','ชื่อแผนก (TH)');
$sheet->setCellValue('C1','ชื่อแผนก (EN)');
$sheet->setCellValue('D1','ชื่อย่อ');

$rowNum = 2;
foreach($data as $row){
    $sheet->setCellValue("A$rowNum",$row['dep_code']);
    $sheet->setCellValue("B$rowNum",$row['dep_name_th']);
    $sheet->setCellValue("C$rowNum",$row['dep_name_en']);
    $sheet->setCellValue("D$rowNum",$row['dep_name_short']);
    $rowNum++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="department.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
