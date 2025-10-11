<?php
include '../config.php';
$qic_id = $_GET['qic_id'] ?? '';
$response = ['status' => 'error', 'data' => []];

if ($qic_id != '') {
    $sql = "SELECT * FROM tb_document_edit WHERE edit_qic_no = ? ORDER BY edit_round ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $qic_id); // s = string
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    $response['status'] = 'success';
    $response['data'] = $data;
}

header('Content-Type: application/json');
echo json_encode($response);

?>
