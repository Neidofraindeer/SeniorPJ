<?php
// เชื่อมต่อกับฐานข้อมูล
include '../conn.php';

// ตรวจสอบว่าได้รับค่าจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status']) && $_POST['status'] === 'return') {
    if (!isset($_POST['Return_ID']) || !isset($_POST['Work_ID'])) {
        die("Error: Missing Return_ID or Work_ID");
    }
    $return_id = $_POST['Return_ID']; // รับค่า Return_ID จากฟอร์ม
    $work_id = $_POST['Work_ID']; // รับค่า Work_ID จากฟอร์ม
    
    date_default_timezone_set('Asia/Bangkok');
    $return_date = new DateTime();
    $formattedDate = $return_date->format('Y-m-d'); 
    $return_time = date('H:i:s'); 

    // สร้างคำสั่ง SQL เพื่อบันทึกข้อมูลในตาราง tb_return
    $sql_insert = "INSERT INTO tb_return (Work_ID, Return_Date, Return_Time) 
                   VALUES ('$work_id', '$formattedDate', '$return_time')";

    if ($conn->query($sql_insert) === TRUE) {
        // ถ้าบันทึกสำเร็จ, ให้เปลี่ยนสถานะการอนุมัติเป็น 'returned'
        $sql_update = "UPDATE tb_approve SET Approve_Status = 'returned' WHERE Approve_ID = '$return_id'";

        if ($conn->query($sql_update) === TRUE) {
            echo "<script>
                    alert('ส่งมอบสำเร็จ');
                    window.location.href = 'Mc-mainpage.php';
                  </script>";
            exit();
        } else {
            echo "Error updating approve status: " . $conn->error;
        }
    } else {
        echo "Error inserting return record: " . $conn->error;
    }
}
?>
