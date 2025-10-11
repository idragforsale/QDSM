const themes = {
  blue:   { primary: "#007bff", secondary: "#3399ff", background: "#f0f8ff", accent: "#0056b3" },
  green:  { primary: "#28a745", secondary: "#20c997", background: "#f0fff5", accent: "#19692c" },
  orange: { primary: "#ff7b59", secondary: "#ff5e62", background: "#fff5f0", accent: "#ff4b3e" },
  dark:   { primary: "#343a40", secondary: "#495057", background: "#1a1a1a", accent: "#000" }
};

const fonts = {
  kanit: "'Kanit', sans-serif",
  niramit: "'Niramit', sans-serif"
};

// เปลี่ยนธีม
function applyTheme(themeName) {
  const theme = themes[themeName];
  for (const key in theme) {
    document.documentElement.style.setProperty(`--${key}`, theme[key]);
  }
  localStorage.setItem("theme", themeName);
}

// เปลี่ยนฟอนต์
function applyFont(fontName) {
  const font = fonts[fontName];
  document.documentElement.style.setProperty("--font", font);
  localStorage.setItem("font", fontName);
}

// โหลดค่าเดิมจาก localStorage ตอนเปิดหน้า
document.addEventListener("DOMContentLoaded", () => {
  const savedTheme = localStorage.getItem("theme") || "blue";
  const savedFont = localStorage.getItem("font") || "kanit";
  
  applyTheme(savedTheme);
  applyFont(savedFont);

  // set select ให้ตรงกับค่าเดิม
  const themeSelect = document.querySelector('select[onchange*="applyTheme"]');
  const fontSelect = document.querySelector('select[onchange*="applyFont"]');
  if (themeSelect) themeSelect.value = savedTheme;
  if (fontSelect) fontSelect.value = savedFont;
});
