<?php
session_start();
require '../conn.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_data']['user_id'])) {
    header("Location: /SeniorPJ/index.php");
    exit();
}

$user_id = $_SESSION['user_data']['user_id'];

// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    // ดึงรหัสผ่านปัจจุบันจากฐานข้อมูล
    $sql = "SELECT Password FROM tb_login WHERE User_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $db_password = $row["Password"];
    
        // ตรวจสอบว่ารหัสผ่านเดิมถูกต้องหรือไม่
        if (!password_verify($old_password, $db_password)) {
            $_SESSION['error_message'] = "รหัสผ่านเดิมไม่ถูกต้อง";
            header("Location: Of-repassword.php"); // กลับไปหน้าฟอร์ม
            exit();
        }
    
        // ตรวจสอบว่ารหัสผ่านใหม่กับยืนยันรหัสผ่านใหม่ตรงกันหรือไม่
        if ($new_password !== $confirm_password) {
            $_SESSION['error_message'] = "รหัสผ่านใหม่และยืนยันรหัสผ่านใหม่ไม่ตรงกัน";
            header("Location: Of-repassword.php"); // กลับไปหน้าฟอร์ม
            exit();
        }
    
        // แฮชรหัสผ่านใหม่ก่อนอัปเดต
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
        // อัปเดตรหัสผ่านใหม่
        $update_sql = "UPDATE tb_login SET Password = ? WHERE User_ID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $hashed_password, $user_id);
    
        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = "เปลี่ยนรหัสผ่านสำเร็จ";
            header("Location: Of-repassword.php");
            exit();
        } else {
            $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการอัปเดตรหัสผ่าน";
            header("Location: Of-repassword.php");
            exit();
        }
    }
}
?>
