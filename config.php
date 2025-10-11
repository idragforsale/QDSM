<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// ✅ Database 1 (หลัก) - ใช้กับเว็บเกือบทั้งหมด
$hostserver     = $_ENV['DB_HOST_1'];
$database_conn  = $_ENV['DB_NAME_1'];
$username_conn  = $_ENV['DB_USER_1'];
$password_conn  = $_ENV['DB_PASS_1'];

$conn = mysqli_connect($hostserver, $username_conn, $password_conn, $database_conn);
if (!$conn) {
    die("Connection failed (DB1): " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");

// ✅ Database 2 (เพิ่มเติม) - ใช้สำหรับดึงข้อมูลอีกแหล่ง
$hostserver2     = $_ENV['DB_HOST_2'];
$database_conn2  = $_ENV['DB_NAME_2'];
$username_conn2  = $_ENV['DB_USER_2'];
$password_conn2  = $_ENV['DB_PASS_2'];

$conn2 = mysqli_connect($hostserver2, $username_conn2, $password_conn2, $database_conn2);
if (!$conn2) {
    die("Connection failed (DB2): " . mysqli_connect_error());
}
mysqli_set_charset($conn2, "utf8mb4");
?>
