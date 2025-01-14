<?php
session_start(); // เริ่มต้น session เพื่อเก็บข้อมูลผู้ใช้

// เชื่อมต่อกับฐานข้อมูล
include('conn.php'); // รวมไฟล์เชื่อมต่อฐานข้อมูลที่คุณสร้างไว้ก่อนหน้านี้

// ตรวจสอบว่าเป็นการส่งฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่าได้รับค่าจากฟอร์มหรือไม่
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // รับค่าจากฟอร์ม
        $username = $_POST['username'];
        $password = $_POST['password'];

        // ตรวจสอบว่าไม่ว่าง
        if (empty($username) || empty($password)) {
            echo "กรุณากรอกชื่อผู้ใช้และรหัสผ่าน";
        } else {
            // คำสั่ง SQL เพื่อค้นหาผู้ใช้จากตาราง tb_login
            $sql = "SELECT * FROM tb_login WHERE username = ?";

            // เตรียมคำสั่ง SQL
            if ($stmt = $conn->prepare($sql)) {
                // ผูกค่าพารามิเตอร์
                $stmt->bind_param("s", $username);
                // รันคำสั่ง SQL
                $stmt->execute();
                // เก็บผลลัพธ์
                $result = $stmt->get_result();

                // ถ้าพบผู้ใช้
                if ($result->num_rows > 0) {
                    // ดึงข้อมูลของผู้ใช้
                    $user = $result->fetch_assoc();

                    // ตรวจสอบรหัสผ่าน (หากใช้ password_hash())
                    if (password_verify($password, $user['password'])) {
                        // ตั้งค่า session สำหรับผู้ใช้ที่ล็อกอิน
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['role'] = $user['role'];

                        // เปลี่ยนหน้าไปยังหน้าที่เหมาะสมตาม role
                        if ($user['role'] == 0) {
                            header("Location: Ad-mainpage.php");
                        } elseif ($user['role'] == 1) {
                            header("Location: Of-mainpage.php");
                        } elseif ($user['role'] == 2) {
                            header("Location: Mc-mainpage.php");
                        }
                        exit;
                    } else {
                        echo "รหัสผ่านไม่ถูกต้อง";
                    }
                } else {
                    echo "ชื่อผู้ใช้ไม่ถูกต้อง";
                }
                $stmt->close();
            }
        }
    } else {
        echo "กรุณากรอกชื่อผู้ใช้และรหัสผ่าน";
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $conn->close();
}
?>
