<div class="theme-gadget">
    <button class="gadget-toggle" onclick="toggleGadget()">
        <i class="bi bi-palette"></i>
    </button>

    <div class="gadget-panel" id="gadgetPanel">
        <div class="gadget-header">
            <h3><i class="bi bi-paint-brush"></i> ปรับแต่งธีม</h3>
            <button class="close-gadget" onclick="toggleGadget()">
                <i class="bi bi-times"></i>
            </button>
        </div>

        <div class="gadget-section">
            <h4><i class="bi bi-palette"></i> ธีมสี</h4>
            <div class="color-palette">
                <div class="color-option active" onclick="changeTheme('orange')" 
                     style="background: linear-gradient(135deg, #ff7b59, #ff5e62);" data-theme="orange"></div>
                <div class="color-option" onclick="changeTheme('blue')" 
                     style="background: linear-gradient(135deg, #4facfe, #00f2fe);" data-theme="blue"></div>
                <div class="color-option" onclick="changeTheme('purple')" 
                     style="background: linear-gradient(135deg, #a8edea, #fed6e3);" data-theme="purple"></div>
                <div class="color-option" onclick="changeTheme('green')" 
                     style="background: linear-gradient(135deg, #56ab2f, #a8e063);" data-theme="green"></div>
                <div class="color-option" onclick="changeTheme('pink')" 
                     style="background: linear-gradient(135deg, #ff9a9e, #fecfef);" data-theme="pink"></div>
                <div class="color-option" onclick="changeTheme('dark')" 
                     style="background: linear-gradient(135deg, #2c3e50, #34495e);" data-theme="dark"></div>
                <!-- เพิ่มใน .color-palette -->
                <div class="color-option" onclick="changeTheme('bright')" 
                     style="background: linear-gradient(135deg, #ffe600, #ff6b6b);" data-theme="bright"></div>
                <div class="color-option" onclick="changeTheme('minimal')" 
                     style="background: linear-gradient(135deg, #f5f5f5, #e0e0e0);" data-theme="minimal"></div>
                <div class="color-option" onclick="changeTheme('seasonal')" 
                     style="background: linear-gradient(135deg, #FFDAC1, #B5EAD7);" data-theme="seasonal"></div>
            </div>
        </div>

        <div class="gadget-section">
            <h4><i class="bi bi-font"></i> แบบอักษร</h4>
            <div class="font-options">
                <div class="font-option active" onclick="changeFont('Kanit')" 
                     style="font-family: 'Kanit', sans-serif;" data-font="Kanit">Kanit - กานิษฐ์</div>
                <div class="font-option" onclick="changeFont('Sarabun')" 
                     style="font-family: 'Sarabun', sans-serif;" data-font="Sarabun">Sarabun - สารบรรณ</div>
                <div class="font-option" onclick="changeFont('Prompt')" 
                     style="font-family: 'Prompt', sans-serif;" data-font="Prompt">Prompt - พร้อมท์</div>
                <div class="font-option" onclick="changeFont('Mitr')" 
                     style="font-family: 'Mitr', sans-serif;" data-font="Mitr">Mitr - มิตร</div>
            </div>
        </div>

        <div class="gadget-section">
            <h4><i class="bi bi-text-paragraph"></i> ขนาดตัวอักษร</h4>
            <div class="size-control">
                <div class="size-slider">
                    <span>เล็ก</span>
                    <input type="range" id="fontSizeSlider" min="12" max="20" value="15" 
                           oninput="changeFontSize(this.value)">
                    <span>ใหญ่</span>
                </div>
                <div class="size-value"><span id="fontSizeValue">15</span> px</div>
            </div>
        </div>

        <button class="reset-btn" onclick="resetTheme()">
            <i class="bi bi-arrow-clockwise"></i> รีเซ็ตเป็นค่าเริ่มต้น
        </button>
    </div>
</div>


<!-- ✅ โหลดธีมทันที ก่อน DOM render -->
<script>
(function() {
    const savedTheme = localStorage.getItem('theme') || 'orange';
    const savedFont = localStorage.getItem('font') || 'Kanit';
    const savedSize = localStorage.getItem('fontSize') || '15';

    // ✅ ปิด transition ชั่วคราวเพื่อไม่ให้เกิดการแว่บของสี
    document.documentElement.style.transition = 'none';
    document.body.style.transition = 'none';

    // ✅ ตั้งธีมก่อน render
    document.documentElement.setAttribute('data-theme', savedTheme);

    // ✅ ฟอนต์
    const style = document.createElement('style');
    style.id = 'fontOverride';
    style.innerHTML = `* { font-family: '${savedFont}', sans-serif !important; }`;
    document.head.appendChild(style);

    // ✅ ขนาดฟอนต์
    document.body.style.fontSize = savedSize + 'px';

    // ✅ เมื่อ DOM โหลดเสร็จ: แสดงผล & เปิด transition กลับมา
    document.addEventListener('DOMContentLoaded', () => {
        document.documentElement.style.visibility = 'visible';
        document.documentElement.style.transition = '';
        document.body.style.transition = '';
        loadSettings();
    });
})();

// ===== ฟังก์ชันหลัก =====
function loadSettings() {
    const savedTheme = localStorage.getItem('theme') || 'orange';
    const savedFont = localStorage.getItem('font') || 'Kanit';
    const savedSize = localStorage.getItem('fontSize') || '15';

    changeTheme(savedTheme);
    changeFont(savedFont);
    changeFontSize(savedSize);
}

function toggleGadget() {
    document.getElementById('gadgetPanel').classList.toggle('active');
}

function changeTheme(themeName) {
    // ✅ ใส่ transition หลังโหลดหน้าแล้วเท่านั้น
    document.body.style.transition = 'background-color 0.5s ease, color 0.5s ease';
    document.documentElement.setAttribute('data-theme', themeName);

    // อัปเดตสถานะ active ของปุ่มธีม
    document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('active'));
    const selectedOption = document.querySelector(`[data-theme="${themeName}"]`);
    if (selectedOption) selectedOption.classList.add('active');

    localStorage.setItem('theme', themeName);
}

function changeFont(fontName) {
    const oldStyle = document.getElementById('fontOverride');
    if (oldStyle) oldStyle.remove();

    const style = document.createElement('style');
    style.id = 'fontOverride';
    style.innerHTML = `* { font-family: '${fontName}', sans-serif !important; }`;
    document.head.appendChild(style);

    // อัปเดตสถานะ active ของปุ่มฟอนต์
    document.querySelectorAll('.font-option').forEach(opt => opt.classList.remove('active'));
    const selectedOption = document.querySelector(`[data-font="${fontName}"]`);
    if (selectedOption) selectedOption.classList.add('active');

    localStorage.setItem('font', fontName);
}

function changeFontSize(size) {
    document.body.style.fontSize = size + 'px';

    const valueElement = document.getElementById('fontSizeValue');
    const sliderElement = document.getElementById('fontSizeSlider');

    if (valueElement) valueElement.textContent = size;
    if (sliderElement) sliderElement.value = size;

    localStorage.setItem('fontSize', size);
}

function resetTheme() {
    localStorage.removeItem('theme');
    localStorage.removeItem('font');
    localStorage.removeItem('fontSize');

    changeTheme('orange');
    changeFont('Kanit');
    changeFontSize('15');

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'รีเซ็ตเรียบร้อย',
            text: 'กลับไปใช้ธีมเริ่มต้นแล้ว',
            timer: 1500,
            showConfirmButton: false
        });
    } else {
        alert('รีเซ็ตการตั้งค่าเรียบร้อยแล้ว!');
    }
}
</script>