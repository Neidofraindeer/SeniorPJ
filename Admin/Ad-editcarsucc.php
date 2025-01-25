<?php
require '../conn.php'; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Work_ID = $_POST['Work_ID'];
    $Car_ID = $_POST['Car_ID'];
    $User_ID = $_POST['User_ID'] ?? null; // ตรวจสอบว่าได้รับค่าหรือไม่

    // ตรวจสอบข้อมูลแต่ละช่อง หากไม่ได้แก้ไขให้เก็บค่าเดิม
    $CarNumber = $_POST['CarNumber'] ?: $row['CarNumber'];
    $CarBrand = $_POST['CarBrand'] ?: $row['CarBrand'];
    $CarModel = $_POST['CarModel'] ?: $row['CarModel'];
    $CarColor = $_POST['CarColor'] ?: $row['CarColor'];
    $CarInsurance = $_POST['CarInsurance'] ?: $row['CarInsurance'];
    $CarDetail = $_POST['CarDetail'] ?: $row['CarDetail'];
    $params = [$CarNumber, $CarBrand, $CarModel, $CarColor, $CarInsurance, $CarDetail];

    // ตรวจสอบไฟล์อัปโหลด หากไม่มีไฟล์ใหม่ ให้เก็บไฟล์เดิม
    if (isset($_FILES['CarPicture']) && $_FILES['CarPicture']['error'] == 0) {
        $upload_dir = '../uploads/car/';
        $car_picture_name = basename($_FILES['CarPicture']['name']);
        $car_picture_tmp = $_FILES['CarPicture']['tmp_name'];
        $car_picture_path = $upload_dir . $car_picture_name;
    
        if (!move_uploaded_file($car_picture_tmp, $car_picture_path)) {
            echo "ไม่สามารถอัพโหลดรูปภาพ CarPicture ได้";
            exit;
        }
        $params[] = $car_picture_name;
    }
    
    if (isset($_FILES['RepairPicture']) && $_FILES['RepairPicture']['error'] == 0) {
        $upload_dir = '../uploads/repair/';
        $repair_picture_name = basename($_FILES['RepairPicture']['name']);
        $repair_picture_tmp = $_FILES['RepairPicture']['tmp_name'];
        $repair_picture_path = $upload_dir . $repair_picture_name;
    
        if (!move_uploaded_file($repair_picture_tmp, $repair_picture_path)) {
            echo "ไม่สามารถอัพโหลดรูปภาพ RepairPicture ได้";
            exit;
        }
        $params[] = $repair_picture_name;
    }
    // สร้างคำสั่ง SQL สำหรับการอัปเดต
    $sql = "UPDATE tb_car SET CarNumber = ?, CarBrand = ?, CarModel = ?, CarColor = ?, CarInsurance = ?, CarDetail = ?";

    // เพิ่มฟิลด์สำหรับ CarPicture หากมีการอัพโหลดใหม่
    if (isset($car_picture_name)) {
        $sql .= ", CarPicture = ?";
    }

    // เพิ่มฟิลด์สำหรับ RepairPicture หากมีการอัพโหลดใหม่
    if (isset($repair_picture_name)) {
        $sql .= ", RepairPicture = ?";
    }

    // เพิ่มเงื่อนไข WHERE
    $sql .= " WHERE Car_ID = ?";
    $params[] = $Car_ID;

    // เตรียมและรันคำสั่ง SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        str_repeat('s', count($params) - 1) . 'i', // กำหนดประเภทข้อมูล (string และ integer)
        ...$params // ส่งค่าอาร์กิวเมนต์
    );

    // รันคำสั่ง SQL
    if ($stmt->execute()) {
        echo "อัปเดตข้อมูลสำเร็จ";
        header("refresh: 1; url= Ad-mainpage.php");
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
}
?>