<?php
require '../conn.php'; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Work_ID = $_POST['Work_ID'];
    $Car_ID = $_POST['Car_ID'];
    $User_ID = $_POST['User_ID'] ?? null; // ตรวจสอบว่าได้รับค่าหรือไม่

     // ดึงค่าปัจจุบันจากฐานข้อมูลเพื่อใช้ในกรณีไม่ได้อัปเดตบางค่า
     $sqlCurrent = "SELECT CarNumber, CarBrand, CarModel, CarColor, CarInsurance, CarDetail, CarPicture, RepairPicture FROM tb_car WHERE Car_ID = ?";
     $stmtCurrent = $conn->prepare($sqlCurrent);
     $stmtCurrent->bind_param("i", $Car_ID);
     $stmtCurrent->execute();
     $resultCurrent = $stmtCurrent->get_result();
     $row = $resultCurrent->fetch_assoc();

    // ตรวจสอบข้อมูลแต่ละช่อง หากไม่ได้แก้ไขให้เก็บค่าเดิม
    $CarNumber = $_POST['CarNumber'] ?: $row['CarNumber'];
    $CarBrand = $_POST['CarBrand'] ?: $row['CarBrand'];
    $CarModel = $_POST['CarModel'] ?: $row['CarModel'];
    $CarColor = $_POST['CarColor'] ?: $row['CarColor'];
    $CarInsurance = $_POST['CarInsurance'] ?: $row['CarInsurance'];
    $CarDetail = $_POST['CarDetail'] ?: $row['CarDetail'];
    

     // ตรวจสอบไฟล์อัปโหลด หากไม่มีไฟล์ใหม่ให้ใช้ไฟล์เดิม
     $car_picture_path = $row['CarPicture']; // ค่าเดิมจาก DB
     if (!empty($_FILES['CarPicture']['name'])) { // ตรวจสอบว่ามีไฟล์ใหม่ถูกอัปโหลด
         $new_car_picture_path = "../uploads/car/" . uniqid("car_", true) . ".jpg";
         if (move_uploaded_file($_FILES['CarPicture']['tmp_name'], $new_car_picture_path)) {
             $car_picture_path = $new_car_picture_path; // ใช้ไฟล์ใหม่แทนค่าเดิม
         } else {
             echo "ไม่สามารถอัพโหลดรูปภาพ CarPicture ได้";
             exit;
         }
     }
 
     $repair_picture_path = $row['RepairPicture']; // ค่าเดิมจาก DB
     if (!empty($_FILES['RepairPicture']['name'])) { // ตรวจสอบว่ามีไฟล์ใหม่ถูกอัปโหลด
         $new_repair_picture_path = "../uploads/repair/" . uniqid("repair_", true) . ".jpg";
         if (move_uploaded_file($_FILES['RepairPicture']['tmp_name'], $new_repair_picture_path)) {
             $repair_picture_path = $new_repair_picture_path; // ใช้ไฟล์ใหม่แทนค่าเดิม
         } else {
             echo "ไม่สามารถอัพโหลดรูปภาพ RepairPicture ได้";
             exit;
         }
     }
     // อัปเดตข้อมูลใน tb_car
    $sql = "UPDATE tb_car 
        SET CarNumber = ?, CarBrand = ?, CarModel = ?, CarColor = ?, CarInsurance = ?, CarDetail = ?, CarPicture = ?, RepairPicture = ? 
        WHERE Car_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $CarNumber, $CarBrand, $CarModel, $CarColor, $CarInsurance, $CarDetail, $car_picture_path, $repair_picture_path, $Car_ID);

    if ($stmt->execute()) {
        echo "อัปเดตข้อมูลสำเร็จ";
        header("refresh: 1; url= Ad-mainpage.php");
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
    $stmt->close();
    }
$conn->close();
?>