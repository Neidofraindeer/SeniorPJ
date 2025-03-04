<?php
require '../conn.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีค่า id ที่ส่งมาหรือไม่
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $work_id = $_GET['id']; 

    // ตรวจสอบการเชื่อมต่อฐานข้อมูล
    if ($conn) {
        // ดึง Car_ID ก่อนลบข้อมูล
        $query = "SELECT Car_ID FROM tb_work WHERE Work_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $work_id);
        $stmt->execute();
        $stmt->bind_result($car_id);
        $stmt->fetch();
        $stmt->close();

        // ถ้ามี Car_ID ให้ลบข้อมูล
        if ($car_id) {
            // ลบจาก tb_work
            $sql = "DELETE FROM tb_work WHERE Work_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $work_id);
            
            if ($stmt->execute()) {
                // ลบจาก tb_car
                $car_sql = "DELETE FROM tb_car WHERE Car_ID = ?";
                $car_stmt = $conn->prepare($car_sql);
                $car_stmt->bind_param("i", $car_id);

                if ($car_stmt->execute()) {
                    // ถ้าลบสำเร็จให้ redirect ไปหน้า Ad-mainpage.php
                    header("Location: Of-mainpage.php");
                    exit();
                } else {
                    echo "เกิดข้อผิดพลาดในการลบข้อมูลจาก tb_car";
                }
            } else {
                echo "เกิดข้อผิดพลาดในการลบข้อมูลจาก tb_work";
            }
        } else {
            echo "ไม่พบข้อมูล Car_ID ที่เชื่อมโยงกับ Work_ID นี้";
        }
    } else {
        echo "ไม่สามารถเชื่อมต่อฐานข้อมูล";
    }
} else {
    echo "ไม่พบ Work_ID ใน URL";
}
?>
