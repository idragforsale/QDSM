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
	<title>QDSM : Admin Console</title>

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
		<div class="search-card shadow mt-4 animate__animated animate__fadeInUp">
			<div class="card-body p-4">
				<div class="row">
					<div class="col-lg-12">
					
						<h6 class="mb-3">
							<i class="bi bi-list-ol text-orange"></i> รายการ หน่วยงานทั้งหมด
						</h6>
						
						<span class="quick-filter mb-1" data-bs-toggle="modal" data-bs-target="#deptModal" onclick="resetForm()">
							<i class="bi bi-plus-circle"></i> เพิ่มข้อมูล
						</span>
                        
                        <!-- ปุ่ม Export API -->
                        <span class="quick-filter mb-1" onclick="exportAPI()">
                            <i class="bi bi-cloud-download"></i> ส่งออก
                        </span>

						<!-- DataTable -->
						<div class="table-responsive">
                            <table id="deptTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <!-- ❌ ลบลำดับออก -->
                                        <th>รหัสแผนก</th>
                                        <th>ชื่อแผนก (TH)</th>
                                        <th>ชื่อแผนก (EN)</th>
                                        <th>ชื่อย่อ</th>
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

<!-- Modal Add/Edit -->
<div class="modal fade" id="deptModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <form id="deptForm">
            <input type="hidden" name="action" id="form_action" value="insert">
            <input type="hidden" name="old_dep_code" id="old_dep_code">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">เพิ่มแผนก</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">รหัสแผนก</label>
                    <input type="text" class="form-control border-orange" name="dep_code" id="dep_code" maxlength="4">
					<small class="text-danger">* กรุณากรอก</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">ชื่อแผนก (TH)</label>
                    <input type="text" class="form-control border-orange" name="dep_name_th" id="dep_name_th">
					<small class="text-danger">* กรุณากรอก</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">ชื่อแผนก (EN)</label>
                    <input type="text" class="form-control border-orange" name="dep_name_en" id="dep_name_en">
					<small class="text-danger">* กรุณากรอก</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">ชื่อย่อ</label>
                    <input type="text" class="form-control border-orange" name="dep_name_short" id="dep_name_short" maxlength="10">
					<small class="text-danger">* กรุณากรอก</small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">ยกเลิก</button>
                <button class="btn btn-success" type="submit">บันทึก</button>
            </div>
        </form>
    </div>
  </div>
</div>

<script>
let table;
$(document).ready(function(){
    table = $('#deptTable').DataTable({
        serverSide: true,
        ajax: { url: 'ajax/get_department.php', type: 'POST' },
        columns: [
            { data: 0 }, // dep_code
            { data: 1 }, // dep_name_th
            { data: 2 }, // dep_name_en
            { data: 3 }, // dep_name_short
            { data: 4, orderable: false } // ปุ่มจัดการ
        ]
    });

    $('#deptForm').on('submit',function(e){
        e.preventDefault();
        const code = $('#dep_code').val().trim();
        const nameTH = $('#dep_name_th').val().trim();
        const nameEN = $('#dep_name_en').val().trim();
        const shortName = $('#dep_name_short').val().trim();

        
        // ตรวจสอบว่ากรอกครบหรือไม่
if(!code || !nameTH || !nameEN || !shortName){
    Swal.fire({
        icon: 'warning',
        title: 'กรอกข้อมูลไม่ครบถ้วน',
        text: 'กรุณากรอก รหัสแผนก, ชื่อแผนก (TH), ชื่อแผนก (EN) และชื่อย่อ',
        confirmButtonColor: '#ffc107',
        customClass: {
            popup: 'animate__animated animate__bounceIn'
        }
    });
    return; // หยุด Ajax
}

        

        $.post('ajax/save_department.php', $(this).serialize(), function(res){
            let r = (typeof res==='string')? JSON.parse(res):res;
            if(r.status==='success'){
                Swal.fire('สำเร็จ', r.message,'success');
                $('#deptModal').modal('hide');
                table.ajax.reload();
            } else {
                Swal.fire('ผิดพลาด', r.message,'error');
            }
        });
    });
});

function editDept(code,nameTH,nameEN,shortName){
    $('#modalTitle').text('แก้ไขแผนก');
    $('#dep_code').val(code);
    $('#dep_name_th').val(nameTH);
    $('#dep_name_en').val(nameEN);
    $('#dep_name_short').val(shortName);
    $('#old_dep_code').val(code);
    $('#form_action').val('update');
    new bootstrap.Modal(document.getElementById('deptModal')).show();
}

function resetForm(){
    $('#modalTitle').text('เพิ่มแผนก');
    $('#deptForm')[0].reset();  // <-- แก้ตรงนี้
    $('#old_dep_code').val('');
    $('#form_action').val('insert');

    // ดึงรหัสล่าสุดจาก server
    $.get('ajax/get_next_dep_code.php', function(res){
        let r = (typeof res==='string') ? JSON.parse(res) : res;
        if(r.status === 'success'){
            $('#dep_code').val(r.next_code);
        } else {
            $('#dep_code').val(''); // ถ้า error
            Swal.fire('ผิดพลาด', r.message,'error');
        }
    });
}


function deleteDept(code){
    Swal.fire({
        title:'ยืนยันการลบ?',
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'ลบ',
        cancelButtonText:'ยกเลิก'
    }).then(result=>{
        if(result.isConfirmed){
            $.post('ajax/delete_department.php',{dep_code:code}, function(res){
                let r = (typeof res==='string')? JSON.parse(res):res;
                if(r.status==='success'){
                    Swal.fire('สำเร็จ',r.message,'success');
                    table.ajax.reload();
                } else {
                    Swal.fire('ผิดพลาด',r.message,'error');
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
            window.open('ajax/export_department_api.php?type=excel', '_blank');
        } else if(result.isDenied){
            window.open('ajax/export_department_api.php?type=json', '_blank');
        }
    });
}


    
</script>

</body>

</html>
