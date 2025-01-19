<?php
// รวมไฟล์เชื่อมต่อฐานข้อมูล
include 'conn.php';

// รับค่า username และ password จากฟอร์ม
$username = $_POST['Username'];
$password = $_POST['Password'];

// เตรียมคำสั่ง SQL สำหรับตรวจสอบข้อมูล
$sql = "SELECT * FROM tb_login WHERE Username = ? AND Password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // หากพบข้อมูลผู้ใช้
    $row = $result->fetch_assoc();
    $roleId = $row['Role_ID'];

    // ตรวจสอบสิทธิ์ตาม Role_ID
    if ($roleId == 0) { // Admin
        // Redirect ไปยังหน้า Admin
        header("Location: Ad-mainpage.php");
    } elseif ($roleId == 1) { // Office
        // Redirect ไปยังหน้า Office
        header("Location: Of-mainpage.php");
    } else { // Mechanic หรืออื่นๆ
        // Redirect ไปยังหน้าอื่นๆ
        header("Location: Mc-mainpage.php");
    }
} else {
    echo "Username หรือ Password ไม่ถูกต้อง";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ล็อกอินเข้าสู่ระบบ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h1>ล็อกอินเข้าสู่ระบบ</h1>
            <form action="index.php" method="POST">
                <div class="form-group">
                    <label for="username">ชื่อผู้ใช้</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">รหัสผ่าน</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">เข้าสู่ระบบ</button>
            </form>
            <p><a href="#">ลืมรหัสผ่าน?</a></p>
        </div>
    </div>
    <script src="script.js"></script> 
</body>
</html>