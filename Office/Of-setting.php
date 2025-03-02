<?php
session_start();

// เช็คว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_data'])) {
    header("Location: /SeniorPJ/index.php"); // กลับไปหน้าเข้าสู่ระบบ
    exit();
}
?>