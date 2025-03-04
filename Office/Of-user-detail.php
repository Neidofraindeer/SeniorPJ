<?php
session_start();

// เช็คว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_data'])) {
    header("Location: /SeniorPJ/index.php"); // กลับไปหน้าเข้าสู่ระบบ
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลผู้ใช้งาน</title>
</head>
<style>
    /* กำหนดรูปแบบพื้นฐาน */
    body {
        font-family: Arial, sans-serif;
        margin: 50px;
        background-color: #f9f9f9;
    }

    /* กรอบฟอร์ม */
    .form-container {
        max-width: 900px;
        height: 550;
        margin: auto;
        border: 1px solid #ccc;
        padding: 20px;
        border-radius: 8px;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* กรอปหัวข้อฟอร์ม */
    .form-title{
        width: 100%;
        height: 35px;
        padding: 10px;
    }
    .form-title .head {
        color: #835EB7; 
        font-size: 24px;
    }

    /* < กลับ */
    .form-title .back-link{
        color: #90879c;
        font-size: 24px;
        font-style: oblique;
    }

    /* กลุ่มฟอร์ม */
    .form-group {
        display: flex; 
        align-items: center; 
        margin-bottom: 15px;
    }

    .form-group label {
        width: 25%; /* กำหนดความกว้างของ label */
        margin-right: 10px; /* เพิ่มช่องว่างระหว่าง label และ input */
        text-align: left; /* จัดข้อความให้ชิดขวา */
    }

    .form-group input,
    .form-group select {
        flex: 1; /* ให้ input/select ขยายเต็มพื้นที่ที่เหลือ */
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    /* ปุ่มฟอร์ม */
    .form-actions {
        text-align: right;
        margin-top: 20px;
    }

    .form-actions button {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    /* ปุ่มบันทึก */
    .form-actions .btn-save {
        background-color: #28a745;
        color: white;
    }

    /* ปุ่มยกเลิก */
    .form-actions .btn-cancel {
        background-color: #dc3545;
        color: white;
        margin-left: 10px;
    }

    /* การเพิ่มเอฟเฟกต์ */
    .form-actions button:hover {
        opacity: 0.9;
    }
</style>
<body>
    <?php
    require '../conn.php'; // เชื่อมต่อกับฐานข้อมูล
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $user_id = $_GET['id'];
        // ตรวจสอบการเชื่อมต่อฐานข้อมูล
        if ($conn) {
            // ดึงข้อมูลผู้ใช้จากตาราง tb_user
            $sql = "SELECT * FROM tb_user WHERE User_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id); // กำหนดประเภทของตัวแปรเป็น integer
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
            } else {
                echo "ไม่พบข้อมูลผู้ใช้";
            }
            // ดึงชื่อแผนก
            $dept_sql = "SELECT Department_Name FROM tb_department WHERE Department_ID = ?";
            $stmt = $conn->prepare($dept_sql);
            $stmt->bind_param("i", $user['Department_ID']);
            $stmt->execute();
            $dept_result = $stmt->get_result();
            $dept_row = $dept_result->fetch_assoc();
            $department_name = $dept_row ? $dept_row['Department_Name'] : "ไม่ระบุ";

            // ดึงชื่อสิทธิ์เข้าถึง
            $role_sql = "SELECT Role FROM tb_role WHERE Role_ID = ?";
            $stmt = $conn->prepare($role_sql);
            $stmt->bind_param("i", $user['Role_ID']);
            $stmt->execute();
            $role_result = $stmt->get_result();
            $role_row = $role_result->fetch_assoc();
            $role_name = $role_row ? $role_row['Role'] : "ไม่ระบุ"; 
            // ดึงข้อมูลจาก tb_login สำหรับชื่อผู้ใช้และรหัสผ่าน
            $login_sql = "SELECT * FROM tb_login WHERE User_ID = ?";
            $login_stmt = $conn->prepare($login_sql);
            $login_stmt->bind_param("i", $user_id );
            $login_stmt->execute();
            $login_result = $login_stmt->get_result();
            if ($login_result->num_rows > 0) {
                $login_data = $login_result->fetch_assoc();
            } else {
                echo "ไม่พบข้อมูลผู้ใช้ใน tb_login";
            }
        } else {
            echo "ไม่สามารถเชื่อมต่อฐานข้อมูล";
        }
    } else {
        echo "ไม่มีข้อมูล User_ID";
    }
    ?>
    <div class="form-container">
            <div class="form-title">
                <a onclick="document.location='Of-user.php'" class="back-link">&lt; </a>
                <a class="head"> ข้อมูลผู้ใช้งาน</a>
            </div><br><br>
            <div class="form-group">
                <label for="photo">รูป:</label>
                <img src="../uploads/<?= $user['User_Picture']; ?> " alt="User Picture" width="100px">
            </div><br>
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้:</label>
                <?= $login_data['Username']; ?>
            </div><br>
            <div class="form-group">
                <label for="first_name">ชื่อ:</label>
                <?= $user['User_Firstname']; ?>
            </div><br>
            <div class="form-group">
                <label for="last_name">นามสกุล:</label>
                <?= $user['User_Lastname']; ?>
            </div><br>
            <div class="form-group">
                <label for="nickname">ชื่อเล่น:</label>
                <?= $user['User_Nickname']; ?>
            </div><br>
            <div class="form-group">
                <label for="phone">เบอร์โทร:</label>
                <?= $user['User_Tel']; ?>
            </div><br>
            <div class="form-group">
                <label for="department">แผนก:</label>
                <?= isset($dept_row['Department_Name']) ? $dept_row['Department_Name'] : "ไม่ระบุ"; ?>
            </div>
        </div>
            
        </form>
    </div>
</body>
</html>
