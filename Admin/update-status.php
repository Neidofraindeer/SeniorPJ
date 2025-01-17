<?php
// เชื่อมต่อกับฐานข้อมูล
include '../conn.php';
if (isset($_POST['Approve_ID']) && isset($_POST['User_ID'])) {
    $approve_id = $_POST['Approve_ID'];
    $user_id = $_POST['User_ID'];

    $approve_status = 'approved'; // กำหนดสถานะเป็นอนุมัติ

    $sql = "INSERT INTO tb_approve (User_ID, Approve_Status) VALUES ('$user_id', '$approve_status') 
            ON DUPLICATE KEY UPDATE Approve_Status = '$approve_status'";
    if ($conn->query($sql) === TRUE) {
        echo "บันทึกข้อมูลการอนุมัติสำเร็จ";
        header("refresh: 1; url= Ad-mainpage.php");
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
} else {
    echo "ข้อมูลไม่ครบถ้วน: กรุณาส่งทั้ง Approve_ID และ User_ID";
}
$conn->close();
?>
