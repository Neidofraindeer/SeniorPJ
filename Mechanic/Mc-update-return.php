<?php
// เชื่อมต่อกับฐานข้อมูล
include '../conn.php';

// ตรวจสอบว่าได้รับค่าจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status']) && $_POST['status'] === 'return') {
    if (!isset($_POST['Return_ID']) || !isset($_POST['Work_ID'])) {
        die("Error: Missing Work_ID");
    }

    $work_id = $_POST['Work_ID']; // รับค่า Work_ID จากฟอร์ม
    
    date_default_timezone_set('Asia/Bangkok');
    $return_date = new DateTime();
    $formattedDate = $return_date->format('Y-m-d'); 
    $return_time = date('H:i:s'); 

      // ตรวจสอบว่า Work_ID มีอยู่ใน tb_return แล้วหรือยัง
    $check_sql = "SELECT * FROM tb_return WHERE Work_ID = '$work_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows == 0) {
        // ถ้า Work_ID ยังไม่มีใน tb_return ให้ทำการ INSERT
        $sql_insert = "INSERT INTO tb_return (Work_ID, Return_Date, Return_Time) 
                       VALUES ('$work_id', '$formattedDate', '$return_time')";

    if ($conn->query($sql_insert) === TRUE) {
        // อัปเดตสถานะใน tb_approve เป็น 'returned'
        $sql_update = "UPDATE tb_approve SET Approve_Status = 'returned' WHERE Approve_ID = '$work_id'";

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
    } else {
        // ถ้ามี Work_ID อยู่แล้ว ไม่ต้องบันทึกซ้ำ
        echo "<script>
                alert('ส่งมอบเรียบร้อยแล้ว');
                window.location.href = 'Mc-mainpage.php';
            </script>";
        exit();
    }
}
?>
