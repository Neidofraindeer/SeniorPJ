<?php
include '../conn.php';

// รับค่าจากฟอร์ม
$carNumber = $_POST['CarNumber'];
$carBrand = $_POST['CarBrand'];
$carModel = $_POST['CarModel'];
$carColor = $_POST['CarColor'];
$carInsurance = $_POST['CarInsurance'];
$carDetail = $_POST['CarDetail'];
$userID = $_POST['User_ID'];

// อัปโหลดรูปภาพ CarPicture
$carPicture = $_FILES['CarPicture']['name'];
$carPictureTmp = $_FILES['CarPicture']['tmp_name'];
$carPicturePath = "../uploads/car/" . uniqid("car_", true) . ".jpg"; // ใช้ชื่อไฟล์ไม่ซ้ำ
if (!move_uploaded_file($carPictureTmp, $carPicturePath)) {
    echo "ไม่สามารถอัปโหลดไฟล์ภาพรถยนต์ได้";
    exit;
}

// อัปโหลดรูปภาพ RepairPicture
$repairPicture = $_FILES['RepairPicture']['name'];
$repairPictureTmp = $_FILES['RepairPicture']['tmp_name'];
$repairPicturePath = "../uploads/repair/" . uniqid("repair_", true) . ".jpg"; // ใช้ชื่อไฟล์ไม่ซ้ำ
if (!move_uploaded_file($repairPictureTmp, $repairPicturePath)) {
    echo "ไม่สามารถอัปโหลดไฟล์ภาพซ่อมแซมได้";
    exit;
}

// INSERT ข้อมูลลงใน tb_car
$sqlCar = "INSERT INTO tb_car (CarNumber, User_ID, CarBrand, CarModel, CarColor, CarInsurance, CarDetail, CarPicture, RepairPicture)
           VALUES ('$carNumber', '$userID', '$carBrand', '$carModel', '$carColor', '$carInsurance', '$carDetail', '$carPicturePath', '$repairPicturePath')";
if ($conn->query($sqlCar) === TRUE) {
    // ดึง Car_ID ที่เพิ่งเพิ่ม
    $carID = $conn->insert_id;

    // INSERT ข้อมูลลงใน tb_work
    $carRepairDate = date("Y-m-d");
    $carRepairTime = date("H:i:s");
    $approveID = null; // หรือกำหนดค่าตามต้องการ

    $sqlWork = "INSERT INTO tb_work (Car_ID, User_ID, CarRepair_Date, CarRepair_Time)
                VALUES ('$carID', '$userID', '$carRepairDate', '$carRepairTime')";
    if ($conn->query($sqlWork) === TRUE) {
        echo "บันทึกข้อมูลสำเร็จ";
        header("refresh: 1; url= Ad-mainpage.php");
    } else {
        echo "ข้อผิดพลาดในการบันทึกข้อมูล tb_work: " . $conn->error;
    }
} else {
    echo "ข้อผิดพลาดในการบันทึกข้อมูล tb_car: " . $conn->error;
}

$conn->close();
?>
