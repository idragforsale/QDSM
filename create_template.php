<?php
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Color;

// ✅ สร้าง Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// ✅ ตั้งชื่อ Sheet
$sheet->setTitle('Import Template');

// ✅ ตั้งค่าเริ่มต้นทั้งชีทให้เป็น TH SarabunPSK
$spreadsheet->getDefaultStyle()->getFont()->setName('TH SarabunPSK')->setSize(14);

// === Header (แถวที่ 1) ===
$headers = [
    'A1' => 'QIC ID',
    'B1' => 'Type',
    'C1' => 'Code',
    'D1' => 'Topic',
    'E1' => 'Dept',
    'F1' => 'Eff Date',
    'G1' => 'Exp Date'
];

foreach ($headers as $cell => $value) {
    $sheet->setCellValue($cell, $value);
}

// ✅ จัดรูปแบบ Header
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 16,
        'name' => 'TH SarabunPSK'
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FF7B59']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
];

$sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

// ตั้งความสูงของแถว Header
$sheet->getRowDimension(1)->setRowHeight(32);

// === ข้อมูลตัวอย่าง (แถวที่ 2-4) ===
$examples = [
    ['147', '3', 'NK-WI-OPD-147', 'คู่มือการทำงาน OPD', '070', '2025-10-05', '2026-10-05'],
    ['148', '4', 'NK-SD-COC-148', 'เอกสารมาตรฐาน COC', '069', '2025-10-06', '2026-10-06'],
    ['149', '1', 'NK-QM-ADM-149', 'แบบฟอร์ม Admin', '068', '2025-10-07', '2026-10-07']
];

$rowNum = 2;
foreach ($examples as $example) {
    $colNum = 'A';
    foreach ($example as $value) {
        $sheet->setCellValue($colNum . $rowNum, $value);
        $colNum++;
    }
    $rowNum++;
}

// ✅ จัดรูปแบบข้อมูลตัวอย่าง
$dataStyle = [
    'font' => [
        'name' => 'TH SarabunPSK',
        'size' => 14
    ],
    'alignment' => [
        'vertical' => Alignment::VERTICAL_CENTER
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => 'CCCCCC']
        ]
    ]
];

$sheet->getStyle('A2:G4')->applyFromArray($dataStyle);

// === คำอธิบาย (แถวที่ 6 เป็นต้นไป) ===
$sheet->setCellValue('A6', 'คำอธิบาย:');
$sheet->getStyle('A6')->getFont()
    ->setBold(true)
    ->setSize(16)
    ->setColor(new Color('FF7B59'))
    ->setName('TH SarabunPSK');

$descriptions = [
    ['A7', 'QIC ID', 'เลขลำดับ QIC (จำเป็น) - ตัวเลข 1-6 หลัก'],
    ['A8', 'Type', 'ประเภทเอกสาร (จำเป็น) - ตัวเลข 1 หลัก (0=QP, 1=HP, 2=PR, 3=WI, 4=SD, 5=PL, 6=FM)'],
    ['A9', 'Code', 'รหัสเอกสาร (จำเป็น) - รูปแบบ NK-XXX-YYY-ZZZ'],
    ['A10', 'Topic', 'ชื่อเรื่อง/หัวข้อเอกสาร (จำเป็น)'],
    ['A11', 'Dept', 'รหัสแผนก (ไม่จำเป็น) - 3-5 หลัก'],
    ['A12', 'Eff Date', 'วันที่บังคับใช้ (ไม่จำเป็น) - รูปแบบ YYYY-MM-DD'],
    ['A13', 'Exp Date', 'วันที่หมดอายุ (ไม่จำเป็น) - รูปแบบ YYYY-MM-DD']
];

foreach ($descriptions as $desc) {
    $sheet->setCellValue($desc[0], $desc[1]);
    $sheet->setCellValue('B' . substr($desc[0], 1), $desc[2]);
    $sheet->getStyle($desc[0])->getFont()->setBold(true)->setName('TH SarabunPSK');
    $sheet->getStyle('B' . substr($desc[0], 1))->getFont()->setName('TH SarabunPSK');
}

// === หมายเหตุ (แถวที่ 15) ===
$sheet->setCellValue('A15', 'หมายเหตุ:');
$sheet->getStyle('A15')->getFont()
    ->setBold(true)
    ->setSize(16)
    ->setColor(new Color('DC3545'))
    ->setName('TH SarabunPSK');

$notes = [
    'A16' => '1. แถวแรก (แถว 1) เป็น Header ห้ามลบหรือแก้ไข',
    'A17' => '2. ข้อมูลเริ่มจากแถว 2 เป็นต้นไป',
    'A18' => '3. ลบแถวตัวอย่าง (แถว 2-4) ก่อนนำเข้าข้อมูลจริง',
    'A19' => '4. ถ้า Code ซ้ำกับในระบบ จะถูกข้าม',
    'A20' => '5. คอลัมน์ที่ระบุ (จำเป็น) ห้ามเว้นว่าง',
    'A21' => '6. วันที่สามารถใช้รูปแบบ DD/MM/YYYY หรือ YYYY-MM-DD'
];

foreach ($notes as $cell => $note) {
    $sheet->setCellValue($cell, $note);
    $sheet->getStyle($cell)->getFont()
        ->setItalic(true)
        ->setName('TH SarabunPSK')
        ->setSize(14);
}

// === ตั้งความกว้างคอลัมน์ ===
$sheet->getColumnDimension('A')->setWidth(12);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(35);
$sheet->getColumnDimension('E')->setWidth(10);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(15);

// === จัดการคอลัมน์วันที่ ===
$sheet->getStyle('F2:G100')->getNumberFormat()->setFormatCode('yyyy-mm-dd');

// === Freeze หัวตาราง ===
$sheet->freezePane('A2');

// === สร้างไฟล์ ===
$writer = new Xlsx($spreadsheet);
$filename = 'QDSM_Import_Template_' . date('Ymd') . '.xlsx';

// ส่ง header สำหรับดาวน์โหลด
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
