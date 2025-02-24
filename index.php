<?php
session_start();
include 'conn.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ดึงข้อมูลเฉพาะ Username จากฐานข้อมูล
    $sql = "SELECT * FROM tb_login WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // พบข้อมูลผู้ใช้
        $row = $result->fetch_assoc();
        $hashed_password = $row['Password']; // รหัสผ่านที่เข้ารหัสใน DB
        $roleId = $row['Role_ID'];

        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $roleId;

            // เปลี่ยนเส้นทางตาม Role_ID
            if ($roleId == 0) { // Admin
                header("Location: Admin/Ad-mainpage.php");
            } elseif ($roleId == 1) { // Office
                header("Location: Office/Of-mainpage.php");
            } else { // Mechanic หรืออื่นๆ
                header("Location: Mechanic/Mc-mainpage.php");
            }
            exit();
        } else {
            $error = "❌ Username หรือ Password ไม่ถูกต้อง";
        }
    } else {
        $error = "❌ Username หรือ Password ไม่ถูกต้อง";
    }

    $stmt->close();
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

            <!-- 🔥 เพิ่ม div สำหรับแจ้งเตือน -->
            <?php if (!empty($error)): ?>
                <div class="alert" id="alert-box">
                    <p><?php echo $error; ?></p>
                </div>    
                <script>
                    setTimeout(function() {
                        document.getElementById("alert-box").style.display = "none";
                    }, 3000); // ซ่อนข้อความอัตโนมัติใน 3 วินาที
                </script>
            <?php endif; ?>

            <form action="index.php" method="POST">
                <div class="form-group">
                    <label for="username">ชื่อผู้ใช้</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">รหัสผ่าน</label>
                    <input type="password" id="password" name="password" minlength="8" required>
                </div>
                <button type="submit">เข้าสู่ระบบ</button>
            </form>
            <p><a href="#">ลืมรหัสผ่าน?</a></p>
        </div>
    </div>
    <script src="script.js"></script> 
</body>
</html>