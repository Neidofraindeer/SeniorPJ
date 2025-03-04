<?php
session_start();

// เช็คว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_data']) || !isset($_SESSION['user_data']['user_id'])) {
    header("Location: /SeniorPJ/index.php"); // กลับไปหน้าเข้าสู่ระบบ
    exit();
}
// ดึงข้อมูลผู้ใช้ที่เข้าสู่ระบบจาก session
$user_id = $_SESSION['user_data']['user_id']; // ดึง User_ID จาก session
// เชื่อมต่อกับฐานข้อมูล
require '../conn.php';
if ($conn) {
    // ดึงข้อมูลจาก tb_login สำหรับชื่อผู้ใช้และรหัสผ่าน
    $login_sql = "SELECT * FROM tb_login WHERE User_ID = ?";
    $login_stmt = $conn->prepare($login_sql);
    $login_stmt->bind_param("i", $user_id); // กำหนดประเภทของตัวแปรเป็น integer
    $login_stmt->execute();
    $login_result = $login_stmt->get_result();
    
    if ($login_result->num_rows > 0) {
        $login_data = $login_result->fetch_assoc(); // ดึงข้อมูลจาก tb_login
    } else {
        echo "ไม่พบข้อมูลผู้ใช้ใน tb_login";
    }

} else {
    echo "ไม่สามารถเชื่อมต่อฐานข้อมูล";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เปลี่ยนรหัสผ่าน</title>
</head>
<style>
    /* กำหนดรูปแบบพื้นฐาน */
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f9f9f9;
    }

    /* กรอบฟอร์ม */
    .form-container {
        max-width: 900px;
        height: 515px;
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
    <form method="post" action="Of-repasswordsucc.php" enctype="multipart/form-data">
        <div class="form-title">
            <a onclick="document.location='Of-mainpage.php'" class="back-link">&lt; </a>
            <a class="head"> เปลี่ยนรหัสผ่าน</a>
        </div>
        <div class="form-container">
        <div class="form-group">
                <label for="username">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="Username" value="<?= $login_data['Username']; ?>" required readonly style="background-color: #f0f0f0;">
            </div>
            <div class="form-group">
                <label for="old_password">รหัสผ่านเดิม:</label>
                <input type="password" id="old_password" name="old_password" minlength="8" required>
            </div>
            <div class="form-group">
                <label for="new_password">รหัสผ่านใหม่:</label>
                <input type="password" id="new_password" name="new_password" minlength="8" required >
            </div>
            <div class="form-group">
                <label for="confirm_password">ยืนยันรหัสผ่านใหม่:</label>
                <input type="password" id="confirm_password" name="confirm_password" minlength="8" required >
            </div>
            <div id="message" style="margin-top: 10px;">
                <?php 
                    if (isset($_SESSION['error_message'])) {
                        echo '<span style="color: red;">' . $_SESSION['error_message'] . '</span>';
                        unset($_SESSION['error_message']); // เคลียร์ข้อความหลังแสดงผล
                    } elseif (isset($_SESSION['success_message'])) {
                        echo '<span style="color: green;">' . $_SESSION['success_message'] . '</span>';
                        unset($_SESSION['success_message']); // เคลียร์ข้อความหลังแสดงผล
                    }
                ?>
            </div>
            <div class="form-actions"><br>
                <button type="submit" class="btn-save">บันทึก</button>
            </div>
        </div>
    </form>
</body>
</html>
