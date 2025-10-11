		let searchTable;

		$(document).ready(function() {
			// Initialize DataTable
			initializeSearchTable();

			// ✅ เปิดการ์ดผลลัพธ์ตั้งแต่แรก
			$('#resultsCard').show();

			// ✅ โหลดเอกสารทั้งหมดตั้งแต่แรก
			searchTable.ajax.reload();

			// Quick filter events
			$('.quick-filter').on('click', function() {
				$('.quick-filter').removeClass('active');
				$(this).addClass('active');

				// Auto search when filter changes
				performSearch();
			});

			// Enter key search
			$('#mainSearch, #searchQIC, #searchDept').on('keypress', function(e) {
				if (e.which === 13) {
					performSearch();
				}
			});

			// Auto search on date change
			$('#searchStartDate, #searchEndDate').on('change', function() {
				performSearch();
			});
		});

		function initializeSearchTable() {
			searchTable = $('#searchTable').DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					url: 'ajax/search_data.php',
					type: 'POST',
					data: function(d) {
						// Add search parameters
						d.main_search = $('#mainSearch').val();
						d.qic_search = $('#searchQIC').val();
						d.dept_search = $('#searchDept').val();
						d.start_date = $('#searchStartDate').val();
						d.end_date = $('#searchEndDate').val();
						d.type_filter = $('.quick-filter.active').data('type');
					},
					beforeSend: function() {
						showLoading();
					},
					complete: function(data) {
						hideLoading();
						// Update result count
						if (data.responseJSON && data.responseJSON.recordsFiltered !== undefined) {
							$('#resultCount').text(data.responseJSON.recordsFiltered);
						}
					},
					error: function(xhr, error, thrown) {
						hideLoading();
						console.error('Search Error:', error);
						Swal.fire({
							icon: 'error',
							title: 'เกิดข้อผิดพลาด!',
							text: 'ไม่สามารถค้นหาข้อมูลได้',
							customClass: {
								popup: 'animate__animated animate__bounceIn'
							}
						});
					}
				},
				columns: [
					{ data: 0, width: "7%" },  // ลำดับ QIC
					{ data: 1, width: "14%" },  // รหัสประเภท
					{ data: 2, width: "10%" }, // เลขที่
					{ data: 3, width: "25%" }, // เรื่อง
					{ data: 4, width: "12%" }, // ชื่อหน่วยงาน
					{ data: 5, width: "10%" }, // วันที่บังคับใช้
					{ data: 6, width: "5%" }, // จำนวนผู้อ่าน
					{ data: 7, width: "8%" },  // ไฟล์
					{
						data: 8,
						orderable: false,
						searchable: false,
						width: "8%"
					} // เอกสาร
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
				pageLength: 10,
				lengthMenu: [
					[10, 25, 50, 100],
					[10, 25, 50, 100]
				]
			});
		}

		function performSearch() {
			const mainSearch = $('#mainSearch').val().trim();
			const qicSearch = $('#searchQIC').val().trim();
			const deptSearch = $('#searchDept').val().trim();
			const startDate = $('#searchStartDate').val();
			const endDate = $('#searchEndDate').val();
			const typeFilter = $('.quick-filter.active').data('type');

			// Check if any search criteria is provided
			if (!mainSearch && !qicSearch && !deptSearch && !startDate && !endDate && typeFilter === 'all') {
				Swal.fire({
					icon: 'info',
					title: 'กรุณาใส่คำค้นหา',
					text: 'กรอกคำค้นหาหรือเลือกตัวกรองอย่างน้อย 1 รายการ',
					customClass: {
						popup: 'animate__animated animate__bounceIn'
					}
				});
				return;
			}

			// Validate date range
			if (startDate && endDate && startDate > endDate) {
				Swal.fire({
					icon: 'warning',
					title: 'ช่วงวันที่ไม่ถูกต้อง',
					text: 'วันที่เริ่มต้นต้องน้อยกว่าหรือเท่ากับวันที่สิ้นสุด',
					customClass: {
						popup: 'animate__animated animate__bounceIn'
					}
				});
				return;
			}

			// Show results card with animation
			$('#resultsCard').show().removeClass('animate__fadeIn').addClass('animate__animated animate__fadeIn');

			// Reload table with new search parameters
			searchTable.ajax.reload(function(json) {
				// Update result count after reload
				if (json && json.recordsFiltered !== undefined) {
					$('#resultCount').text(json.recordsFiltered);

					// Show message if no results found
					if (json.recordsFiltered === 0) {
						Swal.fire({
							icon: 'info',
							title: 'ไม่พบข้อมูล',
							text: 'ไม่พบเอกสารที่ตรงกับเงื่อนไขการค้นหา',
							customClass: {
								popup: 'animate__animated animate__bounceIn'
							}
						});
					}
				}
			});
		}

		function clearSearch() {
			// ล้างค่า input
			$('#mainSearch, #searchQIC, #searchDept, #searchStartDate, #searchEndDate').val('');

			// รีเซ็ต Quick Filter
			$('.quick-filter').removeClass('active');
			$('.quick-filter[data-type="all"]').addClass('active');

			// แสดงผลลัพธ์
			$('#resultsCard').show();

			// โหลดข้อมูลทั้งหมดใหม่
			searchTable.ajax.reload();

			Swal.fire({
				icon: 'success',
				title: 'รีเซ็ตเรียบร้อย',
				text: 'ล้างข้อมูลการค้นหาแล้ว',
				timer: 1500,
				showConfirmButton: false,
				customClass: {
					popup: 'animate__animated animate__bounceIn'
				}
			});
		}

		function readDocument(fileName) {
			if (!fileName) {
				Swal.fire({
					icon: 'warning',
					title: 'ไม่พบเอกสาร',
					text: 'เอกสารนี้ยังไม่ได้อัปโหลด',
					confirmButtonColor: '#ff7b59'
				});
				return;
			}

			// เปิดหน้าใหม่
			let url = 'document.php?id=' + encodeURIComponent(fileName);
			window.open(url, '_blank');
		}

		function viewDocument(filename, title) {
			if (!filename) {
				Swal.fire({
					icon: 'warning',
					title: 'ไม่พบไฟล์',
					text: 'ไม่มีไฟล์เอกสารสำหรับรายการนี้',
					customClass: {
						popup: 'animate__animated animate__bounceIn'
					}
				});
				return;
			}

			const fileUrl = `uploads/${filename}`;
			const fileExtension = filename.split('.').pop().toLowerCase();

			$('#documentModalLabel').html(`<i class="bi bi-file-earmark-pdf"></i> ${title || 'เอกสาร'}`);

			if (['pdf'].includes(fileExtension)) {
				// For PDF files
				$('#documentViewer').attr('src', fileUrl);
				$('#downloadBtn').attr('href', fileUrl);
				$('#documentModal').modal('show');
			} else if (['doc', 'docx', 'xls', 'xlsx'].includes(fileExtension)) {
				// For Office documents, try to use Google Docs Viewer
				// Note: This may not work for localhost, consider using alternative viewers
				const viewerUrl = `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(window.location.origin + '/' + fileUrl)}`;
				$('#documentViewer').attr('src', viewerUrl);
				$('#downloadBtn').attr('href', fileUrl);
				$('#documentModal').modal('show');

				// Show a note about viewer limitations
				setTimeout(() => {
					if ($('#documentViewer').contents().find('body').html() === '') {
						Swal.fire({
							title: 'ไม่สามารถแสดงตัวอย่างได้',
							text: 'คลิกปุ่มดาวน์โหลดเพื่อเปิดไฟล์',
							icon: 'info',
							customClass: {
								popup: 'animate__animated animate__bounceIn'
							}
						});
					}
				}, 3000);

			} else {
				// For other file types, direct download
				Swal.fire({
					title: 'ดาวน์โหลดไฟล์',
					text: `ไฟล์ ${fileExtension.toUpperCase()} จะถูกดาวน์โหลดโดยอัตโนมัติ`,
					icon: 'info',
					showCancelButton: true,
					confirmButtonText: 'ดาวน์โหลด',
					cancelButtonText: 'ยกเลิก',
					customClass: {
						popup: 'animate__animated animate__bounceIn'
					}
				}).then((result) => {
					if (result.isConfirmed) {
						// Create a temporary link element for download
						const link = document.createElement('a');
						link.href = fileUrl;
						link.download = filename;
						document.body.appendChild(link);
						link.click();
						document.body.removeChild(link);
					}
				});
			}
		}

		function showLoading() {
			$('#loadingOverlay').css('display', 'flex');
		}

		function hideLoading() {
			$('#loadingOverlay').hide();
		}

		// Add some sample data for demonstration
		$(document).ready(function() {
			// Set today's date as default end date
			const today = new Date().toISOString().split('T')[0];
			$('#searchEndDate').val(today);

			// Set 30 days ago as default start date
			const thirtyDaysAgo = new Date();
			thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
			$('#searchStartDate').val(thirtyDaysAgo.toISOString().split('T')[0]);

			// Keyboard shortcuts
			$(document).keydown(function(e) {
				// Ctrl + F = Focus on main search
				if (e.ctrlKey && e.keyCode === 70) {
					e.preventDefault();
					$('#mainSearch').focus();
				}
				// Escape = Clear search
				if (e.keyCode === 27) {
					clearSearch();
				}
			});

			// Auto search when typing (with debounce)
			let searchTimeout;
			$('#mainSearch').on('input', function() {
				clearTimeout(searchTimeout);
				const searchValue = $(this).val().trim();

				if (searchValue.length >= 2) {
					searchTimeout = setTimeout(() => {
						performSearch();
					}, 1000); // Wait 1 second after user stops typing
				}
			});

			// Show search tips
			setTimeout(() => {
				if (!$('#resultsCard').is(':visible')) {
					Swal.fire({
						icon: 'info',
						title: 'เคล็ดลับการค้นหา',
						html: `
                    <div class="text-start">
                        <p><i class="bi bi-lightbulb text-warning"></i> <strong>เคล็ดลับ:</strong></p>
                        <ul>
                            <li>ใช้ <kbd>Ctrl+F</kbd> เพื่อโฟกัสช่องค้นหา</li>
                            <li>ใช้ <kbd>Esc</kbd> เพื่อรีเซ็ตการค้นหา</li>
                            <li>ระบบจะค้นหาอัตโนมัติเมื่อพิมพ์ครบ 2 ตัวอักษร</li>
                            <li>สามารถค้นหาได้หลายเงื่อนไขพร้อมกัน</li>
                        </ul>
                    </div>
                `,
						showConfirmButton: false,
						timer: 5000,
						customClass: {
							popup: 'animate__animated animate__bounceIn'
						}
					});
				}
			}, 3000);
		});