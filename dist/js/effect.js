(function() {
    const savedTheme = localStorage.getItem('theme');
    const savedFont = localStorage.getItem('font');
    const savedSize = localStorage.getItem('fontSize');
    
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
    }
    
    if (savedFont) {
        const style = document.createElement('style');
        style.id = 'fontOverride';
        style.innerHTML = `* { font-family: '${savedFont}', sans-serif !important; }`;
        document.head.appendChild(style);
    }
    
    if (savedSize) {
        document.body.style.fontSize = savedSize + 'px';
    }
})();

window.addEventListener('DOMContentLoaded', function() {
    loadSettings();
});

function loadSettings() {
    const savedTheme = localStorage.getItem('theme') || 'orange';
    const savedFont = localStorage.getItem('font') || 'Kanit';
    const savedSize = localStorage.getItem('fontSize') || '16';
    
    changeTheme(savedTheme);
    changeFont(savedFont);
    changeFontSize(savedSize);
}

function toggleGadget() {
    document.getElementById('gadgetPanel').classList.toggle('active');
}

function changeTheme(themeName) {
    // เพิ่ม transition สำหรับ fade effect
    document.body.style.transition = 'background-color 0.5s ease, color 0.5s ease';
    
    document.documentElement.setAttribute('data-theme', themeName);
    
    document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('active'));
    const selectedOption = document.querySelector(`[data-theme="${themeName}"]`);
    if (selectedOption) {
        selectedOption.classList.add('active');
    }
    
    localStorage.setItem('theme', themeName);
}

function changeFont(fontName) {
    const oldStyle = document.getElementById('fontOverride');
    if (oldStyle) oldStyle.remove();
    
    const style = document.createElement('style');
    style.id = 'fontOverride';
    style.innerHTML = `* { font-family: '${fontName}', sans-serif !important; }`;
    document.head.appendChild(style);
    
    document.querySelectorAll('.font-option').forEach(opt => opt.classList.remove('active'));
    const selectedOption = document.querySelector(`[data-font="${fontName}"]`);
    if (selectedOption) {
        selectedOption.classList.add('active');
    }
    
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
    changeFontSize('16');
    
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