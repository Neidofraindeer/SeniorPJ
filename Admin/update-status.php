<?php
include('../conn.php');

// รับค่าจากฟอร์ม
$workID = $_POST['Work_ID'];
$approve_id = $_POST['Approve_ID'];
$status = $_POST['status'];

if ($status == 'approve') {
    // อัปเดตสถานะใน tb_approve
    $sql_approve = "UPDATE tb_approve SET Approve_Status = 'approved' WHERE Approve_ID = ?";
    $stmt = $conn->prepare($sql_approve);
    $stmt->bind_param("i", $approve_id);
    $stmt->execute();

    header("Location: Ad-approve.php");
    exit();
}
?>