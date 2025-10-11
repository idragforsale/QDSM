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
		<div class="search-card shadow mt-4">
			<div class="card-body p-4">
				<div class="row">
					<div class="col-lg-12">
						
						<h6 class="mb-3">
							<i class="bi bi-list-ol text-orange"></i> รายการ ประเภทเอกสารทั้งหมด
						</h6>
					
						<span class="quick-filter mb-1" data-bs-toggle="modal" data-bs-target="#typeModal" onclick="resetForm()">
							<i class="bi bi-plus-circle"></i> เพิ่มข้อมูล
						</span>

                        <span class="quick-filter mb-1" onclick="exportAPI()">
                            <i class="bi bi-cloud-download"></i> ส่งออก
                        </span>

                        <!-- DataTable -->
                        <div class="table-responsive">
                            <table id="typeTable" class="table table-hover align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>รหัสประเภท</th>
                                        <th>ชื่อประเภท</th>
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
	<div class="modal fade" id="typeModal" tabindex="-1" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
		<div class="modal-content">
			<form id="typeForm">
			  <input type="hidden" name="action" id="form_action" value="insert">
			  <input type="hidden" id="old_type_code" name="old_type_code">
			<div class="modal-header">
			  <h5 class="modal-title" id="modalTitle">เพิ่มประเภทเอกสาร</h5>
			  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
<div class="modal-body">
    <div class="mb-3">
        <label for="type_code" class="form-label">รหัสประเภท</label>
        <input type="text" id="type_code" name="type_code" class="form-control border-orange" maxlength="1">
		<small class="text-danger">* กรุณากรอก</small>
    </div>
    <div class="mb-3">
        <label for="type_name" class="form-label">ชื่อประเภท</label>
        <input type="text" id="type_name" name="type_name" class="form-control border-orange">
		<small class="text-danger">* กรุณากรอก</small>
    </div>
	<div class="mb-3">
		<label for="type_short_name" class="form-label">ชื่อย่อ</label>
		<input type="text" id="type_short_name" name="type_short_name" class="form-control border-orange" maxlength="10">
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

$(document).ready(function() {
    // Initialize DataTable
    table = $('#typeTable').DataTable({
        serverSide: true,
        ajax: { url: 'ajax/get_type.php', type: 'POST' },
        columns: [
            { data: 0 }, // type_code
            { data: 1 }, // type_name
            { data: 2 }, // type_short_name
            { data: 3, orderable: false } // ปุ่มแก้ไข/ลบ
        ]
    })

    // Form submit (รวมทุกอย่างไว้ที่นี่)
    $('#typeForm').on('submit', function(e){
        e.preventDefault();

        // ดึงค่าฟิลด์
        const typeName = $('#type_name').val().trim();
        const typeCode = $('#type_code').val().trim();
		const typeShort = $('#type_short_name').val().trim();

        // ตรวจสอบว่ากรอกครบหรือไม่
if (!typeName || !typeCode || !typeShort) {
    Swal.fire({
        icon: 'warning',
        title: 'กรอกข้อมูลไม่ครบถ้วน',
        text: 'กรุณากรอก รหัสประเภท, ชื่อประเภท และชื่อย่อ',
        confirmButtonColor: '#ffc107',
        customClass: {
            popup: 'animate__animated animate__bounceIn'
        }
    });
    return; // หยุด Ajax
}

        // ส่งข้อมูล Ajax ถ้าครบ
        $.ajax({
            url: 'ajax/save_type.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                let r = (typeof res==='string')? JSON.parse(res):res;

                if(r.status==='success'){
                    Swal.fire('สำเร็จ', r.message,'success');
                    $('#typeModal').modal('hide');
                    table.ajax.reload();
                } 
                else if(r.status==='warning'){ 
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรอกข้อมูลไม่ครบ',
                        text: r.message,
                        confirmButtonColor: '#ffc107'
                    });
                }
                else{
                    Swal.fire('ผิดพลาด', r.message,'error');
                }
            },
            error:function(xhr){
                Swal.fire('Error','ไม่สามารถเชื่อมต่อ server ได้','error');
                console.error(xhr.responseText);
            }
        });

    });
});

// ฟังก์ชันช่วย
function editType(code, name, shortName){
    $('#modalTitle').text('แก้ไขประเภทเอกสาร');
    $('#type_code').val(code);
    $('#type_name').val(name);
    $('#type_short_name').val(shortName); // ✅ เพิ่มตรงนี้
    $('#old_type_code').val(code);
    $('#form_action').val('update');
    new bootstrap.Modal(document.getElementById('typeModal')).show();
}

function resetForm(){
    $('#modalTitle').text('เพิ่มประเภทเอกสาร');
    $('#typeForm')[0].reset();
    $('#old_type_code').val('');
    $('#form_action').val('insert');

    // ดึงรหัสล่าสุดจาก server
    $.get('ajax/get_next_type_code.php', function(res){
        let r = (typeof res==='string')? JSON.parse(res):res;
        if(r.status === 'success'){
            $('#type_code').val(r.next_code);
        } else {
            $('#type_code').val(''); // ถ้า error
            Swal.fire('ผิดพลาด', r.message,'error');
        }
    });
}


function deleteType(code){
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: 'ข้อมูลจะถูกลบถาวร',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ยกเลิก'
    }).then((result)=>{
        if(result.isConfirmed){
            $.post('ajax/delete_type.php',{type_code:code},function(res){
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
            window.open('ajax/export_type_api.php?type=excel', '_blank');
        } else if(result.isDenied){
            window.open('ajax/export_type_api.php?type=json', '_blank');
        }
    });
}

</script>

</body>

</html>
