<?php
include '../conn.php';
// รับค่าจากฟอร์ม
$CarNumber = $_POST['CarNumber'];
$CarBrand = $_POST['CarBrand'];
$CarModel = $_POST['CarModel'];
$CarColor = $_POST['CarColor'];
$CarDetail = $_POST['CarDetail'];
$CarInsurance = $_POST['CarInsurance'];
$User_ID = $_POST['User_ID']; // พนักงานช่างที่ถูกเลือก

// ตรวจสอบว่า User_ID ถูกส่งมาหรือไม่
if (isset($User_ID) && !empty($User_ID)) {
    // อัพโหลดรูปภาพรถ
    $CarPicture = $_FILES['CarPicture']['name'];
    $CarPicture_tmp = $_FILES['CarPicture']['tmp_name'];
    $CarPicture_path = "../uploads/" . basename($CarPicture);
    move_uploaded_file($CarPicture_tmp, $CarPicture_path);

    // อัพโหลดรูปตำแหน่งซ่อมแซม
    $RepairPicture = $_FILES['RepairPicture']['name'];
    $RepairPicture_tmp = $_FILES['RepairPicture']['tmp_name'];
    $RepairPicture_path = "../uploads/" . basename($RepairPicture);
    move_uploaded_file($RepairPicture_tmp, $RepairPicture_path);

    // บันทึกข้อมูลลงในตาราง tb_car
    $sql_car = "INSERT INTO tb_car (CarPicture, CarNumber, CarBrand, CarModel, CarColor, CarInsurance, RepairPicture, CarDetail, User_ID) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_car = $conn->prepare($sql_car);
    $stmt_car->bind_param("ssssssssi", $CarPicture, $CarNumber, $CarBrand, $CarModel, $CarColor, $CarInsurance, $RepairPicture, $CarDetail, $User_ID);

    if ($stmt_car->execute()) {
        $Car_ID = $stmt_car->insert_id; // ดึง Car_ID ที่ถูกสร้างขึ้นจากการ insert

        // เพิ่มข้อมูลการซ่อมลงในตาราง tb_work
        $CarRepair_Date = date('Y-m-d');  // วันที่ปัจจุบัน
        $CarRepair_Time = date('H:i:s');  // เวลาปัจจุบัน

        $sql_work = "INSERT INTO tb_work (CarRepair_ID, Car_ID, User_ID, CarRepair_Date, CarRepair_Time) 
                     VALUES (NULL, ?, ?, ?, ?)";
        $stmt_work = $conn->prepare($sql_work);
        $stmt_work->bind_param("iiss", $Car_ID, $User_ID, $CarRepair_Date, $CarRepair_Time);

        if ($stmt_work->execute()) {
            echo "บันทึกข้อมูลเรียบร้อยแล้ว";
            header("Location: Of-mainpage.php");
            exit;
        } else {
            echo "เกิดข้อผิดพลาดในการบันทึกข้อมูลการซ่อม: " . $conn->error;
        }

    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }

    $stmt_car->close();
    $stmt_work->close();
}
$conn->close();
?>
