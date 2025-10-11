<?php
include 'config.php';
$document_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($document_id > 0) {
    // ตรวจสอบว่ามี record ของเอกสารนี้หรือยัง
    $stmt = $conn->prepare("SELECT view_count FROM document_views WHERE document_id=?");
    $stmt->bind_param("i", $document_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // อัปเดตจำนวนผู้ชม
        $row = $result->fetch_assoc();
        $newCount = $row['view_count'] + 1;
        $update = $conn->prepare("UPDATE document_views SET view_count=? WHERE document_id=?");
        $update->bind_param("ii", $newCount, $document_id);
        $update->execute();
    } else {
        // สร้าง record ใหม่
        $insert = $conn->prepare("INSERT INTO document_views (document_id, view_count) VALUES (?, 1)");
        $insert->bind_param("i", $document_id);
        $insert->execute();
        $newCount = 1;
    }

    echo $newCount;
} else {
    echo 0;
}
?>
