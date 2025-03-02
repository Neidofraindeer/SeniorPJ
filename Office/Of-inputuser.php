
<?php
session_start();

// เช็คว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_data'])) {
    header("Location: /SeniorPJ/index.php"); // กลับไปหน้าเข้าสู่ระบบ
    exit();
}
?><!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลผู้ใช้งาน</title>
</head>
<style>
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
    /* หัวข้อฟอร์ม */
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
    require '../conn.php';
    $departments = $conn->query("SELECT * FROM tb_department");
    $role = $conn->query("SELECT * FROM tb_role");
    ?>


<form method="post" action="Of-inputusersucc.php" enctype="multipart/form-data">
    <div class="form-title">
        <a onclick="document.location='Of-user.php'" class="back-link">&lt; </a>
        <a class="head"> เพิ่มข้อมูลผู้ใช้งาน</a>
    </div>
    <div class="form-container">
            <div class="form-group">
                <label for="photo">รูป:</label>
                <input type="file" id="photo" name="User_Picture"  required>
            </div>
            <div class="form-group">
                <label for="first_name">ชื่อ:</label>
                <input type="text" id="first_name" name="User_Firstname" required>
            </div>
            <div class="form-group">
                <label for="last_name">นามสกุล:</label>
                <input type="text" id="last_name" name="User_Lastname" required>
            </div>
            <div class="form-group">
                <label for="nickname">ชื่อเล่น:</label>
                <input type="text" id="nickname" name="User_Nickname">
            </div>
            <div class="form-group">
                <label for="phone">เบอร์โทร:</label>
                <input type="text" id="phone" name="User_Tel" required pattern="^\d{10}$">
            </div>
            <div class="form-group">
                <label for="department">แผนก:</label>
                <select id="department" name="Department_ID" required>
                    <option value="">-- เลือกแผนก --</option>
                    <?php while ($row = $departments->fetch_assoc()): ?>
                        <option value="<?= $row['Department_ID'] ?>"><?= $row['Department_Name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="Username" required>
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน:</label>
                <input type="password" id="password" name="Password" minlength="8"  required>
            </div>
            <div class="form-group">
                <label for="role">สิทธิ์เข้าถึง:</label>
                <select id="role" name="Role_ID" required>
                    <option value="">-- เลือกสิทธิ์เข้าถึง --</option>
                    <?php while ($row = $role->fetch_assoc()): ?>
                        <option value="<?= $row['Role_ID'] ?>"><?= $row['Role'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-actions"><br>
                <button type="submit" class="btn-save">บันทึก</button>
                <button type="reset" class="btn-cancel">ยกเลิก</button>
            </div>
        </form>
    </div>
</body>
</html>