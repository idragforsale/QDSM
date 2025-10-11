<?php
header('Content-Type: application/json');
include '../config.php'; // เชื่อมฐานข้อมูล

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// ดึงข้อมูลรวม doc_type และ total โดยตรง
$sql = "SELECT t.type_name, t.type_code, COUNT(d.doc_type) AS total
        FROM tb_type t
        LEFT JOIN tb_document d 
          ON d.doc_type = t.type_code 
         AND YEAR(d.doc_imp_date) = ?
        GROUP BY t.type_code, t.type_name
        ORDER BY t.type_code ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $year);
$stmt->execute();
$result = $stmt->get_result();

$stats = [];
while($row = $result->fetch_assoc()) {
    $stats[] = [
        'type_name' => $row['type_name'],
        'total' => intval($row['total'])
    ];
}

echo json_encode($stats);
?>
