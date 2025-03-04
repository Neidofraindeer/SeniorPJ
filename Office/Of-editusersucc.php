<?php
require '../conn.php'; // เชื่อมต่อกับฐานข้อมูล

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id']; // รับค่า User_ID จาก URL
} else {
    echo "ไม่พบ User_ID ใน URL";
    exit;
}

// ตรวจสอบว่ามีข้อมูลจากฟอร์มหรือไม่
if (isset($_POST['User_Firstname'], $_POST['User_Lastname'], $_POST['User_Tel'], $_POST['Department_ID'], $_POST['Username'], $_POST['Role_ID'])) {
    // รับค่าจากฟอร์ม
    $firstname = $_POST['User_Firstname'];
    $lastname = $_POST['User_Lastname'];
    $nickname = $_POST['User_Nickname'];
    $tel = $_POST['User_Tel'];
    $department_id = $_POST['Department_ID'];
    $username = $_POST['Username'];
    $role = $_POST['Role_ID'];

    // ตรวจสอบเบอร์โทร
    if (!preg_match("/^\d{10}$/", $tel)) {
        echo "เบอร์โทรต้องประกอบด้วยตัวเลข 10 หลัก";
        exit;
    }

    // เช็คว่ามีการอัปโหลดไฟล์ใหม่หรือไม่
    $has_new_image = isset($_FILES['User_Picture']) && $_FILES['User_Picture']['error'] == 0;
    
    // หากมีการอัปโหลดรูปใหม่
    if ($has_new_image) {
        $upload_dir = '../uploads/';
        $photo_name = basename($_FILES['User_Picture']['name']);
        $photo_tmp = $_FILES['User_Picture']['tmp_name'];
        $photo_path = $upload_dir . $photo_name;

        if (!move_uploaded_file($photo_tmp, $photo_path)) {
            echo "ไม่สามารถอัปโหลดรูปภาพได้";
            exit;
        }
    }

    // เตรียม SQL อัปเดตข้อมูล
    if ($has_new_image) {
        $sql = "UPDATE tb_user SET User_Firstname = ?, User_Lastname = ?, User_Nickname = ?, User_Tel = ?, Department_ID = ?, User_Picture = ? WHERE User_ID = ?";
        $params = [$firstname, $lastname, $nickname, $tel, $department_id, $photo_name, $user_id];
        $types = "ssssisi";
    } else {
        $sql = "UPDATE tb_user SET User_Firstname = ?, User_Lastname = ?, User_Nickname = ?, User_Tel = ?, Department_ID = ? WHERE User_ID = ?";
        $params = [$firstname, $lastname, $nickname, $tel, $department_id, $user_id];
        $types = "ssssis";
    }

    // เตรียมคำสั่ง SQL และ Bind Parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    // ดำเนินการอัปเดตข้อมูล
    if ($stmt->execute()) {
        // อัปเดตข้อมูลใน tb_login (ถ้ามีการเปลี่ยนแปลง Username หรือ Role)
        $login_sql = "UPDATE tb_login SET Username = ?, Role_ID = ? WHERE User_ID = ?";
        $login_stmt = $conn->prepare($login_sql);
        $login_stmt->bind_param("sii", $username, $role, $user_id);

        if ($login_stmt->execute()) {
            echo "ข้อมูลผู้ใช้งานได้ถูกอัปเดตเรียบร้อยแล้ว";
            header("refresh: 1; url= Of-user.php");
        } else {
            echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูลใน tb_login";
        }
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูลผู้ใช้";
    }
} else {
    echo "ข้อมูลที่ส่งมาจากฟอร์มไม่ครบถ้วน";
}
?>
