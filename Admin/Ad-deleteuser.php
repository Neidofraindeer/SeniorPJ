<?php
require '../conn.php'; // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าได้รับค่า User_ID จาก URL หรือไม่
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id']; // รับค่า User_ID จาก URL

    // เช็คการเชื่อมต่อฐานข้อมูล 
    if ($conn) {
        // เริ่มต้นการลบข้อมูลในฐานข้อมูล
        // ลบข้อมูลใน tb_user
        $sql = "DELETE FROM tb_user WHERE User_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $login_sql = "DELETE FROM tb_login WHERE User_ID = ?";
            $login_stmt = $conn->prepare($login_sql);
            $login_stmt->bind_param("i", $user_id);
        
            if ($login_stmt->execute()) {
                // ลบสำเร็จ ให้เปลี่ยนเส้นทางไปยัง Ad-user.php
                header("Location: Ad-user.php");
                exit();
            } else {
                die("เกิดข้อผิดพลาดในการลบข้อมูลจาก tb_login");
            }
        } else {
            die("เกิดข้อผิดพลาดในการลบข้อมูลจาก tb_user");
        }
    }
} 
?>
