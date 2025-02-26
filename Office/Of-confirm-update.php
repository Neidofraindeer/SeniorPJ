<?php
include '../conn.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $work_id = $_POST['Work_ID'];

    // ตรวจสอบสถานะปัจจุบัน
    $check_sql = "SELECT Approve_Status FROM tb_approve WHERE Work_ID = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $work_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if ($row['Approve_Status'] == 'confirm') {
            echo "<script>alert('รายการนี้ได้รับการยืนยันแล้ว!'); window.location='Of-mainpage.php';</script>";
            exit();
        }

        // อัปเดตสถานะเป็น confirm
        $update_sql = "UPDATE tb_approve SET Approve_Status = 'confirm' WHERE Work_ID = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $work_id);

        if ($stmt->execute()) {
            echo "<script>alert('ยืนยันสำเร็จ!'); window.location='Of-mainpage.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาด!'); history.back();</script>";
        }
    } else {
        echo "<script>alert('ไม่พบข้อมูลงานนี้!'); history.back();</script>";
    }
}
?>
