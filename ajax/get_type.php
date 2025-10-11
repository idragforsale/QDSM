<?php
require '../config.php';
header('Content-Type: application/json');

$columns = ['type_code','type_name','type_short_name'];

$draw = intval($_POST['draw'] ?? 1);
$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$searchValue = $_POST['search']['value'] ?? '';
$orderColumn = $_POST['order'][0]['column'] ?? 0;
$orderDir = $_POST['order'][0]['dir'] ?? 'asc';

// Base query
$sql = "SELECT type_code,type_name,type_short_name FROM tb_type WHERE 1=1 ";
$params = [];

if(!empty($searchValue)){
    $sql .= " AND (type_code LIKE ? OR type_name LIKE ? OR type_short_name LIKE ?)";
    $searchParam = "%$searchValue%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

// Count filtered records
$stmt = $conn->prepare($sql);
if(count($params)){
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$stmt->store_result();
$recordsFiltered = $stmt->num_rows;

// Add order and limit
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

$data=[];
while($row = $result->fetch_assoc()){
    $data[] = [
        $row['type_code'],
        $row['type_name'],
        $row['type_short_name'],
        '<button type="button" class="btn btn-sm btn-warning me-1" onclick="editType(\''.$row['type_code'].'\',\''.$row['type_name'].'\',\''.$row['type_short_name'].'\')"><i class="bi bi-pencil"></i></button>
         <button type="button" class="btn btn-sm btn-danger" onclick="deleteType(\''.$row['type_code'].'\')"><i class="bi bi-trash"></i></button>'
    ];
}

$totalRecords = $conn->query("SELECT COUNT(*) as c FROM tb_type")->fetch_assoc()['c'];

echo json_encode([
    "draw"=>$draw,
    "recordsTotal"=>$totalRecords,
    "recordsFiltered"=>$recordsFiltered,
    "data"=>$data
]);
?>
