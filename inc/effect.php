	<!-- Floating medical shapes -->
	<div class="floating-shapes">
		<!-- เครื่องหมายบวกแดง (Red Cross) -->
		<svg class="shape" width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
			<rect x="30" y="10" width="20" height="60" fill="url(#redCross)" rx="3" />
			<rect x="10" y="30" width="60" height="20" fill="url(#redCross)" rx="3" />
			<defs>
				<linearGradient id="redCross" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" style="stop-color:#ff4757" />
					<stop offset="100%" style="stop-color:#ff3838" />
				</linearGradient>
			</defs>
		</svg>

		<!-- หัวใจ (Heart) -->
		<svg class="shape" width="100" height="90" viewBox="0 0 100 90" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M50 80C50 80 10 55 10 35C10 25 17.5 15 30 15C37.5 15 45 20 50 25C55 20 62.5 15 70 15C82.5 15 90 25 90 35C90 55 50 80 50 80Z" fill="url(#heart)" stroke="#fff" stroke-width="2" />
			<defs>
				<linearGradient id="heart" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" style="stop-color:#ff6b9d" />
					<stop offset="100%" style="stop-color:#ff8fab" />
				</linearGradient>
			</defs>
		</svg>

		<!-- ป้ายโรงพยาบาล (Hospital Sign) -->
		<svg class="shape" width="90" height="60" viewBox="0 0 90 60" fill="none" xmlns="http://www.w3.org/2000/svg">
			<rect width="90" height="60" rx="8" fill="url(#hospitalSign)" stroke="#fff" stroke-width="2" />
			<rect x="35" y="15" width="6" height="30" fill="#fff" rx="1" />
			<rect x="20" y="25" width="30" height="6" fill="#fff" rx="1" />
			<text x="65" y="35" font-family="Arial" font-size="12" fill="#fff" font-weight="bold">H</text>
			<defs>
				<linearGradient id="hospitalSign" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" style="stop-color:#4a90e2" />
					<stop offset="100%" style="stop-color:#357abd" />
				</linearGradient>
			</defs>
		</svg>

		<!-- ยาเม็ด (Pills) -->
		<svg class="shape" width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
			<circle cx="25" cy="25" r="12" fill="url(#pill1)" stroke="#fff" stroke-width="1.5" />
			<circle cx="45" cy="45" r="12" fill="url(#pill2)" stroke="#fff" stroke-width="1.5" />
			<ellipse cx="35" cy="15" rx="8" ry="15" fill="url(#pill3)" stroke="#fff" stroke-width="1.5" />
			<defs>
				<linearGradient id="pill1" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" style="stop-color:#ffd93d" />
					<stop offset="100%" style="stop-color:#ffcd3c" />
				</linearGradient>
				<linearGradient id="pill2" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" style="stop-color:#6bcf7e" />
					<stop offset="100%" style="stop-color:#4fc3f7" />
				</linearGradient>
				<linearGradient id="pill3" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" style="stop-color:#ff9999" />
					<stop offset="100%" style="stop-color:#ff7979" />
				</linearGradient>
			</defs>
		</svg>

		<!-- เครื่องฟังหัวใจ (Stethoscope) -->
		<svg class="shape" width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M20 20C20 15 25 10 30 10C35 10 40 15 40 20V35C40 50 50 60 65 60C70 60 75 65 75 70C75 75 70 80 65 80C45 80 30 65 30 45V20" fill="none" stroke="url(#stethoscope)" stroke-width="4" stroke-linecap="round" />
			<circle cx="30" cy="15" r="8" fill="url(#stethoscope)" stroke="#fff" stroke-width="2" />
			<circle cx="65" cy="70" r="10" fill="url(#stethoscope)" stroke="#fff" stroke-width="2" />
			<defs>
				<linearGradient id="stethoscope" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" style="stop-color:#74b9ff" />
					<stop offset="100%" style="stop-color:#0984e3" />
				</linearGradient>
			</defs>
		</svg>

		<!-- ใบไผ่หรือสัญลักษณ์การรักษา (Medical Leaf) -->
		<svg class="shape" width="80" height="100" viewBox="0 0 80 100" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M40 95C40 95 10 80 10 50C10 20 25 5 40 5C55 5 70 20 70 50C70 80 40 95 40 95Z" fill="url(#medicalLeaf)" stroke="#fff" stroke-width="2" />
			<path d="M40 20 L40 80 M25 35 L40 50 L55 35" stroke="#fff" stroke-width="2" stroke-linecap="round" />
			<defs>
				<linearGradient id="medicalLeaf" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" style="stop-color:#00b894" />
					<stop offset="100%" style="stop-color:#00a085" />
				</linearGradient>
			</defs>
		</svg>
	</div>
	<!-- Floating particles -->
	<div class="particles" id="particles"></div>

	<!-- Wave overlay -->
	<div class="waves">
		<div class="wave"></div>
		<div class="wave"></div>
	</div>

	<div class="sparkle"></div>
	<div class="sparkle"></div>
	<div class="sparkle"></div>


	<script>
		// สร้าง particles แบบสุ่ม
		function createParticles() {
			const particlesContainer = document.getElementById('particles');
			const particleCount = 25;

			for (let i = 0; i < particleCount; i++) {
				const particle = document.createElement('div');
				particle.className = 'particle';

				// ตำแหน่งเริ่มต้นสุ่ม
				particle.style.left = Math.random() * 100 + '%';
				particle.style.animationDelay = Math.random() * 12 + 's';
				particle.style.animationDuration = (Math.random() * 8 + 8) + 's';

				// ขนาดสุ่ม
				const size = Math.random() * 4 + 4;
				particle.style.width = size + 'px';
				particle.style.height = size + 'px';

				particlesContainer.appendChild(particle);
			}
		}

		// เริ่มต้นสร้าง particles
		createParticles();

		// Parallax effect สำหรับ floating shapes
		window.addEventListener('scroll', function() {
			const scrolled = window.scrollY;
			const shapes = document.querySelectorAll('.shape');

			shapes.forEach((shape, index) => {
				const rate = scrolled * (0.1 + index * 0.05);
				shape.style.transform += ` translateY(${rate}px)`;
			});
		});

	</script>
	