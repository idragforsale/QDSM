<?php
include '../config.php';

// DataTables server-side processing
$draw = intval($_POST['draw']);
$start = intval($_POST['start']);
$length = intval($_POST['length']);
$searchValue = $_POST['search']['value'] ?? '';

// Search filter values
$main_search = $_POST['main_search'] ?? '';
$qic_search = $_POST['qic_search'] ?? '';
$dept_search = $_POST['dept_search'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$type_filter = $_POST['type_filter'] ?? 'all';

// Map doc_type -> type_name from tb_type
$typeMap = [];
$typeResult = $conn->query("SELECT type_code, type_name FROM tb_type");
if($typeResult && $typeResult->num_rows > 0){
    while($t = $typeResult->fetch_assoc()){
        $typeMap[$t['type_code']] = $t['type_name'];
    }
}

// Base query (DB1)
$sql = "
    SELECT d.*,
           IFNULL(v.view_count,0) AS view_count
    FROM tb_document d
    LEFT JOIN document_views v ON d.doc_id = v.document_id
";

$whereClauses = [];

// DataTables default search
if (!empty($searchValue)) {
    $searchValueEsc = mysqli_real_escape_string($conn, $searchValue);
    $whereClauses[] = "(d.doc_qic_id LIKE '%$searchValueEsc%' 
                        OR d.doc_type LIKE '%$searchValueEsc%'
                        OR d.doc_code LIKE '%$searchValueEsc%'
                        OR d.doc_topic LIKE '%$searchValueEsc%'
                        OR d.doc_dept LIKE '%$searchValueEsc%'
                        OR d.doc_file LIKE '%$searchValueEsc%'
                        OR d.doc_note LIKE '%$searchValueEsc%')";
}

// Main search
if (!empty($main_search)) {
    $main_searchEsc = mysqli_real_escape_string($conn, $main_search);
    $whereClauses[] = "(d.doc_code LIKE '%$main_searchEsc%' 
                        OR d.doc_topic LIKE '%$main_searchEsc%'
                        OR d.doc_dept LIKE '%$main_searchEsc%'
                        OR d.doc_qic_id LIKE '%$main_searchEsc%')";
}

// QIC search
if (!empty($qic_search)) {
    $qic_searchEsc = mysqli_real_escape_string($conn, $qic_search);
    $whereClauses[] = "d.doc_qic_id LIKE '%$qic_searchEsc%'";
}

// Department search (filter by depcode)
if (!empty($dept_search)) {
    $dept_searchEsc = mysqli_real_escape_string($conn, $dept_search);
    $whereClauses[] = "d.doc_dept LIKE '%$dept_searchEsc%'";
}

// Type filter
if ($type_filter !== 'all') {
    $type_filterEsc = mysqli_real_escape_string($conn, $type_filter);
    $whereClauses[] = "FIND_IN_SET('$type_filterEsc', d.doc_type) > 0";
}

// Date range filter
if (!empty($start_date)) {
    $start_dateEsc = mysqli_real_escape_string($conn, $start_date);
    $whereClauses[] = "d.doc_eft_date >= '$start_dateEsc'";
}
if (!empty($end_date)) {
    $end_dateEsc = mysqli_real_escape_string($conn, $end_date);
    $whereClauses[] = "d.doc_eft_date <= '$end_dateEsc'";
}

// Combine WHERE clauses
$whereSQL = '';
if (count($whereClauses) > 0) {
    $whereSQL = " WHERE " . implode(" AND ", $whereClauses);
}

$sqlFiltered = $sql . $whereSQL;

// Get total records (all)
$totalResult = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_document");
$totalRecords = mysqli_fetch_assoc($totalResult)['total'];

// Get filtered records count
$filteredResult = mysqli_query($conn, "SELECT COUNT(*) as filteredTotal FROM tb_document d $whereSQL");
$recordsFiltered = mysqli_fetch_assoc($filteredResult)['filteredTotal'];

// Order functionality
$orderColumn = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
$orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'desc';

$columns = array(
    'd.doc_qic_id',
    'd.doc_type',
    'd.doc_code',
    'd.doc_topic',
    'd.doc_dept',
    'd.doc_eft_date',
    'v.view_count',
    'd.doc_file'
);

if ($orderColumn < count($columns)) {
    $sqlFiltered .= " ORDER BY " . $columns[$orderColumn] . " " . $orderDir;
} else {
    $sqlFiltered .= " ORDER BY d.doc_id DESC";
}

// Limit
$sqlFiltered .= " LIMIT $start, $length";

// Execute query
$result = mysqli_query($conn, $sqlFiltered);
if (!$result) {
    $output = array(
        "draw" => $draw,
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $recordsFiltered,
        "data" => [],
        "error" => "Database query failed: " . mysqli_error($conn)
    );
    echo json_encode($output);
    exit;
}

// --- Step 2: collect depcodes ไป query DB2 ---
$deptCodes = [];
$rows = [];
while ($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
    if (!empty($r['doc_dept'])) {
        $deptCodes[] = $r['doc_dept'];
    }
}
$deptCodes = array_unique($deptCodes);

// Query DB2 หา department name
$deptMap = [];
if (count($deptCodes) > 0) {
    $in = "'" . implode("','", array_map([$conn2, 'real_escape_string'], $deptCodes)) . "'";
    $deptResult = mysqli_query($conn, "SELECT dep_code, dep_name_th FROM tb_department WHERE dep_code IN ($in)");
    while ($d = mysqli_fetch_assoc($deptResult)) {
        $deptMap[$d['dep_code']] = $d['dep_name_th'];
    }
}

// --- Step 3: Build data array ---
$data = [];
foreach ($rows as $row) {
$subArray = [];
$subArray[] = $row['doc_qic_id'];

// ประเภท
$types = explode(',', $row['doc_type']);
$typeNames = [];
foreach($types as $t) {
    $typeNames[] = $typeMap[$t] ?? $t;
}
$subArray[] = implode(', ', $typeNames);

$subArray[] = $row['doc_code'];
$subArray[] = htmlspecialchars($row['doc_topic']);
$subArray[] = $deptMap[$row['doc_dept']] ?? $row['doc_dept']; // ชื่อแผนก
$subArray[] = $row['doc_eft_date'] ? date('d/m/Y', strtotime($row['doc_eft_date'])) : '';

// จำนวนอ่าน พร้อมไอคอน
$subArray[] = '<span class="quick-filter border-0 fw-normal">' . $row['view_count'] . '</div>';

// ปุ่มไฟล์
if ($row['doc_file']) {
    $fileExtension = strtolower(pathinfo($row['doc_file'], PATHINFO_EXTENSION));
    $iconClass = '';
    $btnClass = 'btn-info';
    switch ($fileExtension) {
        case 'pdf': $iconClass='bi-file-earmark-pdf'; $btnClass='btn-danger'; break;
        case 'doc': case 'docx': $iconClass='bi-file-earmark-word'; $btnClass='btn-primary'; break;
        case 'xls': case 'xlsx': $iconClass='bi-file-earmark-excel'; $btnClass='btn-success'; break;
        default: $iconClass='bi-file-earmark'; break;
    }
    $subArray[] = '<button type="button" class="btn btn-sm '.$btnClass.'" onclick="viewDocument(\''.$row['doc_file'].'\', \''.htmlspecialchars($row['doc_topic']).'\')"><i class="bi '.$iconClass.'"></i></button>';
} else {
    $subArray[] = '<span class="text-muted">-</span>';
}

// ปุ่มอ่าน
$subArray[] = '<a href="/qdsm/document/'.$row['doc_id'].'" class="btn btn-sm btn-dark">อ่าน</a>';


$data[] = $subArray;

}

// Output JSON
$output = [
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
];

echo json_encode($output);
?>
