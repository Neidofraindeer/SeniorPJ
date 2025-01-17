<?php
require '../conn.php'; // เชื่อมต่อกับฐานข้อมูล
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id']; // รับค่า User_ID จาก URL
} else {
    echo "ไม่พบ User_ID ใน URL";
    exit; // หยุดการทำงานเมื่อไม่พบค่า
}

// ตรวจสอบว่ามีข้อมูลจากฟอร์มหรือไม่
if (isset($_POST['User_Firstname'], $_POST['User_Lastname'], $_POST['User_Tel'], $_POST['Department_ID'], $_POST['Username'], $_POST['Role'])) {
    // ดึงค่าจากฟอร์ม
    $firstname = $_POST['User_Firstname'];
    $lastname = $_POST['User_Lastname'];
    $nickname = $_POST['User_Nickname'];
    $tel = $_POST['User_Tel'];
    $department_id = $_POST['Department_ID'];
    $username = $_POST['Username'];
    $role = $_POST['Role'];
    
    // ตรวจสอบเบอร์โทร
    if (!preg_match("/^\d{10}$/", $tel)) {
        echo "เบอร์โทรต้องประกอบด้วยตัวเลข 10 หลัก";
        exit;
    }

    // เริ่มสร้างคำสั่ง SQL สำหรับการอัพเดท
    $sql = "UPDATE tb_user SET User_Firstname = ?, User_Lastname = ?, User_Nickname = ?, User_Tel = ?, Department_ID = ? WHERE User_ID = ?";
    $params = [$firstname, $lastname, $nickname, $tel, $department_id, $user_id];

    // ตรวจสอบว่ามีการอัพโหลดรูปภาพใหม่หรือไม่
    if (isset($_FILES['User_Picture']) && $_FILES['User_Picture']['error'] == 0) {
        $upload_dir = '../uploads/';
        $photo_name = $_FILES['User_Picture']['name'];
        $photo_tmp = $_FILES['User_Picture']['tmp_name'];
        $photo_path = $upload_dir . basename($photo_name);

        if (move_uploaded_file($photo_tmp, $photo_path)) {
            // อัพเดทรูปภาพในฐานข้อมูล
            $sql = "UPDATE tb_user SET User_Firstname = ?, User_Lastname = ?, User_Nickname = ?, User_Tel = ?, Department_ID = ?, User_Picture = ? WHERE User_ID = ?";
            $params[] = $photo_name; // เพิ่มชื่อไฟล์รูปภาพ
        } else {
            echo "ไม่สามารถอัพโหลดรูปภาพได้";
            exit;
        }
    }

    // เตรียมคำสั่ง SQL สำหรับการอัพเดทข้อมูลใน tb_user
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params); // เรียงลำดับพารามิเตอร์ตามที่ต้องการ

    // อัพเดทข้อมูลใน tb_user
    if ($stmt->execute()) {
        // อัพเดทข้อมูลใน tb_login (ถ้ามีการเปลี่ยนแปลง Username หรือ Role)
        $login_sql = "UPDATE tb_login SET Username = ?, Role = ? WHERE User_ID = ?";
        $login_stmt = $conn->prepare($login_sql);
        $login_stmt->bind_param("sii", $username, $role, $user_id);

        if ($login_stmt->execute()) {
            echo "ข้อมูลผู้ใช้งานได้ถูกอัพเดทเรียบร้อยแล้ว";
            header("refresh: 1; url= Ad-user.php");
        } else {
            echo "เกิดข้อผิดพลาดในการอัพเดทข้อมูลใน tb_login";
        }
    } else {
        echo "เกิดข้อผิดพลาดในการอัพเดทข้อมูลผู้ใช้";
    }
} else {
    echo "ข้อมูลที่ส่งมาจากฟอร์มไม่ครบถ้วน";
}
?>
