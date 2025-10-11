<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="text-center text-orange">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">กำลังค้นหา...</span>
        </div>
        <div class="mt-2 fw-bold">กำลังค้นหาข้อมูล...</div>
    </div>
</div>

<? /* require 'inc/effect.php'; */ ?>

<nav class="qsdm-navbar" id="navbar">
    <div class="qsdm-navbar-top">
        <div class="qsdm-brand">
            <a href="manage" class="qsdm-logo">
                <i class="bi bi-journal-medical"></i>
            </a>
            <div class="qsdm-brand-text">
                <h1>QDSM <span class="hide-mobile">: Quality Document System Management</span></h1>
                <p><i class="bi bi-award text-orange"></i> ระบบบริหารคุณภาพเอกสาร โรงพยาบาลหนองคาย</p>
            </div>
        </div>

        <div class="qsdm-user-info">
            <div class="qsdm-user-badge">
                <i class="bi bi-person-circle"></i>
                <span><?= $profile['title_th'] . ' ' . $profile['firstname_th'] . ' ' . $profile['lastname_th'] ?></span>
            </div>
            <a href="#" class="qsdm-logout-btn" id="logoutBtn">
                <i class="bi bi-door-closed"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <div class="qsdm-menu">
        <a href="/qdsm" class="qsdm-menu-item">
            <i class="bi bi-house-door text-orange"></i>
            <span>เอกสารทั้งหมด</span>
            
                <?php
                $totalDocuments = 0; // default
                $sqlCount = "SELECT COUNT(*) AS total FROM tb_document";
                $resultCount = $conn->query($sqlCount);
                if ($resultCount) {
                    $rowCount = $resultCount->fetch_assoc();
                    $totalDocuments = intval($rowCount['total']);
                } else {
                    die("SQL Error (count tb_document): " . $conn->error);
                }       
                ?>
            
            <span class="qsdm-menu-badge"><?php echo $totalDocuments; ?></span>
        </a>
    </div>
</nav>

<script>
    document.getElementById("logoutBtn").addEventListener("click", function(e) {
        e.preventDefault();

        Swal.fire({
            title: "คุณต้องการออกจากระบบ?",
            text: "หากยืนยัน คุณจะต้องเข้าสู่ระบบใหม่อีกครั้ง",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "ใช่, ออกจากระบบ",
            cancelButtonText: "ยกเลิก",
            confirmButtonColor: "#ef4444",
            cancelButtonColor: "#6b7280"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "logout";
            }
        });
    });

</script>
<?php include 'theme-gadget.php'; ?>


 <? /*
	<div class="header-card">
		<div class="header-content">
			<div class="title-row">
				
				<!-- โลโก้ด้านซ้าย -->
				<a class="icon-wrapper" href="manage">
					<i class="bi bi-journal-medical"></i>
				</a>

				<!-- ข้อความด้านขวา -->
				<div class="title-content">
					<h2>QDSM : Admin Console</h2>
					<div class="title-subrow">
						<!-- ซ้าย -->
						<div class="badge-quality">
							<i class="bi bi-award"></i> ระบบจัดการเอกสาร (สำหรับผู้ดูแล)
						</div>

						<!-- ขวา -->
						<div class="user-actions">
							<div class="badge-quality fs-6">
								<i class="bi bi-person-circle"></i> 
								<?= $profile['title_th'] . ' ' . $profile['firstname_th'] . ' ' . $profile['lastname_th'] ?>
							</div>
							
							<a class="btn btn-lg btn-dark fs-6" data-type="1" id="logoutBtn">
								<i class="bi bi-door-closed"></i> logout
							</a>
							<script>
								document.getElementById("logoutBtn").addEventListener("click", function(e) {
									e.preventDefault(); // ❌ กันไม่ให้เด้งไป logout.php ทันที

									Swal.fire({
										title: "คุณต้องการออกจากระบบ?",
										text: "หากยืนยัน คุณจะต้องเข้าสู่ระบบใหม่อีกครั้ง",
										icon: "warning",
										showCancelButton: true,
										confirmButtonText: "ใช่, ออกจากระบบ",
										cancelButtonText: "ยกเลิก",
										confirmButtonColor: "#ef4444",
										cancelButtonColor: "#6b7280"
									}).then((result) => {
										if (result.isConfirmed) {
											window.location.href = "logout.php"; // ✅ ไป logout จริง
										}
									});
								});
							</script>
				
						</div>
					</div>				
				</div>
			</div>
		</div>
	</div>

  */ ?>  
    
