<?php
require '../config.php'; // ปรับ path ให้ถูกต้อง

if (isset($_POST['edit_id'])) {
    $edit_id = intval($_POST['edit_id']); // ป้องกัน SQL injection

    $stmt = $conn->prepare("DELETE FROM tb_document_edit WHERE edit_id = ?");
    $stmt->bind_param("i", $edit_id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        error_log("Delete failed: " . $stmt->error);
        echo 'error';
    }

    $stmt->close();
} else {
    echo 'error';
}
?>
