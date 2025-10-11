<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../config.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$type = $_GET['type'] ?? 'excel';

// ดึงข้อมูล
$sql = "SELECT type_code, type_name, type_short_name FROM tb_type ORDER BY type_code";
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
$sheet->setCellValue('A1','รหัสประเภท');
$sheet->setCellValue('B1','ชื่อประเภท');
$sheet->setCellValue('C1','ชื่อย่อ');

$rowNum = 2;
foreach($data as $row){
    $sheet->setCellValue("A$rowNum",$row['type_code']);
    $sheet->setCellValue("B$rowNum",$row['type_name']);
    $sheet->setCellValue("C$rowNum",$row['type_short_name']);
    $rowNum++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="type.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
