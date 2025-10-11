<?php
include '../config.php';

// DataTables server-side processing
$draw        = intval($_POST['draw']);
$start       = intval($_POST['start']);
$length      = intval($_POST['length']);
$searchValue = $_POST['search']['value'] ?? '';

// Filter values
$start_date  = $_POST['start_date'] ?? '';
$end_date    = $_POST['end_date'] ?? '';
$type_code   = $_POST['type_code'] ?? '';

// Escape input
$searchValue = mysqli_real_escape_string($conn, $searchValue);
$type_code   = mysqli_real_escape_string($conn, $type_code);
$start_date  = mysqli_real_escape_string($conn, $start_date);
$end_date    = mysqli_real_escape_string($conn, $end_date);

// --- ดึง map ของประเภทเอกสาร ---
$typeMap = [];
$typeResult = $conn->query("SELECT type_code, type_name FROM tb_type");
if($typeResult && $typeResult->num_rows > 0){
    while($t = $typeResult->fetch_assoc()){
        $typeMap[$t['type_code']] = $t['type_name'];
    }
}

// Base query
$sql      = "SELECT * FROM tb_document";
$sqlCount = "SELECT COUNT(*) as total FROM tb_document";

// Build WHERE clauses
$whereClauses = [];
if ($searchValue !== '') {
    $whereClauses[] = "(doc_qic_id LIKE '%$searchValue%' 
                        OR doc_type LIKE '%$searchValue%'
                        OR doc_code LIKE '%$searchValue%'
                        OR doc_topic LIKE '%$searchValue%'
                        OR doc_dept LIKE '%$searchValue%'
                        OR doc_file LIKE '%$searchValue%')";
}

// Filter by type code
if ($type_code !== '') {
    $whereClauses[] = "doc_type = '$type_code'";
}

// Filter by date range
if ($start_date !== '' && $end_date !== '') {
    $whereClauses[] = "doc_eft_date BETWEEN '$start_date' AND '$end_date'";
}

// Combine WHERE clauses
$whereSQL = '';
if (count($whereClauses) > 0) {
    $whereSQL = " WHERE " . implode(" AND ", $whereClauses);
}

$sql      .= $whereSQL;
$sqlCount .= $whereSQL;

// Total records before filtering
$totalRecordsQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_document");
$totalRecords      = mysqli_fetch_assoc($totalRecordsQuery)['total'];

// Total records after filtering
$filteredResult   = mysqli_query($conn, $sqlCount);
$recordsFiltered  = mysqli_fetch_assoc($filteredResult)['total'];

// Order functionality
$orderColumn = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
$orderDir    = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'desc';

$columns = array(
    'doc_qic_id',
    'doc_type',
    'doc_code',
    'doc_topic',
    'doc_dept',
    'doc_eft_date',
    'file_count',
    'doc_file'
);


if ($orderColumn < count($columns)) {
    $sql .= " ORDER BY " . $columns[$orderColumn] . " " . $orderDir;
} else {
    $sql .= " ORDER BY doc_id DESC";
}

// Limit
$sql .= " LIMIT $start, $length";

// Execute query
$result = mysqli_query($conn, $sql);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $subArray = array();
	$subArray[] = $row['doc_qic_id'];      // ลำดับ QIC
	$typeDisplay = $typeMap[$row['doc_type']] ?? '-';
	$subArray[] = $typeDisplay;            // ประเภท
	$subArray[] = $row['doc_code'];
	$subArray[] = htmlspecialchars($row['doc_topic']);

    // แปลงรหัสหน่วยงานเป็นชื่อหน่วยงาน
    $deptName = '-';
    if(!empty($row['doc_dept'])) {
        $stmt = $conn->prepare("SELECT dep_name_th FROM tb_department WHERE dep_code = ?");
        $stmt->bind_param("s", $row['doc_dept']);
        $stmt->execute();
        $stmt->bind_result($department);
        if($stmt->fetch()){
            $deptName = $department;
        }
        $stmt->close();
    }
    $subArray[] = $deptName;

    // วันที่บังคับใช้
    $subArray[] = $row['doc_eft_date'] ? date('d/m/Y', strtotime($row['doc_eft_date'])) : '';

    // --- นับจำนวนแก้เอกสาร ---
    $editCount = 0;
    if(!empty($row['doc_qic_id'])) {
        $stmt2 = $conn->prepare("SELECT COUNT(*) FROM tb_document_edit WHERE edit_qic_no = ?");
        $stmt2->bind_param("s", $row['doc_qic_id']);
        $stmt2->execute();
        $stmt2->bind_result($editCount);
        $stmt2->fetch();
        $stmt2->close();
    }
	$subArray[] = '<span class="quick-filter border-0 fw-normal">' . $editCount . '</div>';

    // ไฟล์
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
        $subArray[] = '<a href="uploads/'.$row['doc_file'].'" target="_blank" class="btn btn-sm '.$btnClass.'"><i class="bi '.$iconClass.'"></i></a>';
    } else {
        $subArray[] = '<span class="text-muted">-</span>';
    }

    // Action buttons
    $subArray[] = '<div class="btn-group-action">
                    <button type="button" class="btn btn-sm btn-warning me-1" onclick="editData('.$row['doc_id'].')">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteData('.$row['doc_id'].')">
                        <i class="bi bi-trash"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-info" onclick="openEditHistoryModal('.$row['doc_qic_id'].')">
                        <i class="bi bi-clock-history"></i>
                    </button>
                   </div>';

    $data[] = $subArray;
}

$output = array(
    "draw"            => $draw,
    "recordsTotal"    => intval($totalRecords),
    "recordsFiltered" => intval($recordsFiltered),
    "data"            => $data
);

echo json_encode($output);
?>
