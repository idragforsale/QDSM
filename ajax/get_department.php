<?php
require '../config.php';
header('Content-Type: application/json');

$columns = ['dep_code','dep_name_th','dep_name_en','dep_name_short'];

$draw = intval($_POST['draw'] ?? 1);
$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$searchValue = $_POST['search']['value'] ?? '';
$orderColumn = $_POST['order'][0]['column'] ?? 0;
$orderDir = $_POST['order'][0]['dir'] ?? 'asc';

$sql = "SELECT dep_code, dep_name_th, dep_name_en, dep_name_short FROM tb_department WHERE 1 ";
$params = [];

if(!empty($searchValue)){
    $sql .= " AND (dep_code LIKE ? OR dep_name_th LIKE ? OR dep_name_en LIKE ? OR dep_name_short LIKE ?)";
    $searchParam = "%$searchValue%";
    $params = [$searchParam,$searchParam,$searchParam,$searchParam];
}

$stmt = $conn->prepare($sql);
if(count($params)){
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$stmt->store_result();
$recordsFiltered = $stmt->num_rows;

$sql .= " ORDER BY ".$columns[$orderColumn]." $orderDir LIMIT ?,?";
$params[] = $start;
$params[] = $length;

$stmt = $conn->prepare($sql);
if(count($params)){
    $types = str_repeat('s', count($params)-2) . 'ii';
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while($row = $result->fetch_assoc()){
    $data[] = [
        $row['dep_code'],
        $row['dep_name_th'],
        $row['dep_name_en'],
        $row['dep_name_short'],
        '<button class="btn btn-warning btn-sm" onclick="editDept(\''.$row['dep_code'].'\',\''.$row['dep_name_th'].'\',\''.$row['dep_name_en'].'\',\''.$row['dep_name_short'].'\')">แก้ไข</button>
         <button class="btn btn-danger btn-sm" onclick="deleteDept(\''.$row['dep_code'].'\')">ลบ</button>'
    ];
}

$totalRecords = $conn->query("SELECT COUNT(*) as c FROM tb_department")->fetch_assoc()['c'];

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
]);
?>
