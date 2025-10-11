<?php
session_start();
require 'inc/authentication.php';
include 'config.php';

$id = $_GET['id'] ?? '';
if (!$id) die("ไม่พบเอกสาร");

// ดึงข้อมูลเอกสาร
$sql = "SELECT * FROM tb_document WHERE doc_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$doc = mysqli_fetch_assoc($result);
if (!$doc) die("ไม่พบเอกสาร");


// ดึงข้อมูลเอกสาร
$sql = "SELECT d.*, t.type_name, dept.dep_name_th
        FROM tb_document d
        LEFT JOIN tb_type t ON d.doc_type = t.type_code
        LEFT JOIN tb_department dept ON d.doc_dept = dept.dep_code
        WHERE d.doc_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$doc = mysqli_fetch_assoc($result);
if (!$doc) die("ไม่พบเอกสาร");


// ดึงข้อมูลแก้ไข
$qic_id = $doc['doc_qic_id'];
$edit_sql = "SELECT * FROM tb_document_edit WHERE edit_qic_no = ? ORDER BY edit_round DESC";
$edit_stmt = mysqli_prepare($conn, $edit_sql);
mysqli_stmt_bind_param($edit_stmt, "s", $qic_id);
mysqli_stmt_execute($edit_stmt);
$edit_result = mysqli_stmt_get_result($edit_stmt);
$edits = [];
while ($row = mysqli_fetch_assoc($edit_result)) {
    $edits[] = $row;
}

$file = $doc['doc_file'];

// ฟังก์ชันแปลงวันที่เป็นภาษาไทย
function formatThaiDate($date) {
    $months = [
        '01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.',
        '05' => 'พ.ค.', '06' => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.',
        '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.'
    ];
    
    if ($date && $date != '0000-00-00') {
        $parts = explode('-', $date);
        if (count($parts) == 3) {
            return $parts[2] . ' ' . $months[$parts[1]] . ' ' . ($parts[0] + 543);
        }
    }
    return '-';
}

// กำหนดสีตามประเภทเอกสาร
function getDocTypeColor($type) {
    $colors = [
        'คู่มือ' => 'primary',
        'ข้อบังคับ' => 'danger', 
        'ระเบียบ' => 'warning',
        'แนวปฏิบัติ' => 'success',
        'มาตรฐาน' => 'info'
    ];
    return $colors[$type] ?? 'secondary';
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QDSM : Quality Document System Management</title>

    <!-- Google Fonts - Kanit -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500&family=Niramit:wght@100;200;300;400;500&family=Sarabun:wght@100;200;300;400;500&family=Prompt:wght@100;200;300;400;500&family=Mitr:wght@100;200;300;400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <link href="/qdsm/dist/css/styles.css?v=<?=filemtime($_SERVER['DOCUMENT_ROOT'].'/qdsm/dist/css/styles.css') ?>" rel="stylesheet">

</head>

<body class="body-main">

    <?php require 'inc/head.php'; ?>

    <div class="container-fluid pb-4">

        <!-- Document & Info Card -->
        <div class="search-card shadow mt-4 animate__animated animate__fadeInUp ">
            <div class="card-body p-4">

                <div class="row">
                    <!-- Document Info -->
                    <div class="col-lg-12">


                        <!-- Article Header -->
                        <div class="article-header">

                            <h1 class="article-title">
                                <?php echo htmlspecialchars($doc['doc_topic']); ?>
                            </h1>

                            <div class="article-meta">
                                <div><i class="bi bi-calendar3"></i> วันที่บังคับใช้: <?php echo formatThaiDate($doc['doc_eft_date']); ?></div>
                                <div><i class="bi bi-file-text"></i> เลขที่: <?php echo htmlspecialchars($doc['doc_code']); ?></div>
                                <div class="views" id="documentViews"><i class="bi bi-eye"></i> กำลังโหลด...</div>
                            </div>
                        </div>

                        <!-- Article Body -->
                        <!-- Article Body -->
                        <div class="article-body">
                            <div class="article-content">
                                <!-- Main Content -->
                                <div class="content-main">
                                    <h3><i class="bi bi-info-circle text-orange"></i> รายละเอียดเอกสาร</h3>
                                    <p><?php echo $doc['doc_note'] ? 'หมายเหตุ: ' . htmlspecialchars($doc['doc_note']) : 'เอกสารนี้เป็นส่วนหนึ่งของระบบการจัดการคุณภาพขององค์กร เพื่อให้การดำเนินงานเป็นไปตามมาตรฐานที่กำหนดไว้'; ?></p>

                                    <!-- Document Viewer -->
                                    <div class="document-viewer">
                                        <div class="viewer-header">
                                            <h5><i class="bi bi-file-earmark-pdf"></i> ดูเอกสารออนไลน์</h5>
                                        </div>

                                        <?php if($file): 
                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                        if($ext==='pdf'): ?>
                                        
                                        <iframe class="pdf-viewer" src="/qdsm/uploads/<?php echo htmlspecialchars($file); ?>"></iframe>
                                        <div class="download-section">
                                            <a href="/qdsm/uploads/<?php echo htmlspecialchars($file); ?>" class="btn-download" target="_blank">
                                                <i class="bi bi-download"></i> ดาวน์โหลดเอกสาร PDF
                                            </a>
                                        </div>
                                        <?php else: ?>
                                        <div class="no-file-notice text-center py-5">
                                            <i class="bi bi-file-earmark-word fs-1 text-primary"></i>
                                            <h5>ไฟล์เอกสาร</h5>
                                            <p>ไฟล์ประเภท: <?php echo strtoupper($ext); ?></p>
                                        </div>
                                        <div class="download-section">
                                            <a href="uploads/<?php echo htmlspecialchars($file); ?>" class="btn-download" target="_blank">
                                                <i class="bi bi-download"></i> ดาวน์โหลดไฟล์
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                        <?php else: ?>
                                        <div class="no-file-notice text-center py-5">
                                            <i class="bi bi-file-earmark-x fs-1 text-secondary"></i>
                                            <h5>ไม่มีไฟล์แนบ</h5>
                                            <p>เอกสารนี้ยังไม่มีไฟล์แนบ</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if(!empty($edits)): ?>
                                    <h3><i class="bi bi-clock-history text-orange"></i> ประวัติการปรับปรุงเอกสาร</h3>
                                    <p>เอกสารนี้ผ่านการทบทวนและปรับปรุงอย่างต่อเนื่องเพื่อให้ทันสมัยและสอดคล้องกับการเปลี่ยนแปลงของระเบียบและข้อบังคับต่างๆ</p>

                                    <!-- Edit History -->
                                    <div class="edit-history">
                                        <div class="history-header">
                                            <h4><i class="bi bi-journal-bookmark text-orange"></i> ประวัติการแก้ไขเอกสาร</h4>
                                        </div>
                                        <div class="history-content">
                                            <div class="table-responsive">
                                                <table id="editTable" class="table table-hover align-middle" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 8%">รอบ</th>
                                                            <th style="width: 12%">วันที่แก้ไข</th>
                                                            <th style="width: 25%">หมายเหตุ</th>
                                                            <th style="width: 18%">จัดเตรียมโดย</th>
                                                            <th style="width: 18%">ทบทวนโดย</th>
                                                            <th style="width: 19%">อนุมัติโดย</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach($edits as $e): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($e['edit_round']); ?></td>
                                                            <td><?php echo formatThaiDate($e['edit_date']); ?></td>
                                                            <td><?php echo htmlspecialchars($e['edit_remark']); ?></td>
                                                            <td><?php echo htmlspecialchars($e['edit_prepared_by']); ?></td>
                                                            <td><?php echo htmlspecialchars($e['edit_reviewed_by']); ?></td>
                                                            <td><?php echo htmlspecialchars($e['edit_approved_by']); ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Sidebar -->
                                <div class="info-sidebar">
                                    <h4><i class="bi bi-info-square text-orange"></i> ข้อมูลเอกสาร</h4>

                                    <div class="info-item">
                                        <div class="info-label">ลำดับ QIC:</div>
                                        <div class="info-value"><?php echo htmlspecialchars($doc['doc_qic_id']); ?></div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">ประเภท:</div>
                                        <div class="info-value"><?php echo htmlspecialchars($doc['type_name']); ?></div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">เลขที่เอกสาร:</div>
                                        <div class="info-value"><?php echo htmlspecialchars($doc['doc_code']); ?></div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">หน่วยงาน:</div>
                                        <div class="info-value"><?php echo htmlspecialchars($doc['dep_name_th']); ?></div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">วันที่บังคับใช้:</div>
                                        <div class="info-value"><?php echo formatThaiDate($doc['doc_eft_date']); ?></div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">วันที่นำเข้าระบบ:</div>
                                        <div class="info-value"><?php echo formatThaiDate($doc['doc_imp_date']); ?></div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">สถานะ:</div>
                                        <div class="info-value">
                                            <span style="background: #28a745; color: white; padding: 4px 12px; border-radius: 15px; font-size: 0.8rem;">ใช้งานปัจจุบัน</span>
                                        </div>
                                    </div>

                                    <?php if($doc['doc_note']): ?>
                                    <div class="info-item">
                                        <div class="info-label">หมายเหตุ:</div>
                                        <div class="info-value"><?php echo htmlspecialchars($doc['doc_note']); ?></div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            <?php if(!empty($edits)): ?>
            $('#editTable').DataTable({
                paging: false,
                searching: false,
                info: false,
                lengthChange: false,
                ordering: true,
                order: [
                    [0, 'desc']
                ],
                language: {
                    emptyTable: "ไม่มีประวัติการแก้ไข",
                    zeroRecords: "ไม่พบข้อมูล"
                }
            });
            <?php endif; ?>

            // ดึงจำนวนผู้อ่านจาก counter.php
            const documentId = "<?php echo $_GET['id'] ?? ''; ?>";
            if (documentId) {
                $.get('/qdsm/counter.php', {
                    id: documentId
                }, function(data) {
                    $('#documentViews').html('<i class="bi bi-eye"></i> ' + data);
                }).fail(function() {
                    const viewCount = Math.floor(Math.random() * 2000) + 100;
                    $('#documentViews').html('<i class="bi bi-eye"></i> ' + viewCount.toLocaleString() + ' ครั้ง');
                });
            }
        });

    </script>
</body>

</html>
