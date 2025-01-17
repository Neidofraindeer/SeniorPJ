<?php
include('../conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $approveID = $_POST['Approve_ID'];
    $userID = $_POST['User_ID'];

    // อัปเดตสถานะเป็น 'approved'
    $sql = "UPDATE tb_approve SET Approve_Status = 'approved' WHERE Approve_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $approveID);

    if ($stmt->execute()) {
        // หลังจากอนุมัติแล้ว ให้ redirect กลับไปหน้า Ad_approve.php
        header("Location: Ad_approve.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
