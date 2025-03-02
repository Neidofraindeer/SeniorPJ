<?php
session_start();

// เช็คว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_data']) || !isset($_SESSION['user_data']['user_id'])) {
    header("Location: /SeniorPJ/index.php"); // กลับไปหน้าเข้าสู่ระบบ
    exit();
}

// ดึงข้อมูลผู้ใช้ที่เข้าสู่ระบบ
$user_id = $_SESSION['user_data']['user_id']; // ดึง User_ID จาก session

// เชื่อมต่อกับฐานข้อมูล
require '../conn.php';

if ($conn) {
    // ดึงข้อมูลรูปภาพปัจจุบันจากฐานข้อมูล
    $stmt = $conn->prepare("SELECT User_Picture FROM tb_user WHERE User_ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($current_picture);
    $stmt->fetch();
    $stmt->close();

    // ตรวจสอบข้อมูลที่ถูกส่งมาจากฟอร์ม
    $firstname = isset($_POST['User_Firstname']) ? $_POST['User_Firstname'] : '';
    $lastname = isset($_POST['User_Lastname']) ? $_POST['User_Lastname'] : '';
    $nickname = isset($_POST['User_Nickname']) ? $_POST['User_Nickname'] : '';
    $phone = isset($_POST['User_Tel']) ? $_POST['User_Tel'] : '';

    // ตั้งค่ารูปภาพเป็นค่าปัจจุบัน
    $user_picture = $current_picture;

    // ตรวจสอบว่ามีการอัปโหลดรูปภาพใหม่หรือไม่
    if (isset($_FILES['User_Picture']) && $_FILES['User_Picture']['error'] == 0) {
        $upload_dir = '../uploads/'; // โฟลเดอร์เก็บไฟล์

        // ดึงนามสกุลไฟล์ (เช่น .jpg, .png)
        $file_extension = strtolower(pathinfo($_FILES['User_Picture']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif']; // นามสกุลที่อนุญาต

        if (!in_array($file_extension, $allowed_extensions)) {
            echo "ประเภทไฟล์ไม่ถูกต้อง (อนุญาตเฉพาะ JPG, JPEG, PNG, GIF เท่านั้น)";
            exit;
        }

        // ตั้งชื่อไฟล์ใหม่ให้ไม่ซ้ำกัน (ใช้ user_id + timestamp)
        $new_filename = "../uploads/" . time() . "." . $file_extension;
        $target_file = $upload_dir . $new_filename; // พาธไฟล์ที่อัปโหลด

        // ย้ายไฟล์ไปยังโฟลเดอร์อัปโหลด
        if (move_uploaded_file($_FILES['User_Picture']['tmp_name'], $target_file)) {
            $user_picture = $new_filename; // บันทึกแค่ชื่อไฟล์ลงฐานข้อมูล
        } else {
            echo "ไม่สามารถอัปโหลดรูปภาพได้";
            exit;
        }
    }

    // อัปเดตข้อมูลผู้ใช้ใน tb_user
    $sql = "UPDATE tb_user SET 
            User_Firstname = ?, 
            User_Lastname = ?, 
            User_Nickname = ?, 
            User_Tel = ?, 
            User_Picture = ? 
            WHERE User_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $firstname, $lastname, $nickname, $phone, $user_picture, $user_id);
    $stmt->execute();

    // ตรวจสอบผลการอัปเดต
    if ($stmt->affected_rows > 0) {
        echo "ข้อมูลถูกอัปเดตสำเร็จ";
        header("Location: Ad-mainpage.php");
        exit();
    } else {
        echo "ไม่มีการเปลี่ยนแปลงข้อมูล";
    }
} else {
    echo "ไม่สามารถเชื่อมต่อฐานข้อมูล";
}
?>
