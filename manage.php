<?php
session_start();
require 'inc/authentication-admin.php';
require "config.php";


$sql = "SELECT type_code, type_name, type_short_name FROM tb_type ORDER BY type_code ASC";
$result = $conn->query($sql);
if ($result) {
    while($row = $result->fetch_assoc()) {
        $typeOptions .= "<option value='{$row['type_code']}' data-short='{$row['type_short_name']}'>{$row['type_name']}</option>";
    }
} else {
    die("SQL Error (tb_type): " . $conn->error);
}

// ดึง QIC ล่าสุด
$qicNext = 1; // default ถ้าไม่มีข้อมูล
$sqlQIC = "SELECT MAX(CAST(doc_qic_id AS UNSIGNED)) AS max_qic FROM tb_document";
$resultQIC = $conn->query($sqlQIC);
if($resultQIC) {
    $rowQIC = $resultQIC->fetch_assoc();
    if($rowQIC && $rowQIC['max_qic'] !== null) {
        $qicNext = intval($rowQIC['max_qic']) + 1;
    }
} else {
    die("SQL Error (tb_document): " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>หน้าจัดการ QDSM : Admin Console</title>

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


	<!-- JS Libraries (บางตัวโหลดใน head เพราะต้องใช้ก่อนโหลด body) -->
	<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<!-- DataTables JS -->
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

	<!-- DataTables Buttons JS -->
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

	<!-- SweetAlert2 JS -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

	<!-- Select2 JS -->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


</head>
<body>

<?php require 'inc/head-admin.php'; ?>    
    
	<div class="container-fluid pb-4">
		<div class="row">
			<div class="col-12">
				<div class="card search-card mt-4 p-2">
					<div class="card-body pb-2">
						<!-- Button Add -->
						<div class="mb-3 my-1">
                            <h6 class="mb-3"><i class="bi bi-filter text-orange"></i> ตัวกรองด่วน:</h6>
                        </div>
						<!-- Filter -->
						<div class="row">
							<div class="col-md-3 mb-3">
								<label class="mb-1"><i class="bi bi-calendar-date text-orange"></i> วันที่บังคับใช้ จาก</label>
								<input type="date" id="filter_start" class="form-control border-orange">
							</div>
							<div class="col-md-3 mb-3">
								<label class="mb-1"><i class="bi bi-calendar-check text-orange"></i> ถึง</label>
								<input type="date" id="filter_end" class="form-control border-orange">
							</div>
							<div class="col-md-3 mb-3">
								<label class="mb-1"><i class="bi bi-tags text-orange"></i> ประเภทเอกสาร</label>
								<select id="filter_type" class="form-control border-orange">
									<option value="">ทั้งหมด</option>
									<?php echo $typeOptions; ?>
								</select>
							</div>
							<div class="col-md-3 d-flex align-items-end mb-2">
								<button id="filterBtn" class="quick-filter active" data-type="all">
									<i class="bi bi-search"></i> ค้นหา
								</button>
							</div>
						</div>
					</div>
				</div>

				<!-- Results Card -->
				<div class="card search-card shadow mt-4">
					<div class="card-body px-4 pt-4">

						<h6 class="mb-3">
							<i class="bi bi-list-ol text-orange"></i> รายการ เอกสารทั้งหมด
						</h6>
						
							<span class="quick-filter" data-type="all" data-bs-toggle="modal" data-bs-target="#addModal">
								<i class="bi bi-plus-circle"></i> เพิ่มข้อมูล
							</span>

							<a class="quick-filter text-decoration-none" href="import">
								<i class="bi bi-cloud-upload"></i> นำเข้าเอกสาร
							</a>
                        
                        <span class="quick-filter mb-1" onclick="exportAPI()">
                            <i class="bi bi-cloud-download"></i> ส่งออก
                        </span>

						<!-- DataTable -->
						<div class="table-responsive">
							<table id="workInTable" class="table table-hover align-middle" style="width:100%">
								<thead>
									<tr>
										<th>ลำดับ QIC</th>
										<th>ประเภทเอกสาร</th>
										<th>เลขที่</th>
										<th>เรื่อง</th>
										<th>หน่วยงาน</th>
										<th>วันที่บังคับใช้</th>
										<th>แก้</th> <!-- เพิ่มคอลัมน์นี้ -->
										<th>ไฟล์</th>
										<th>จัดการ</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- Add/Edit Modal -->
	<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addModalLabel">
						<i class="bi bi-plus-circle"></i> เพิ่มข้อมูล
					</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<form id="workInForm" enctype="multipart/form-data">
					<div class="modal-body">
						<input type="hidden" id="doc_id" name="doc_id">

						<!-- ข้อมูลหลัก -->
						<div class="row g-3">
							<div class="col-md-6">
								<div class="card border-0 bg-light-orange h-100">
									<div class="card-header bg-transparent border-0">
										<h6 class="text-light mb-0">
											<i class="bi bi-file-earmark-text"></i> ข้อมูลเอกสาร
										</h6>
									</div>
									<div class="card-body">
										<div class="row g-3">
											<!-- ลำดับ QIC -->
											<div class="col-md-6">
												<label for="doc_qic_id" class="form-label">
													<i class="bi bi-list-ol text-orange"></i> ลำดับ QIC
												</label>
												<input type="text" class="form-control border-orange" id="doc_qic_id" name="doc_qic_id" maxlength="6" value="<?php echo $qicNext; ?>">
												<small class="text-danger">* กรุณากรอก ลำดับ QIC</small>
											</div>

											<!-- รหัสประเภท -->
											<div class="col-md-6">
												<label for="doc_type" class="form-label">
													<i class="bi bi-tags text-orange"></i> ประเภทเอกสาร
												</label>
												<select class="form-control border-orange" id="doc_type" name="doc_type">
													<?php echo $typeOptions; ?>
												</select>
												<small class="text-danger">* กรุณาเลือก ประเภท</small>
											</div>


											<!-- รหัสหน่วยงาน -->
											<div class="col-md-6">
												<label for="doc_dept" class="form-label">
													<i class="bi bi-building text-orange"></i> หน่วยงาน
												</label>
												<select id="doc_dept" name="doc_dept" class="form-control border-orange" style="width: 100%;">
													<option></option> <!-- placeholder -->
													<?php
													$sql = "SELECT dep_code, dep_name_th, dep_name_short FROM tb_department ORDER BY dep_name_th ASC";
													$result = $conn->query($sql);
													if ($result->num_rows > 0) {
														while($row = $result->fetch_assoc()){
															echo '<option value="'.$row['dep_code'].'" data-short="'.$row['dep_name_short'].'">'
																 .htmlspecialchars($row['dep_name_th']).'</option>';
														}
													}
												  ?>
												</select>
												<small class="text-danger">* กรุณาเลือก หน่วยงาน</small>
											</div>

											<!-- วันที่บังคับใช้ -->
											<div class="col-md-6">
												<label for="doc_eft_date" class="form-label">
													<i class="bi bi-calendar-event text-orange"></i> วันที่บังคับใช้
												</label>
												<input type="date" class="form-control border-orange" id="doc_eft_date" name="doc_eft_date">
												<small class="text-danger">* กรุณาเลือก วันที่บังคับใช้</small>
											</div>


											<!-- เลขที่ -->
											<div class="col-12">
												<label for="doc_code" class="form-label">
													<i class="bi bi-upc-scan text-orange"></i> เลขที่
													<small class="text-secondary">เช่น NK-PR-PHAs-xxx</small>
												</label>
												<input type="text" class="form-control border-orange" id="doc_code" name="doc_code" maxlength="20">
												<small class="text-danger">* กรุณากรอก เลขที่</small>
											</div>

											<!-- เรื่อง -->
											<div class="col-12">
												<label for="doc_topic" class="form-label">
													<i class="bi bi-journal-text text-orange"></i> เรื่อง
												</label>
												<input type="text" class="form-control border-orange" id="doc_topic" name="doc_topic" maxlength="255">
												<small class="text-danger">* กรุณากรอก เรื่อง</small>
											</div>


										</div>
									</div>
								</div>
							</div>

							<!-- ฝั่งขวา - อัปโหลดไฟล์และหมายเหตุ -->
							<div class="col-md-6">
								<div class="card border-0 bg-light-success h-100">
									<div class="card-header bg-transparent border-0">
										<h6 class="text-light mb-0">
											<i class="bi bi-cloud-upload"></i> ไฟล์และหมายเหตุ
										</h6>
									</div>
									<div class="card-body">
										<!-- Dropzone -->
										<div class="mb-3">
											<label class="form-label">
												<i class="bi bi-paperclip text-orange"></i> แนบไฟล์เอกสาร
											</label>
											<div class="dropzone-area mb-3 text-center p-3" id="dropzoneArea">
												<i class="bi bi-cloud-arrow-up text-orange" style="font-size: 2rem;"></i>
												<div class="mt-2 text-orange">ลากไฟล์มาวางหรือคลิกที่นี่</div>
												<small class="text-muted">รองรับ: PDF, DOC, DOCX, XLS, XLSX (สูงสุด 10MB)</small>
												<div class="file-name mt-2" id="fileName"></div>
											</div>
											<input type="file" class="form-control" id="doc_file" name="doc_file" accept=".pdf,.doc,.docx,.xls,.xlsx" style="display:none;">

											<!-- Progress Bar -->
											<div id="uploadProgress" class="progress mt-3" style="display:none;">
												<div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width:0%">0%</div>
											</div>
										</div>

										<!-- หมายเหตุ -->
										<div class="mb-3">
											<label for="doc_note" class="form-label">
												<i class="bi bi-chat-left-text text-orange"></i> หมายเหตุ
											</label>
											<textarea class="form-control border-orange" id="doc_note" name="doc_note" rows="5"></textarea>
										</div>




										<!-- สถานะไฟล์ -->
										<div class="d-flex align-items-center justify-content-between">
											<small class="text-muted">
												<i class="bi bi-info-circle"></i> วันที่นำเข้าระบบจะบันทึกอัตโนมัติ
											</small>
											<div id="fileStatus" class="badge bg-light text-dark">
												<i class="bi bi-file-earmark"></i> ยังไม่เลือกไฟล์
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row mt-3">
							<div class="col-md-12">
								<div class="card border-0 bg-light-orange h-100">
									<div class="card-header bg-transparent border-0">
										<h6 class="text-light mb-0">
											<div class="form-check form-switch">
												<input class="form-check-input" type="checkbox" id="saveToHistory" name="saveToHistory">
												<label class="form-check-label" for="saveToHistory">
													แก้เอกสาร และบันทึกลงประวัติ
												</label>
											</div>
										</h6>
									</div>
									<div class="card-body">

										<!-- Switch บันทึกลง history -->



										<div id="historyFields" style="display:none;">
											<div class="row">
												<div class="col-lg-6">
													<div class="row">
														<div class="col-md-6">
															<label for="doc_dept" class="form-label">
																<i class="bi bi-building text-orange"></i> วันที่แก้ไข
															</label>
															<input type="date" class="form-control border-orange mb-3" name="edit_date" id="edit_date">
														</div>
														<?php
															$users = [];
															$sql = "SELECT name FROM opduser ORDER BY name ASC";
															$result = $conn2->query($sql);
															if ($result->num_rows > 0) {
																while($row = $result->fetch_assoc()){
																	$users[] = $row['name'];
																}
															}
														?>

														<!-- Prepared by -->
														<div class="col-md-6">
															<label for="edit_prepared_by" class="form-label">
																<i class="bi bi-person text-orange"></i> จัดทำโดย
															</label>
															<select class="form-control border-orange mb-3" name="edit_prepared_by" id="edit_prepared_by">
																<option></option>
																<?php foreach($users as $u) echo '<option value="'.htmlspecialchars($u).'">'.htmlspecialchars($u).'</option>'; ?>
															</select>
														</div>

														<!-- Reviewed by -->
														<div class="col-md-6">
															<label for="edit_reviewed_by" class="form-label">
																<i class="bi bi-person-check text-orange"></i> ตรวจสอบโดย
															</label>
															<select class="form-control border-orange mb-3" name="edit_reviewed_by" id="edit_reviewed_by">
																<option></option>
																<?php foreach($users as $u) echo '<option value="'.htmlspecialchars($u).'">'.htmlspecialchars($u).'</option>'; ?>
															</select>
														</div>

														<!-- Approved by -->
														<div class="col-md-6">
															<label for="edit_approved_by" class="form-label">
																<i class="bi bi-person-badge text-orange"></i> อนุมัติโดย
															</label>
															<select class="form-control border-orange mb-3" name="edit_approved_by" id="edit_approved_by">
																<option></option>
																<?php foreach($users as $u) echo '<option value="'.htmlspecialchars($u).'">'.htmlspecialchars($u).'</option>'; ?>
															</select>
														</div>


													</div>
												</div>
												<div class="col-lg-6">
													<label class="mb-2">หมายเหตุ</label>
													<textarea class="form-control border-orange mb-3" name="edit_remark" id="edit_remark" rows="5"></textarea>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>


					</div>

					<div class="modal-footer border-0">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
							<i class="bi bi-x-circle"></i> ยกเลิก
						</button>
						<button type="submit" class="btn btn-success">
							<i class="bi bi-save"></i> บันทึก
						</button>
					</div>


				</form>
			</div>
		</div>
	</div>

	<!-- Modal: Edit History -->
	<div class="modal animate-bounce" id="editHistoryModal" tabindex="-1" aria-labelledby="editHistoryLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header bg-info text-white border-0">
					<h5 class="modal-title" id="editHistoryLabel">ประวัติการแก้ไข</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
				</div>
				<div class="modal-body">

					<div id="editHistoryContent" class="table-responsive">
						<table class="table" id="editHistoryTable">
							<thead>
								<tr>
									<th>รอบแก้ไข</th>
									<th>วันที่แก้ไข</th>
									<th>ผู้จัดเตรียม</th>
									<th>ผู้ทบทวน</th>
									<th>ผู้อนุมัติ</th>
									<th>หมายเหตุ</th>
									<th>จัดการ</th> <!-- เพิ่ม column สำหรับปุ่มลบ -->
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="8" class="text-center text-muted">กำลังโหลดข้อมูล...</td>
								</tr>
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>


	<script>
		$(document).ready(function() {
			// เมื่อ modal เปิด
			$('#addModal').on('shown.bs.modal', function() {
				// ซ่อน historyFields และเคลียร์ค่าเมื่อเปิด modal
				$('#saveToHistory').prop('checked', false); // ปิด switch
				$('#historyFields').hide(); // ซ่อนกล่อง history
				$('#edit_date').val('');
				$('#edit_prepared_by').val(null).trigger('change');
				$('#edit_reviewed_by').val(null).trigger('change');
				$('#edit_approved_by').val(null).trigger('change');
				$('#edit_remark').val('');

				// init dropdown แผนก
				if (!$('#doc_dept').hasClass("select2-hidden-accessible")) {
					$('#doc_dept').select2({
						placeholder: "เลือกแผนก",
						allowClear: true,
						width: '100%',
						dropdownParent: $('#addModal .modal-body')
					});
				}

				// init dropdown ของ prepared/reviewed/approved
				$('#edit_prepared_by, #edit_reviewed_by, #edit_approved_by').each(function() {
					if (!$(this).hasClass("select2-hidden-accessible")) {
						$(this).select2({
							placeholder: "เลือกชื่อ",
							allowClear: true,
							width: '100%',
							dropdownParent: $(this).closest('.modal-body')
						});
					}
				});
			});

			// toggle history fields
			$('#saveToHistory').on('change', function() {
				if ($(this).is(':checked')) {
					$('#historyFields').slideDown(function() {
						$('#edit_prepared_by, #edit_reviewed_by, #edit_approved_by').each(function() {
							if (!$(this).hasClass("select2-hidden-accessible")) {
								$(this).select2({
									placeholder: "เลือกชื่อ",
									allowClear: true,
									width: '100%',
									dropdownParent: $(this).closest('.modal-body')
								});
							}
						});
					});
				} else {
					$('#historyFields').slideUp();
				}
			});


			const $dropzone = $('#dropzoneArea');
			const $fileInput = $('#doc_file');
			const $fileNameDisplay = $('#fileName');
			const $fileStatus = $('#fileStatus');
			const $progressBar = $('#uploadProgress');
			const $progressInner = $progressBar.find('.progress-bar');

			let selectedFile = null;

			// อัพเดตเลขที่อัตโนมัติเมื่อเปลี่ยนประเภท
			function updateDocCode() {
				const shortName = $('#doc_type').find(':selected').data('short'); // จาก tb_type
				const deptShort = $('#doc_dept').find(':selected').data('short'); // จาก tb_department
				const currentCode = $('#doc_code').val();

				if (shortName && deptShort) {
					if (currentCode && currentCode.includes('-')) {
						const parts = currentCode.split('-');
						if (parts.length >= 4) {
							$('#doc_code').val(`NK-${shortName}-${deptShort}-${parts[3]}`);
						} else {
							$('#doc_code').val(`NK-${shortName}-${deptShort}-`);
						}
					} else {
						$('#doc_code').val(`NK-${shortName}-${deptShort}-`);
					}
				} else {
					$('#doc_code').val('');
				}
			}

			// โหลดครั้งแรก
			updateDocCode();

			// เมื่อเปลี่ยนประเภทหรือแผนก
			$('#doc_type, #doc_dept').on('change', updateDocCode);


			// คลิก dropzone -> เปิด file dialog
			$dropzone.on('click', function() {
				$fileInput.click();
			});

			// เลือกไฟล์
			$fileInput.on('change', function() {
				selectedFile = this.files[0];
				showFile(selectedFile);
			});

			// Drag & Drop Events
			$dropzone.on('dragover dragenter', function(e) {
				e.preventDefault();
				e.stopPropagation();
				$dropzone.addClass('border-primary').css({
					'background': 'rgba(13, 110, 253, 0.1)',
					'border-color': '#0d6efd'
				});
			});

			$dropzone.on('dragleave dragend', function(e) {
				e.preventDefault();
				e.stopPropagation();
				$dropzone.removeClass('border-primary').css({
					'background': 'rgba(40, 167, 69, 0.1)',
					'border-color': '#28a745'
				});
			});

			$dropzone.on('drop', function(e) {
				e.preventDefault();
				e.stopPropagation();
				$dropzone.removeClass('border-primary').css({
					'background': 'rgba(40, 167, 69, 0.1)',
					'border-color': '#28a745'
				});

				const files = e.originalEvent.dataTransfer.files;
				if (files.length > 0) {
					selectedFile = files[0];

					// Update input files
					const dt = new DataTransfer();
					dt.items.add(selectedFile);
					$fileInput[0].files = dt.files;

					showFile(selectedFile);
				}
			});

			function showFile(file) {
				if (!file) {
					$fileNameDisplay.empty();
					$fileStatus.removeClass('bg-success bg-warning bg-danger')
						.addClass('bg-light text-dark')
						.html('<i class="bi bi-file-earmark"></i> ยังไม่เลือกไฟล์');
					$progressBar.hide();
					return;
				}

				// ตรวจสอบขนาดไฟล์ (10MB)
				const maxSize = 10 * 1024 * 1024;
				if (file.size > maxSize) {
					showFileError('ไฟล์ใหญ่เกินกำหนด (สูงสุด 10MB)');
					resetFileInput();
					return;
				}

				// ตรวจสอบชนิดไฟล์
				const fileExt = file.name.split('.').pop().toLowerCase();
				const allowedTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
				if (!allowedTypes.includes(fileExt)) {
					showFileError('ชนิดไฟล์ไม่รองรับ (รองรับเฉพาะ PDF, DOC, DOCX, XLS, XLSX)');
					resetFileInput();
					return;
				}

				// แสดงข้อมูลไฟล์
				const fileIcon = getFileIcon(fileExt);
				const fileSize = formatFileSize(file.size);

				$fileNameDisplay.html(`
					<div class="d-flex align-items-center justify-content-between p-2 bg-white rounded border">
						<div class="d-flex align-items-center">
							<i class="bi ${fileIcon} text-orange me-2"></i>
							<div>
								<div class="fw-bold text-truncate" style="max-width: 100%" title="${file.name}">${file.name}</div>
								<small class="text-muted">${fileSize}</small>
							</div>
						</div>
						<button type="button" class="btn btn-sm btn-outline-danger mx-2" onclick="removeFile()">
							<i class="bi bi-x fs-5"></i>
						</button>
					</div>
				`);

				$fileStatus.removeClass('bg-light bg-warning bg-danger text-dark')
					.addClass('bg-success text-white')
					.html('<i class="bi bi-check-circle"></i> ไฟล์พร้อมอัพโหลด');

				$progressInner.css('width', '0%').text('0%');
				$progressBar.show();
			}

			function showFileError(message) {
				$fileStatus.removeClass('bg-light bg-success text-dark text-white')
					.addClass('bg-danger text-white')
					.html('<i class="bi bi-exclamation-triangle"></i> ' + message);

				setTimeout(() => {
					$fileStatus.removeClass('bg-danger text-white')
						.addClass('bg-light text-dark')
						.html('<i class="bi bi-file-earmark"></i> ยังไม่เลือกไฟล์');
				}, 3000);
			}

			function getFileIcon(extension) {
				const icons = {
					'pdf': 'bi-file-earmark-pdf',
					'doc': 'bi-file-earmark-word',
					'docx': 'bi-file-earmark-word',
					'xls': 'bi-file-earmark-excel',
					'xlsx': 'bi-file-earmark-excel'
				};
				return icons[extension] || 'bi-file-earmark';
			}

			function formatFileSize(bytes) {
				if (bytes === 0) return '0 Bytes';
				const k = 1024;
				const sizes = ['Bytes', 'KB', 'MB', 'GB'];
				const i = Math.floor(Math.log(bytes) / Math.log(k));
				return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
			}

			// Global function สำหรับลบไฟล์
			window.removeFile = function() {
				resetFileInput();
			};

			function resetFileInput() {
				selectedFile = null;
				$fileInput.val('');
				$fileNameDisplay.empty();
				$fileStatus.removeClass('bg-success bg-warning bg-danger text-white')
					.addClass('bg-light text-dark')
					.html('<i class="bi bi-file-earmark"></i> ยังไม่เลือกไฟล์');
				$progressBar.hide();
			}


			// Submit form AJAX (ปรับปรุงแล้ว)
			$('#workInForm').on('submit', function(e) {
				e.preventDefault();

				// Validation
				const qic = $('#doc_qic_id').val().trim();
				const code = $('#doc_code').val().trim();
				const topic = $('#doc_topic').val().trim();
				const eftDate = $('#doc_eft_date').val().trim();
				const docType = $('#doc_type').val();
				const docDept = $('#doc_dept').val();

				if (!qic || !code || !topic || !eftDate || !docType || !docDept) {
					Swal.fire({
						icon: 'warning',
						title: 'กรอกข้อมูลไม่ครบถ้วน',
						text: 'กรุณากรอก ลำดับ QIC, เลขที่, เรื่อง, วันที่บังคับใช้, ประเภทเอกสาร และหน่วยงาน',
						confirmButtonColor: '#ff7b59',
						customClass: {
							popup: 'bounceIn'
						}
					});
					return;
				}

				// ตรวจสอบรูปแบบเลขที่
				if (!code.startsWith('NK-') || code.split('-').length < 4) {
					Swal.fire({
						icon: 'warning',
						title: 'รูปแบบเลขที่ไม่ถูกต้อง',
						text: 'เลขที่ต้องอยู่ในรูปแบบ NK-XXX-YYY-XXX',
						confirmButtonColor: '#ff7b59',
						customClass: {
							popup: 'bounceIn'
						}
					});
					return;
				}

				showLoading();

				let formData = new FormData(this);

				// เปลี่ยนชื่อไฟล์ตาม code
				if (selectedFile && code) {
					const extension = selectedFile.name.split('.').pop();
					const newFileName = `${code}.${extension}`;
					const newFile = new File([selectedFile], newFileName, {
						type: selectedFile.type
					});
					formData.set('doc_file', newFile);
				}

				let action = $('#doc_id').val() ? 'update' : 'insert';
				formData.append('action', action);



				$.ajax({
					url: 'ajax/save_data.php',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					xhr: function() {
						let xhr = new window.XMLHttpRequest();
						xhr.upload.addEventListener('progress', function(evt) {
							if (evt.lengthComputable) {
								let percent = Math.round((evt.loaded / evt.total) * 100);
								$progressInner.css('width', percent + '%').text(percent + '%');

								if (percent === 100) {
									$progressInner.addClass('bg-warning').text('กำลังประมวลผล...');
								}
							}
						});
						return xhr;
					},
					success: function(response) {
						hideLoading();

						try {
							let res = typeof response === 'string' ? JSON.parse(response) : response;

							if (res.status === 'success') {
								Swal.fire({
									icon: 'success',
									title: 'บันทึกข้อมูลสำเร็จ!',
									text: res.message,
									timer: 2000,
									showConfirmButton: false,
									customClass: {
										popup: 'bounceIn'
									}
								}).then(() => {
									$('#addModal').modal('hide');
									if (typeof table !== 'undefined') {
										table.ajax.reload(null, false); // รีโหลดโดยไม่รีเซ็ต pagination
									}
									resetForm();
								});
							} else {
								Swal.fire({
									icon: 'error',
									title: 'เกิดข้อผิดพลาด',
									text: res.message || 'ไม่สามารถบันทึกข้อมูลได้',
									confirmButtonColor: '#ff7b59',
									customClass: {
										popup: 'bounceIn'
									}
								});
							}
						} catch (e) {
							console.error('Parse Error:', e);
							Swal.fire({
								icon: 'error',
								title: 'เกิดข้อผิดพลาดระบบ',
								text: 'กรุณาติดต่อผู้ดูแลระบบ',
								confirmButtonColor: '#ff7b59',
								customClass: {
									popup: 'bounceIn'
								}
							});
						}
					},
					error: function(xhr, status, error) {
						hideLoading();
						console.error('AJAX Error:', {
							status,
							error,
							response: xhr.responseText
						});

						let errorMessage = 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้';

						if (xhr.status === 404) {
							errorMessage = 'ไม่พบไฟล์ประมวลผล (ajax/save_data.php)';
						} else if (xhr.status === 500) {
							errorMessage = 'เซิร์ฟเวอร์เกิดข้อผิดพลาด';
						} else if (xhr.status === 413) {
							errorMessage = 'ไฟล์มีขนาดใหญ่เกินที่เซิร์ฟเวอร์รองรับ';
						}

						Swal.fire({
							icon: 'error',
							title: 'เกิดข้อผิดพลาด',
							text: errorMessage,
							confirmButtonColor: '#ff7b59',
							customClass: {
								popup: 'bounceIn'
							}

						});
					}
				});
			});

			function resetForm() {
				$('#workInForm')[0].reset();
				$('#doc_id').val('');
				resetFileInput();
				$('#addModalLabel').html('<i class="bi bi-plus-circle"></i> เพิ่มข้อมูล');

				// รีเซ็ต progress bar
				$progressInner.css('width', '0%').text('0%');
				$progressBar.hide();
			}

			// Global functions
			window.showLoading = function() {
				$('#loadingOverlay').css('display', 'flex');
			};

			window.hideLoading = function() {
				$('#loadingOverlay').hide();
			};

			window.resetForm = resetForm;
		});


		let table;

		$(document).ready(function() {
			// Initialize DataTable
			table = $('#workInTable').DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					url: 'ajax/get_data.php',
					type: 'POST',
					data: function(d) {
						d.start_date = $('#filter_start').val();
						d.end_date = $('#filter_end').val();
						d.type_code = $('#filter_type').val().trim();
					},
					beforeSend: function() {
						showLoading();
					},
					complete: function() {
						hideLoading();
					}
				},
                
                /*
				dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>' +
					'<"row"<"col-sm-12"tr>>' +
					'<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
				buttons: [{
						extend: 'excelHtml5',
						text: '<i class="bi bi-file-earmark-excel"></i> ส่งออก Excel',
						className: 'btn btn-success',
						title: 'รายการเอกสาร',
						filename: 'รายการเอกสาร_' + new Date().toISOString().slice(0, 10),
						exportOptions: {
							columns: ':visible:not(:last-child)'
						}
					},
					{
						extend: 'print',
						text: '<i class="bi bi-printer"></i> พิมพ์',
						className: 'btn btn-orange-theme',
						title: 'รายการเอกสาร',
						exportOptions: {
							columns: ':visible:not(:last-child)'
						}
					}
				],
                */
                
				columns: [
					{ data: 0, width: '7%' },  // ลำดับ QIC
					{ data: 1, width: '12%' },  // ประเภทเอกสาร
					{ data: 2, width: '10%' }, // เลขที่
					{ data: 3, width: '20%' }, // เรื่อง
					{ data: 4, width: '12%' }, // หน่วยงาน
					{ data: 5, width: '10%' }, // วันที่บังคับใช้
					{ data: 6, width: '7%' }, // แก้เอกสาร
					{ data: 7, width: '10%' }, // ไฟล์
					{
						data: 8,
						orderable: false,
						searchable: false,
						width: '8%'
					} // จัดการ
				],

				language: {
					processing: '<div class="text-center"><i class="bi bi-arrow-repeat text-orange" style="font-size: 2rem;"></i><br>กำลังค้นหา...</div>',
					emptyTable: '<div class="text-center text-muted"><i class="bi bi-search" style="font-size: 3rem;"></i><br>ไม่พบข้อมูลที่ค้นหา</div>',
					info: 'แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ',
					infoEmpty: 'ไม่พบข้อมูล',
					infoFiltered: '(กรองจากทั้งหมด _MAX_ รายการ)',
					lengthMenu: 'แสดง _MENU_ รายการ',
					search: 'ค้นหา:',
					paginate: {
						first: 'แรก',
						last: 'สุดท้าย',
						next: 'ถัดไป',
						previous: 'ก่อนหน้า'
					}
				},
				order: [
					[0, 'desc']
				],
				pageLength: 10
			});


			/*

						$('#filterBtn').on('click', function() {
							table.ajax.reload();
						});
						*/

            
            /*
			$('#filterBtn').on('click', function() {
				// แสดง overlay
				$('#loadingOverlay').fadeIn(200);

				// delay 2.5 วินาที
				setTimeout(function() {
					table.ajax.reload(function() {
						// ซ่อน overlay หลังโหลดเสร็จ
						$('#loadingOverlay').fadeOut(200);
					}, false);
				}, 0); // 2500 ms = 2.5 วินาที
			});
            */
            
$('#filterBtn').on('click', function() {
    table.ajax.reload(null, false); // reload data ทันที
});

			// Reset form when modal is closed
			$('#addModal').on('hidden.bs.modal', function() {
				resetForm();
			});
		});




		let editHistoryTable; // เก็บ instance ของ DataTable

		function openEditHistoryModal(qicId) {
			console.log('openEditHistoryModal called with qicId:', qicId); // debug
			$('#editHistoryModal').modal('show');

			// แสดง loading
			$('#editHistoryTable tbody').html('<tr><td colspan="8" class="text-center text-muted">กำลังโหลดข้อมูล...</td></tr>');

			// Destroy DataTable ก่อน
			if ($.fn.DataTable.isDataTable('#editHistoryTable')) {
				$('#editHistoryTable').DataTable().clear().destroy();
			}

			$('#editHistoryTable tbody').empty();

			$.ajax({
				url: 'ajax/get_edit_history.php',
				type: 'GET',
				data: {
					qic_id: qicId
				},
				dataType: 'json',
				success: function(response) {
					if (response.status === 'success' && response.data.length > 0) {
						let rows = '';
						response.data.forEach((row, index) => {
							rows += `<tr data-id="${row.edit_id}">
                        <td>${row.edit_round}</td>
                        <td>${row.edit_date}</td>
                        <td>${row.edit_prepared_by}</td>
                        <td>${row.edit_reviewed_by}</td>
                        <td>${row.edit_approved_by}</td>
                        <td>${row.edit_remark}</td>
                        <td>
                            <button class="btn btn-danger btn-sm delete-history" data-id="${row.edit_id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>`;
						});
						$('#editHistoryTable tbody').html(rows);

						editHistoryTable = $('#editHistoryTable').DataTable({
							scrollY: '400px',
							scrollCollapse: true,
							paging: false,
							autoWidth: false,
							"bSort": false, // ปิดการเรียงลำดับ
							"searching": false, // ปิดการค้นหา
							"info": false, // ปิดข้อมูลสถิติ
							"headerCallback": function() {
								$(this.api().table().header()).hide(); // ซ่อนหัวตาราง
							}

						});

					} else {
						$('#editHistoryTable tbody').html('<tr><td colspan="8" class="text-center text-muted">ยังไม่มีประวัติการแก้ไข</td></tr>');
					}
				},
				error: function(err) {
					console.error(err);
					$('#editHistoryTable tbody').html('<tr><td colspan="8" class="text-center text-muted">โหลดข้อมูลไม่สำเร็จ</td></tr>');
				}
			});
		}


		// ✅ Fix หัวตารางกระตุกใน modal
		$('#editHistoryModal').on('shown.bs.modal', function() {
			if ($.fn.DataTable.isDataTable('#editHistoryTable')) {
				$('#editHistoryTable').DataTable().columns.adjust().draw();
			}
		});
		// Event: ลบ record
		// Event: ลบ record (ใช้ SweetAlert2)
		$(document).on('click', '.delete-history', function() {
			let row = $(this).closest('tr');
			let editId = row.data('id'); // เก็บ edit_id ไว้ที่ data-id

			if (!editId) {
				Swal.fire({
					icon: 'error',
					title: 'ไม่พบ ID',
					text: 'ไม่พบ ID ของรายการนี้',
					confirmButtonColor: '#ff7b59'
				});
				return;
			}

			// ยืนยันการลบ
			Swal.fire({
				title: 'คุณแน่ใจหรือไม่?',
				text: 'ต้องการลบรายการนี้ใช่หรือไม่?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'ลบ',
				cancelButtonText: 'ยกเลิก'
			}).then((result) => {
				if (result.isConfirmed) {
					showLoading(); // แสดง overlay loading

					$.ajax({
						url: 'ajax/delete_edit_history.php',
						type: 'POST',
						data: {
							edit_id: editId
						},
						success: function(res) {
							hideLoading(); // ซ่อน overlay

							if (res.trim() === 'success') {
								var table = $('#editHistoryTable').DataTable();
								table.row(row).remove().draw();

								Swal.fire({
									icon: 'success',
									title: 'ลบสำเร็จ!',
									timer: 1500,
									showConfirmButton: false
								});
							} else {
								Swal.fire({
									icon: 'error',
									title: 'ลบไม่สำเร็จ!',
									text: 'ไม่สามารถลบรายการนี้ได้',
									confirmButtonColor: '#ff7b59'
								});
							}
						},
						error: function(err) {
							hideLoading();
							console.error(err);
							Swal.fire({
								icon: 'error',
								title: 'เกิดข้อผิดพลาด!',
								text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
								confirmButtonColor: '#ff7b59'
							});
						}
					});
				}
			});
		});



		function showLoading() {
			$('#loadingOverlay').css('display', 'flex');
		}

		function hideLoading() {
			$('#loadingOverlay').hide();
		}

		function resetForm() {
			$('#workInForm')[0].reset();
			$('#doc_id').val('');
			selectedFile = null;
			$('#fileName').text('');
			$('#uploadProgress').hide();
			$('#addModalLabel').text('เพิ่มข้อมูล');
			if (table) {
				table.ajax.reload();
			} // ✅ รีโหลดข้อมูลใหม่
		}


		function editData(id) {
			showLoading();

			$.ajax({
				url: 'ajax/get_single.php',
				type: 'POST',
				data: {
					id: id
				},
				success: function(response) {
					hideLoading();
					try {
						let data = JSON.parse(response);
						if (data.status === 'success') {
							let item = data.data;
							$('#doc_id').val(item.doc_id);
							$('#doc_qic_id').val(item.doc_qic_id);
							$('#doc_type').val(item.doc_type);
							$('#doc_code').val(item.doc_code);
							$('#doc_topic').val(item.doc_topic);
							$('#doc_dept').val(item.doc_dept);

							// แปลงวันที่ให้ตรง format YYYY-MM-DD
							$('#doc_eft_date').val(formatDateForInput(item.doc_eft_date));
							$('#doc_imp_date').val(formatDateForInput(item.doc_imp_date));

							$('#doc_note').val(item.doc_note);

							$('#addModalLabel').text('แก้ไขข้อมูล');
							$('#addModal').modal('show');
						}
					} catch (e) {
						Swal.fire({
							icon: 'error',
							title: 'เกิดข้อผิดพลาด!',
							text: 'ไม่สามารถโหลดข้อมูลได้'
						});
					}
				},
				error: function() {
					hideLoading();
					Swal.fire({
						icon: 'error',
						title: 'เกิดข้อผิดพลาด!',
						text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
					});
				}
			});
		}

		// ฟังก์ชันแปลงวันที่เป็น YYYY-MM-DD
		function formatDateForInput(dateStr) {
			if (!dateStr) return '';

			let d = new Date(dateStr);
			if (isNaN(d)) {
				// กรณีเป็น format dd/mm/yyyy
				const parts = dateStr.split('/');
				if (parts.length === 3) {
					return `${parts[2]}-${parts[1].padStart(2,'0')}-${parts[0].padStart(2,'0')}`;
				}
				return '';
			} else {
				// ปกติเป็น format yyyy-mm-dd หรือ Date object
				const yyyy = d.getFullYear();
				const mm = (d.getMonth() + 1).toString().padStart(2, '0');
				const dd = d.getDate().toString().padStart(2, '0');
				return `${yyyy}-${mm}-${dd}`;
			}
		}


		function deleteData(id) {
			Swal.fire({
				title: 'ยืนยันการลบ',
				text: 'คุณต้องการลบข้อมูลนี้หรือไม่?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'ลบ',
				cancelButtonText: 'ยกเลิก'
			}).then((result) => {
				if (result.isConfirmed) {
					showLoading();

					$.ajax({
						url: 'ajax/delete_data.php',
						type: 'POST',
						data: {
							id: id
						},
						success: function(response) {
							hideLoading();
							try {
								let result = JSON.parse(response);
								if (result.status === 'success') {
									Swal.fire({
										icon: 'success',
										title: 'สำเร็จ!',
										text: result.message,
										timer: 1500,
										showConfirmButton: false
									}).then(() => {
										table.ajax.reload();
									});
								} else {
									Swal.fire({
										icon: 'error',
										title: 'เกิดข้อผิดพลาด!',
										text: result.message
									});
								}
							} catch (e) {
								Swal.fire({
									icon: 'error',
									title: 'เกิดข้อผิดพลาด!',
									text: 'ไม่สามารถประมวลผลได้'
								});
							}
						},
						error: function() {
							hideLoading();
							Swal.fire({
								icon: 'error',
								title: 'เกิดข้อผิดพลาด!',
								text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
							});
						}
					});
				}
			});
		}

function exportAPI(){
    Swal.fire({
        title: 'เลือกประเภทการส่งออก',
        text: 'คุณต้องการส่งออกข้อมูลในรูปแบบใด?',
        icon: 'question',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Excel (.xlsx)',
        denyButtonText: 'JSON (.json)',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#28a745', // ✅ ปุ่ม Excel สีเขียว success
        denyButtonColor: '#0d6efd',    // ปุ่ม JSON สีน้ำเงิน (info)
        cancelButtonColor: '#d33'      // ปุ่มยกเลิก สีแดง (danger)
    }).then(result => {
        if(result.isConfirmed){
            window.open('ajax/export_document_api.php?type=excel', '_blank');
        } else if(result.isDenied){
            window.open('ajax/export_document_api.php?type=json', '_blank');
        }
    });
}

        
        
	</script>
</body>

</html>
