ติดตั้ง composer

############################################################

composer require vlucas/phpdotenv
composer require phpoffice/phpspreadsheet

############################################################

วิธีแก้แบบ Step by Step (Windows + PHP 8.3 + IIS)

1. เปิด fileinfo และ zip extensions ใน php.ini

ไปที่โฟลเดอร์ PHP ของคุณ เช่น

C:\Program Files (x86)\PHP\v8.3\

เปิดไฟล์ php.ini ด้วย Notepad หรือโปรแกรมแก้ไขข้อความ

ค้นหา (Ctrl+F):

;extension=fileinfo
;extension=zip


เอาเครื่องหมาย ; ด้านหน้าออก → ให้เป็นแบบนี้:

extension=fileinfo
extension=zip


กดบันทึก (Ctrl+S)

2. รีสตาร์ต IIS (หรือ PHP)

เปิด Command Prompt (Run as Administrator) แล้วพิมพ์:

iisreset


หรือถ้าใช้ PHP ผ่าน CLI อย่างเดียวก็ปิด/เปิด Terminal ใหม่

3. ตรวจสอบว่าเปิด extension สำเร็จหรือยัง

รันคำสั่ง:

php -m

ดูว่าในรายการมีบรรทัด

fileinfo
zip

ปรากฏอยู่หรือไม่ ✅

4. ติดตั้งเวอร์ชันที่รองรับกับ PHP 8.3

แนะนำให้ใช้เวอร์ชันล่าสุด (5.1.0) หลังจากเปิด extension แล้วรัน:

composer require phpoffice/phpspreadsheet

หากยังติดปัญหาเรื่องเวอร์ชัน PHP (เช่น composer มองผิด)
สามารถใช้ flag ข้ามการตรวจสอบ platform ได้ชั่วคราว:

composer require phpoffice/phpspreadsheet --ignore-platform-reqs

(แต่ไม่แนะนำถ้า extension ยังไม่ครบ)