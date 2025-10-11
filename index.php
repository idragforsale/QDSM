<?php
session_start();
require 'inc/authentication.php';
require "config.php";
?>

<!DOCTYPE html>
<html lang="th" dir="ltr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- ✅ SEO Meta Tags -->
	<title>หน้าหลัก QDSM - ระบบบริหารจัดการเอกสารคุณภาพ</title>
	<meta name="title" content="หน้าหลัก QDSM - ระบบบริหารจัดการเอกสารคุณภาพ">
	<meta name="description" content="หน้าหลักของระบบ QDSM สำหรับค้นหาและจัดการเอกสารคุณภาพของโรงพยาบาลหนองคาย">
	<link rel="canonical" href="https://it.nkh.go.th/qdsm/manage">

	<!-- ✅ Favicon -->
	<link rel="icon" href="/qdsm/favicon.ico" type="image/x-icon">

	<!-- ✅ Open Graph / Facebook -->
	<meta property="og:type" content="website">
	<meta property="og:url" content="https://it.nkh.go.th/qdsm/manage">
	<meta property="og:title" content="หน้าหลัก QDSM - ระบบบริหารจัดการเอกสารคุณภาพ">
	<meta property="og:description" content="หน้าหลักของระบบ QDSM สำหรับค้นหาและจัดการเอกสารคุณภาพของโรงพยาบาลหนองคาย">
	<meta property="og:image" content="https://it.nkh.go.th/qdsm/dist/images/preview.jpg">

	<!-- ✅ Twitter -->
	<meta property="twitter:card" content="summary_large_image">
	<meta property="twitter:url" content="https://it.nkh.go.th/qdsm/manage">
	<meta property="twitter:title" content="หน้าหลัก QDSM - ระบบบริหารจัดการเอกสารคุณภาพ">
	<meta property="twitter:description" content="หน้าหลักของระบบ QDSM สำหรับค้นหาและจัดการเอกสารคุณภาพของโรงพยาบาลหนองคาย">
	<meta property="twitter:image" content="https://it.nkh.go.th/qdsm/dist/images/preview.jpg">

	<!-- ✅ Schema.org Structured Data -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "WebApplication",
		"name": "QDSM - Quality Document System Management",
		"url": "https://it.nkh.go.th/qdsm/manage",
		"applicationCategory": "DocumentManagement",
		"operatingSystem": "All",
		"offers": {
			"@type": "Offer",
			"price": "0",
			"priceCurrency": "THB"
		},
		"publisher": {
			"@type": "Organization",
			"name": "โรงพยาบาลหนองคาย",
			"url": "https://www.nkp-hospital.go.th",
			"logo": {
				"@type": "ImageObject",
				"url": "https://it.nkh.go.th/qdsm/dist/images/logo.png"
			}
		}
	}
	</script>

	<!-- Google Fonts - Kanit -->
	<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500&family=Niramit:wght@100;200;300;400;500&family=Sarabun:wght@100;200;300;400;500&family=Prompt:wght@100;200;300;400;500&family=Mitr:wght@100;200;300;400;500&display=swap" rel="stylesheet">

	<!-- Bootstrap 5 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

	<!-- DataTables CSS -->
	<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

	<!-- SweetAlert2 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css" rel="stylesheet">

	<!-- Animate CSS -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />

	<!-- Select2 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<!-- Custom CSS -->
	<link href="/qdsm/dist/css/styles.css?v=<?=filemtime($_SERVER['DOCUMENT_ROOT'].'/qdsm/dist/css/styles.css') ?>" rel="stylesheet">

	<!-- JS Libraries -->
	<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body class="body-main">

<?php require 'inc/head.php'; ?>
	
	<div class="container-fluid pb-4">
		<div class="row">
			<div class="col-12">

				<!-- Search Card -->
				<div class="search-card mt-4 animate__animated animate__fadeInUp">

					<div class="card-body p-4">
						<!-- Main Search -->
						<div class="search-input-group">
							<i class="bi bi-search search-icon"></i>
							<input type="text" class="form-control" id="mainSearch" placeholder="ค้นหาด้วยเลขที่, เรื่อง, หรือหน่วยงาน...">
							<button class="btn search-btn" onclick="performSearch()">
								<i class="bi bi-search"></i>
								<span class="d-none d-md-inline ms-2">ค้นหา</span>
							</button>
						</div>
						
						<!-- Quick Filters -->
						<div class="mb-3">
							<h6 class="mb-3"><i class="bi bi-filter text-orange"></i> ตัวกรองด่วน:</h6>
							<div class="text-center">
								<!-- All -->
								<span class="quick-filter active" data-type="all">
									<i class="bi bi-files"></i> ทั้งหมด
								</span>

								<?php
								$sql = "SELECT type_code, type_name, type_short_name FROM tb_type ORDER BY type_code ASC";
								$result = $conn->query($sql);

								if ($result && $result->num_rows > 0) {
									while ($row = $result->fetch_assoc()) {
										echo '<span class="quick-filter" data-type="'.htmlspecialchars($row['type_code']).'">';
										echo '<i class="bi bi-file-earmark-text"></i> '.htmlspecialchars($row['type_name']);
										echo '</span>';
									}
								}
								?>
							</div>
						</div>

						<!-- ปุ่มเปิด/ปิด Advanced Search -->
						<div class="text-center">
							<button class="btn quick-filter" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSearchSection" aria-expanded="false" aria-controls="advancedSearchSection">
								<i class="bi bi-sliders"></i> ค้นหาขั้นสูง
							</button>
							<button class="btn quick-filter active" onclick="clearSearch()">
								<i class="bi bi-arrow-clockwise"></i> รีเซ็ตการค้นหา
							</button>
						</div>

						<!-- Advanced Search (ซ่อนอยู่) -->
						<div class="collapse" id="advancedSearchSection">
							<div class="row g-3 pt-3">
								<div class="col-md-3">
									<label class="form-label">
										<i class="bi bi-list-ol text-orange"></i> ลำดับ QIC
									</label>
									<input type="text" class="form-control border-orange" id="searchQIC" placeholder="เช่น 001">
								</div>

								<div class="col-md-3">
									<label for="searchDept" class="form-label">
										<i class="bi bi-building text-orange"></i> รหัสหน่วยงาน
									</label>
									<select id="searchDept" name="searchDept" class="form-control border-orange" style="width: 100%;">
										<option></option> <!-- placeholder -->
										<?php
										  // ดึงข้อมูลจากตาราง kskdepartment
										  $sql = "SELECT dep_code, dep_name_th FROM tb_department ORDER BY dep_code ASC";
										  $result = $conn->query($sql);
										  if ($result->num_rows > 0) {
											  while($row = $result->fetch_assoc()){
												  echo '<option value="'.$row['dep_code'].'">'.htmlspecialchars($row['dep_name_th']).'</option>';
											  }
										  }
										  ?>
									</select>
								</div>
								<script>
									$(document).ready(function() {
										$('#searchDept').select2({ // ✅ ต้องตรงกับ id ของ select
											placeholder: "เลือกแผนก",
											allowClear: true,
											width: '100%',
										});
									});

								</script>

								<div class="col-md-3">
									<label class="form-label">
										<i class="bi bi-calendar-range text-orange"></i> วันบังคับใช้ จาก
									</label>
									<input type="date" class="form-control border-orange" id="searchStartDate">
								</div>
								<div class="col-md-3">
									<label class="form-label">
										<i class="bi bi-calendar-check text-orange"></i> ถึง
									</label>
									<input type="date" class="form-control border-orange" id="searchEndDate">
								</div>

								<div class="text-center mt-4 col-12">
									<button class="btn btn-success btn-lg me-2" onclick="performSearch()">
										<i class="bi bi-search"></i> ค้นหา
									</button>

								</div>
							</div>
						</div>

					</div>
				</div>

				<!-- Results Card -->
				<div class="card search-card shadow mt-4 animate__animated animate__fadeIn " id="resultsCard" style="display: none;">

					<div class="card-body px-4 pt-4">

						<h6 class="mb-3">
							<i class="bi bi-list-ol text-orange"></i> ผลการค้นหา
						</h6>

						<!-- Result Stats -->
						<div class="result-stats text-black" id="resultStats">
							<i class="bi bi-info-circle"></i> พบ <span id="resultCount">0</span> รายการ
						</div>

						<!-- DataTable -->
						<div class="table-responsive">
							<table id="searchTable" class="table table-hover align-middle" style="width:100%">
								<thead>
									<tr>
										<th>ลำดับ QIC</th>
										<th>รหัสประเภท</th>
										<th>เลขที่</th>
										<th>เรื่อง</th>
										<th>ชื่อหน่วยงาน</th>
										<th>วันที่บังคับใช้</th>
										<th>อ่าน</th> 
										<th>ไฟล์</th>
										<th>เอกสาร</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Document Viewer Modal -->
	<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header border-bottom-0">
					<h5 class="modal-title" id="documentModalLabel">
						<i class="bi bi-file-earmark-pdf"></i> เอกสาร
					</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-3 rounded-0">
					<iframe id="documentViewer" class="pdf-viewer  border-0 rounded-0" src=""></iframe>
				</div>
				<div class="modal-footer border-0">
					<a href="#" class="btn btn-download" id="downloadBtn" target="_blank">
						<i class="bi bi-download"></i> ดาวน์โหลด
					</a>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
						<i class="bi bi-x-circle"></i> ปิด
					</button>
				</div>
			</div>
		</div>
	</div>

    <script src="dist/js/index.js?v=<?=filemtime($_SERVER['DOCUMENT_ROOT'].'/qdsm/dist/js/index.js') ?>"></script>
    
</body>
</html>