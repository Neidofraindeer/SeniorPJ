<?php
include('../conn.php');

// รับค่าจากฟอร์ม
$approveID = $_POST['Approve_ID'];
$userID = $_POST['User_ID'];

// ตรวจสอบว่ามีการส่งข้อมูลมาอย่างถูกต้องหรือไม่
if (isset($approveID) && isset($userID)) {
    // อัพเดตสถานะการอนุมัติในตาราง tb_approve
    $sql = "UPDATE tb_approve SET Approve_Status = 'approved' WHERE Approve_ID = ? AND User_ID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $approveID, $userID);  // i = integer
    
    if ($stmt->execute()) {
        // ถ้าการอัพเดตสำเร็จ ก็จะไปที่หน้ารายการอนุมัติอีกครั้ง
        header("Location: Ad-approve.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    echo "ข้อมูลไม่ครบถ้วน";
}

$conn->close();
?>
