<?php
include('../conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $workID = $_POST['Work_ID'];

    // ตรวจสอบว่ามี Work_ID ถูกส่งมาหรือไม่
    if (!empty($workID)) {
        // อัปเดตสถานะในฐานข้อมูล
        $sql = "UPDATE tb_approve 
                SET Approve_Status = 'approved' 
                WHERE Approve_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $workID);

        if ($stmt->execute()) {
            // หากอัปเดตสำเร็จ ให้กลับไปยังหน้า Ad-mainpage.php
            header("Location: Ad-mainpage.php");
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการอัปเดตสถานะ: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "ไม่มี Work_ID ที่ส่งมาสำหรับอัปเดต!";
    }
}

$conn->close();
?>