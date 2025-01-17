<?php
require '../conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['User_Picture'])) {
    // รับค่าจากฟอร์ม
    $firstname = $_POST['User_Firstname'];
    $lastname = $_POST['User_Lastname'];
    $nickname = $_POST['User_Nickname'];
    $phone = $_POST['User_Tel'];
    $department_id = $_POST['Department_ID'];  // Department ID จากฟอร์ม
    $role = $_POST['Role']; // รับค่า Role จากฟอร์ม
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    // ตรวจสอบเบอร์โทรศัพท์
    if (!preg_match("/^\d{10}$/", $phone)) {
        echo "เบอร์โทรต้องประกอบด้วยตัวเลข 10 หลัก";
        exit;
    }

    // ตรวจสอบรหัสผ่าน (ต้องมีอย่างน้อย 8 ตัวอักษร)
    if (strlen($password) < 8) {
        echo "รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร";
        exit;
    }

    // ถ้ารหัสผ่านถูกกรอกมา ให้ทำการเข้ารหัสรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // จัดการอัปโหลดรูปภาพ
    $target_dir = "../uploads/"; // กำหนดโฟลเดอร์ที่จะเก็บไฟล์
    $target_file = $target_dir . basename($_FILES['User_Picture']['name']); // เก็บตำแหน่งไฟล์ที่อัปโหลด
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); // เช็คประเภทไฟล์

    // กำหนดประเภทไฟล์ที่รองรับ
    $valid_extensions = array("jpg", "jpeg", "png", "gif");

    // ตรวจสอบประเภทไฟล์
    if (in_array($imageFileType, $valid_extensions)) {
        // ตรวจสอบการอัปโหลดไฟล์
        if (move_uploaded_file($_FILES['User_Picture']['tmp_name'], $target_file)) {
            // บันทึกข้อมูลใน tb_user
            $sql_user = "INSERT INTO tb_user (User_Firstname, User_Lastname, User_Nickname, User_Tel, User_Picture, Department_ID)
                         VALUES ('$firstname', '$lastname', '$nickname', '$phone', '$target_file', '$department_id')";
            if ($conn->query($sql_user) === TRUE) {
                $user_id = $conn->insert_id; // ดึง User_ID ที่เพิ่งบันทึก

                // บันทึกข้อมูลใน tb_login
                $sql_login = "INSERT INTO tb_login (Username, Password, User_ID, Role)
                              VALUES ('$username', '$hashed_password', '$user_id', '$role')";
                if ($conn->query($sql_login) === TRUE) {
                    echo "บันทึกข้อมูลสำเร็จ!";
                    header("refresh: 1; url= Ad-user.php");
                } else {
                    echo "เกิดข้อผิดพลาดในการบันทึกข้อมูลใน tb_login: " . $conn->error;
                }
            } else {
                echo "เกิดข้อผิดพลาดในการบันทึกข้อมูลใน tb_user: " . $conn->error;
            }
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ";
        }
    } else {
        echo "ประเภทไฟล์รูปภาพไม่ถูกต้อง";
    }
} else {
    echo "ข้อมูลไม่ครบถ้วน";
}

$conn->close();
?>
