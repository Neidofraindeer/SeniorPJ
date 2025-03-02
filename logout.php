<?php
session_start();
include 'conn.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่า session ผู้ใช้มีการล็อกอินอยู่หรือไม่
if (isset($_SESSION['user_data']['user_id'])) {
    $userId = $_SESSION['user_data']['user_id'];

    // รีเซ็ตสถานะ Logged_in เป็น 0 ในฐานข้อมูล
    $updateLoginStatus = "UPDATE tb_user SET Logged_in = 0 WHERE User_ID = ?";
    $updateStmt = $conn->prepare($updateLoginStatus);
    $updateStmt->bind_param("i", $userId);
    $updateStmt->execute();

    // ลบข้อมูลใน session
    session_unset();
    session_destroy();
    
    // รีไดเรกต์ไปที่หน้า index.php
    header("Location: index.php");
    exit();
} else {
    // ถ้าไม่มี session หรือผู้ใช้ไม่ได้ล็อกอิน
    header("Location: index.php");
    exit();
}
?>
