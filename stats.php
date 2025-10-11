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

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css" rel="stylesheet">
	<link href="/qdsm/dist/css/styles.css?v=<?=filemtime($_SERVER['DOCUMENT_ROOT'].'/qdsm/dist/css/styles.css') ?>" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
	

</head>

<body>
	
<?php require 'inc/head-admin.php'; ?>   



	<div class="container-fluid pb-4">
		<div class="search-card shadow mt-4 animate__animated animate__fadeInUp">
			<div class="card-body p-4">
				<div class="row">
					<div class="col-lg-12">

						<h6><i class="bi bi-info-circle text-orange"></i> รายงานสถิติ</h6>



						<div class="row">

							<div class="col-md-12">
								<!-- Dropdown เลือกปีงบ -->
								<div class="mb-3">
									<label for="fiscalYearSelect" class="form-label">เลือกปีงบประมาณ:</label>
									<select id="fiscalYearSelect" class="form-select" style="width:auto;display:inline-block;"></select>
								</div>
							</div>
							<div class="col-md-8">
								<!-- กราฟ -->
								<canvas id="statChart" height="120"></canvas>
							</div>
							<div class="col-md-4">
								<!-- ตาราง -->
								<table class="table table-hover" id="statTable">
									<thead>
										<tr>
											<th>ประเภทเอกสาร</th>
											<th>จำนวนเอกสาร</th>
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
	</div>

	<!-- JS -->
	<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
	<script>
let statDataTable;
let statChart;

function loadStats(year) {
    $.getJSON("ajax/get_stats.php", { year: year }, function(data) {
        const labels = data.map(r => r.type_name);
        const counts = data.map(r => Number(r.total));

        // ดึงสีจาก CSS Variables
        const rootStyles = getComputedStyle(document.documentElement);
        const backgroundColors = [];
        const borderColors = [];
        for (let i = 0; i < data.length; i++) {
            const idx = (i % 5) + 1; // หมุนสี 1-5
            backgroundColors.push(rootStyles.getPropertyValue(`--chart-bar-${idx}`).trim());
            borderColors.push(rootStyles.getPropertyValue(`--chart-border-${idx}`).trim());
        }

        // อัปเดต Chart
        if (statChart) statChart.destroy();
        const ctx = document.getElementById("statChart").getContext("2d");
        statChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: "จำนวนเอกสาร",
                    data: counts,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 }
                    }
                }
            }
        });

        // อัปเดต Table
        if (statDataTable) {
            statDataTable.clear();
            statDataTable.rows.add(data.map(r => [r.type_name, r.total]));
            statDataTable.draw();
        } else {
            statDataTable = $('#statTable').DataTable({
                data: data.map(r => [r.type_name, r.total]),
                columns: [
                    { title: "ประเภทเอกสาร" },
                    { title: "จำนวนเอกสาร", className: "text-center" }
                ],
                paging: false,
                searching: false,
                info: false
            });
        }
    });
}


$(document).ready(function() {
    const select = $("#fiscalYearSelect");
    const currentYear = new Date().getFullYear();

    // สร้าง dropdown ปีงบ
    for (let y = currentYear + 1; y >= currentYear - 4; y--) {
        select.append(`<option value="${y}">ปีงบ ${y + 543}</option>`);
    }
    select.val(currentYear + 1);

    loadStats(select.val());

    select.change(function() {
        loadStats($(this).val());
    });
});
</script>


</body>
</html>
