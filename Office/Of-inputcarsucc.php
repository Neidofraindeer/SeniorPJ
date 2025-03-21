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

// ดึง Department_ID ของพนักงานที่เลือก
$sqlDept = "SELECT Department_ID FROM tb_user WHERE User_ID = '$userID'";
$resultDept = $conn->query($sqlDept);
$rowDept = $resultDept->fetch_assoc();
$departmentID = $rowDept['Department_ID'];

// กำหนด Status_ID ตาม Department_ID
switch ($departmentID) {
    case 2:
        $statusID = 0;  // เครื่องยนต์
        break;
    case 3:
        $statusID = 1;  // เคาะ
        break;
    case 4:
        $statusID = 2;  // ทำสี
        break;
    case 5:
        $statusID = 3;  // ประกอบ
        break;
    default:
        $statusID = null; // ถ้าไม่ตรงกับใดๆ
        echo "ไม่พบ Department_ID ที่ถูกต้อง";
        exit;
}
// ดึงชื่อของ Status_Car ที่ตรงกับ Status_ID
$sqlStatus = "SELECT Status_Car FROM tb_status WHERE Status_ID = '$statusID'";
$resultStatus = $conn->query($sqlStatus);
$rowStatus = $resultStatus->fetch_assoc();
$statusCar = $rowStatus['Status_Car'];

// อัปโหลดรูปภาพ CarPicture
$carPicture = $_FILES['CarPicture']['name'];
$carPictureTmp = $_FILES['CarPicture']['tmp_name'];
$carPicturePath = "../uploads/car/" . uniqid("car_", true) . ".jpg"; // ใช้ชื่อไฟล์ไม่ซ้ำ
if (!move_uploaded_file($carPictureTmp, $carPicturePath)) {
    echo "ไม่สามารถอัปโหลดไฟล์ภาพรถยนต์ได้";
    exit;
}

$repairPictures = $_FILES['RepairPicture'];
$repairPicturePaths = [];

foreach ($repairPictures['name'] as $key => $name) {
    if ($repairPictures['error'][$key] == 0) {
        $tmp_name = $repairPictures['tmp_name'][$key];
        $newFileName = "../uploads/repair/" . uniqid("repair_", true) . ".jpg";
        if (move_uploaded_file($tmp_name, $newFileName)) {
            $repairPicturePaths[] = $newFileName;
        }
    }
}
// ตรวจสอบว่ามีรูปซ่อมแซมอัปโหลดสำเร็จหรือไม่
if (!isset($_FILES['RepairPicture']) || empty($_FILES['RepairPicture']['name'][0])) {
    echo "ไม่มีไฟล์อัปโหลด";
    exit;
}
// เปลี่ยนเป็น JSON String เพื่อเก็บหลายไฟล์ในฐานข้อมูล
$repairPicturePathsJson = json_encode($repairPicturePaths, JSON_UNESCAPED_SLASHES);


// INSERT ข้อมูลลงใน tb_car
$sqlCar = "INSERT INTO tb_car (CarNumber, CarModel,  CarBrand,  CarColor, CarDetail, CarInsurance,  CarPicture, RepairPicture)
           VALUES ('$carNumber', '$carModel', '$carBrand', '$carColor', '$carDetail', '$carInsurance', '$carPicturePath', '$repairPicturePathsJson')";
if ($conn->query($sqlCar) === TRUE) {
    // ดึง Car_ID ที่เพิ่งเพิ่ม
    $carID = $conn->insert_id;

    date_default_timezone_set('Asia/Bangkok');
    $workDate = new DateTime();
    $formattedDate = $workDate->format('Y-m-d');
    $workTime = date('H:i:s'); 

    $sqlWork = "INSERT INTO tb_work (Car_ID, User_ID, Status_ID, Work_Date, Work_Time)
                VALUES ('$carID', '$userID', '$statusID', '$formattedDate', '$workTime')";

    if ($conn->query($sqlWork) === TRUE) {
        $workID = $conn->insert_id;
        // INSERT ข้อมูลลงใน tb_approve (ตั้งค่า Approve_Status เป็น pending)
        $sqlApprove = "INSERT INTO tb_approve (Work_ID, Approve_Status) 
                       VALUES ('$workID','pending')";
        if ($conn->query($sqlApprove) === TRUE) {
            echo "บันทึกข้อมูลสำเร็จ";
            header("refresh: 1; url= Of-mainpage.php");
        } else {
            echo "ข้อผิดพลาดในการบันทึกข้อมูล tb_approve: " . $conn->error;
        }
    } else {
        echo "ข้อผิดพลาดในการบันทึกข้อมูล tb_work: " . $conn->error;
    }
} else {
    echo "ข้อผิดพลาดในการบันทึกข้อมูล tb_car: " . $conn->error;
}
$conn->close();
?>