<?php
session_start();
require 'inc/authentication-admin.php';
require "config.php";
?>
<!DOCTYPE html>
<html lang="th">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>QDSM : นำเข้าข้อมูล</title>

	<!-- Google Fonts - Kanit -->
	<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500&family=Niramit:wght@100;200;300;400;500&family=Sarabun:wght@100;200;300;400;500&family=Prompt:wght@100;200;300;400;500&family=Mitr:wght@100;200;300;400;500&display=swap" rel="stylesheet">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css" rel="stylesheet">
	<link href="/qdsm/dist/css/styles.css?v=<?=filemtime($_SERVER['DOCUMENT_ROOT'].'/qdsm/dist/css/styles.css') ?>" rel="stylesheet">
	
	<style>
		.upload-area {
			border: 3px dashed var(--primary-color);
			border-radius: 20px;
			padding: 60px 40px;
			text-align: center;
			background: linear-gradient(135deg, var(--bg-1), var(--bg-2));
			transition: all 0.3s ease;
			cursor: pointer;
			min-height: 300px;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
		}
		
		.upload-area:hover {
			border-color: var(--secondary-color);
			background: linear-gradient(135deg, var(--bg-2), var(--bg-3));
			transform: translateY(-5px);
			box-shadow: 0 10px 30px rgba(0,0,0,0.1);
		}
		
		.upload-area.dragover {
			border-color: var(--accent-color);
			background: linear-gradient(135deg, var(--bg-3), var(--bg-4));
			transform: scale(1.02);
		}
		
		.upload-icon {
			font-size: 80px;
			color: var(--primary-color);
			margin-bottom: 20px;
			animation: bounce 2s infinite;
		}
		
		@keyframes bounce {
			0%, 100% { transform: translateY(0); }
			50% { transform: translateY(-10px); }
		}
		
		.file-preview {
			background: white;
			border: 2px solid var(--primary-color);
			border-radius: 15px;
			padding: 20px;
			margin-top: 20px;
		}
		
		.step-indicator {
			display: flex;
			justify-content: space-between;
			margin-bottom: 30px;
			position: relative;
		}
		
		.step {
			flex: 1;
			text-align: center;
			position: relative;
			z-index: 1;
		}
		
		.step-number {
			width: 50px;
			height: 50px;
			border-radius: 50%;
			background: #e9ecef;
			color: #6c757d;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			font-weight: bold;
			margin-bottom: 10px;
			transition: all 0.3s;
		}
		
		.step.active .step-number {
			background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
			color: white;
			transform: scale(1.1);
		}
		
		.step.completed .step-number {
			background: #28a745;
			color: white;
		}
		
		.step-line {
			position: absolute;
			top: 25px;
			left: 0;
			right: 0;
			height: 3px;
			background: var(--secondary-color);
			z-index: 0;
		}
		
		.import-log {
			max-height: 400px;
			overflow-y: auto;
			background: #000;
			border-radius: 10px;
			padding: 20px;
			font-family: 'Courier New', monospace;
			font-size: 14px;
		}
		
		.log-success { color: #28a745; }
		.log-error { color: #dc3545; }
		.log-warning { color: #ffc107; }
		.log-info { color: #17a2b8; }
	</style>
</head>

<body>
    
	<?php require 'inc/head-admin.php'; ?>  
    
	<!-- Main Content -->
	<div class="container-fluid pb-4">
		<div class="search-card shadow mt-4 animate__animated animate__fadeInUp">
			<div class="card-body p-4">

				<h6><i class="bi bi-cloud-upload text-orange"></i> นำเข้าข้อมูล</h6>
				<hr>

				<!-- Step Indicator -->
				<div class="step-indicator">
					<div class="step-line"></div>
					<div class="step active" id="step1">
						<div class="step-number">1</div>
						<div class="step-label">เลือกไฟล์</div>
					</div>
					<div class="step" id="step2">
						<div class="step-number">2</div>
						<div class="step-label">ตรวจสอบ</div>
					</div>
					<div class="step" id="step3">
						<div class="step-number">3</div>
						<div class="step-label">นำเข้า</div>
					</div>
					<div class="step" id="step4">
						<div class="step-number">4</div>
						<div class="step-label">เสร็จสิ้น</div>
					</div>
				</div>

				<!-- Download Template Button -->
				<div class="text-center mb-4">
					<a href="create_template.php" class="btn btn-info btn-lg">
						<i class="bi bi-download me-2"></i> ดาวน์โหลดไฟล์ Template
					</a>
					<p class="text-muted mt-2">
						<i class="bi bi-info-circle"></i> ดาวน์โหลดไฟล์ตัวอย่างพร้อมคำอธิบายวิธีการใช้งาน
					</p>
				</div>

				<!-- Upload Area -->
				<div class="row">
					<div class="col-lg-12">
						<div class="upload-area" id="uploadArea">
							<i class="bi bi-cloud-arrow-up upload-icon"></i>
							<h4 class="mb-3">ลากไฟล์มาวางที่นี่</h4>
							<p class="text-muted mb-3">หรือคลิกเพื่อเลือกไฟล์</p>
							<input type="file" id="fileInput" class="d-none" accept=".xlsx,.xls,.csv">
							<div class="mt-3">
								<span class="badge bg-primary me-2"><i class="bi bi-file-earmark-excel"></i> .xlsx</span>
								<span class="badge bg-success me-2"><i class="bi bi-file-earmark-excel"></i> .xls</span>
								<span class="badge bg-info"><i class="bi bi-file-earmark-text"></i> .csv</span>
							</div>
							<small class="text-muted mt-2 d-block">ขนาดไฟล์สูงสุด: 10 MB</small>
						</div>

						<!-- File Preview -->
						<div class="file-preview d-none" id="filePreview">
							<div class="d-flex justify-content-between align-items-center">
								<div class="d-flex align-items-center">
									<i class="bi bi-file-earmark-excel text-success fs-1 me-3"></i>
									<div>
										<h5 class="mb-1" id="fileName"></h5>
										<small class="text-muted" id="fileSize"></small>
									</div>
								</div>
								<button class="btn btn-danger" id="removeFile">
									<i class="bi bi-trash"></i> ลบไฟล์
								</button>
							</div>
						</div>

						<!-- Import Button -->
						<div class="text-center mt-4 d-none" id="importBtnContainer">
							<button class="btn btn-lg btn-success px-5" id="importBtn">
								<i class="bi bi-upload me-2"></i> เริ่มนำเข้าข้อมูล
							</button>
						</div>

						<!-- Progress -->
						<div class="mt-4 d-none" id="progressContainer">
							<h5><i class="bi bi-hourglass-split text-orange"></i> กำลังนำเข้าข้อมูล...</h5>
							<div class="progress" style="height: 30px;">
								<div class="progress-bar progress-bar-striped progress-bar-animated" 
									 id="progressBar" 
									 role="progressbar" 
									 style="width: 0%">0%</div>
							</div>
							<p class="text-muted mt-2 text-center" id="progressText">กำลังเตรียมข้อมูล...</p>
						</div>

						<!-- Import Log -->
						<div class="mt-4 d-none" id="logContainer">
							<h5><i class="bi bi-journal-text text-orange"></i> บันทึกการนำเข้า</h5>
							<div class="import-log" id="importLog"></div>
						</div>

						<!-- Result Summary -->
						<div class="mt-4 d-none" id="resultContainer">
							<div class="alert alert-success">
								<h5><i class="bi bi-check-circle"></i> นำเข้าข้อมูลเสร็จสิ้น</h5>
								<hr>
								<div class="row text-center">
									<div class="col-md-4">
										<h2 class="text-success" id="successCount">0</h2>
										<p>สำเร็จ</p>
									</div>
									<div class="col-md-4">
										<h2 class="text-warning" id="skipCount">0</h2>
										<p>ข้าม</p>
									</div>
									<div class="col-md-4">
										<h2 class="text-danger" id="errorCount">0</h2>
										<p>ผิดพลาด</p>
									</div>
								</div>
								<div class="text-center mt-3">
									<a href="/qdsm/manage" class="btn btn-primary">
										<i class="bi bi-house"></i> กลับหน้าหลัก
									</a>
									<button class="btn btn-secondary" id="resetBtn">
										<i class="bi bi-arrow-clockwise"></i> นำเข้าไฟล์ใหม่
									</button>
								</div>
							</div>
						</div>

					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Scripts -->
	<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
	
	<script>
		const uploadArea = document.getElementById('uploadArea');
		const fileInput = document.getElementById('fileInput');
		const filePreview = document.getElementById('filePreview');
		const importBtnContainer = document.getElementById('importBtnContainer');
		const importBtn = document.getElementById('importBtn');
		const progressContainer = document.getElementById('progressContainer');
		const logContainer = document.getElementById('logContainer');
		const resultContainer = document.getElementById('resultContainer');
		
		let selectedFile = null;

		// Click to select file
		uploadArea.addEventListener('click', () => fileInput.click());

		// File selected
		fileInput.addEventListener('change', (e) => {
			handleFile(e.target.files[0]);
		});

		// Drag & Drop
		uploadArea.addEventListener('dragover', (e) => {
			e.preventDefault();
			uploadArea.classList.add('dragover');
		});

		uploadArea.addEventListener('dragleave', () => {
			uploadArea.classList.remove('dragover');
		});

		uploadArea.addEventListener('drop', (e) => {
			e.preventDefault();
			uploadArea.classList.remove('dragover');
			handleFile(e.dataTransfer.files[0]);
		});

		function handleFile(file) {
			if (!file) return;

			const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
								'application/vnd.ms-excel', 
								'text/csv'];
			const maxSize = 10 * 1024 * 1024; // 10MB

			if (!validTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls|csv)$/)) {
				Swal.fire({
					icon: 'error',
					title: 'ไฟล์ไม่ถูกต้อง',
					text: 'กรุณาเลือกไฟล์ .xlsx, .xls หรือ .csv เท่านั้น',
					confirmButtonColor: '#ff7b59'
				});
				return;
			}

			if (file.size > maxSize) {
				Swal.fire({
					icon: 'error',
					title: 'ไฟล์ใหญ่เกินไป',
					text: 'ขนาดไฟล์ต้องไม่เกิน 10 MB',
					confirmButtonColor: '#ff7b59'
				});
				return;
			}

			selectedFile = file;
			showFilePreview(file);
			updateStep(2);
		}

		function showFilePreview(file) {
			document.getElementById('fileName').textContent = file.name;
			document.getElementById('fileSize').textContent = formatFileSize(file.size);
			
			uploadArea.classList.add('d-none');
			filePreview.classList.remove('d-none');
			importBtnContainer.classList.remove('d-none');
		}

		function formatFileSize(bytes) {
			if (bytes === 0) return '0 Bytes';
			const k = 1024;
			const sizes = ['Bytes', 'KB', 'MB', 'GB'];
			const i = Math.floor(Math.log(bytes) / Math.log(k));
			return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
		}

		document.getElementById('removeFile').addEventListener('click', resetUpload);
		document.getElementById('resetBtn').addEventListener('click', resetUpload);

		function resetUpload() {
			selectedFile = null;
			fileInput.value = '';
			uploadArea.classList.remove('d-none');
			filePreview.classList.add('d-none');
			importBtnContainer.classList.add('d-none');
			progressContainer.classList.add('d-none');
			logContainer.classList.add('d-none');
			resultContainer.classList.add('d-none');
			updateStep(1);
		}

		function updateStep(step) {
			for (let i = 1; i <= 4; i++) {
				const stepEl = document.getElementById(`step${i}`);
				stepEl.classList.remove('active', 'completed');
				if (i < step) stepEl.classList.add('completed');
				if (i === step) stepEl.classList.add('active');
			}
		}

		function addLog(message, type = 'info') {
			const log = document.getElementById('importLog');
			const time = new Date().toLocaleTimeString('th-TH');
			log.innerHTML += `<div class="log-${type}">[${time}] ${message}</div>`;
			log.scrollTop = log.scrollHeight;
		}

		// Import Process
		importBtn.addEventListener('click', async () => {
			if (!selectedFile) return;

			updateStep(3);
			importBtnContainer.classList.add('d-none');
			progressContainer.classList.remove('d-none');
			logContainer.classList.remove('d-none');

			addLog('เริ่มต้นการนำเข้าข้อมูล...', 'info');
			
			const formData = new FormData();
			formData.append('file', selectedFile);

			try {
				const response = await fetch('ajax/import_data.php', {
					method: 'POST',
					body: formData
				});

				const reader = response.body.getReader();
				const decoder = new TextDecoder();

				while (true) {
					const {done, value} = await reader.read();
					if (done) break;

					const chunk = decoder.decode(value);
					const lines = chunk.split('\n');

					lines.forEach(line => {
						if (!line.trim()) return;
						
						try {
							const data = JSON.parse(line);
							
							if (data.progress) {
								const percent = data.progress;
								document.getElementById('progressBar').style.width = percent + '%';
								document.getElementById('progressBar').textContent = percent + '%';
								document.getElementById('progressText').textContent = data.message || 'กำลังประมวลผล...';
							}
							
							if (data.log) {
								addLog(data.log, data.type || 'info');
							}
							
							if (data.complete) {
								showResult(data);
							}
						} catch (e) {
							console.error('Parse error:', e, line);
						}
					});
				}

			} catch (error) {
				console.error('Import error:', error);
				addLog('เกิดข้อผิดพลาดในการนำเข้าข้อมูล: ' + error.message, 'error');
				Swal.fire({
					icon: 'error',
					title: 'เกิดข้อผิดพลาด',
					text: 'ไม่สามารถนำเข้าข้อมูลได้ กรุณาลองใหม่อีกครั้ง',
					confirmButtonColor: '#ff7b59'
				});
			}
		});

		function showResult(data) {
			updateStep(4);
			progressContainer.classList.add('d-none');
			resultContainer.classList.remove('d-none');

			document.getElementById('successCount').textContent = data.success || 0;
			document.getElementById('skipCount').textContent = data.skipped || 0;
			document.getElementById('errorCount').textContent = data.errors || 0;

			addLog('=== นำเข้าข้อมูลเสร็จสิ้น ===', 'success');
			addLog(`สำเร็จ: ${data.success} รายการ`, 'success');
			addLog(`ข้าม: ${data.skipped} รายการ`, 'warning');
			addLog(`ผิดพลาด: ${data.errors} รายการ`, 'error');
		}
	</script>

</body>
</html>