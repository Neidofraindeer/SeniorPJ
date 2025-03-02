<?php
// เริ่มต้น session ถ้ายังไม่ได้เริ่มต้น
if (session_status() == PHP_SESSION_NONE) {
}

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost', 'root', '', 'project');
$conn->query("SET NAMES utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีการส่งค่า 'username' และ 'password' มาหรือไม่
    if (isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // ป้องกัน SQL Injection
        $query = $conn->prepare("SELECT * FROM tb_login WHERE username = ?");
        $query->bind_param("s", $username);  // ตรวจสอบแค่ชื่อผู้ใช้
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // ตรวจสอบรหัสผ่านที่ถูกเข้ารหัส
            if (password_verify($password, $user['password'])) {
                // ตั้งค่าข้อมูล session
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // ตรวจสอบสถานะการล็อกอินของผู้ใช้ใน tb_user
                $userId = $user['User_ID'];  // สมมติว่ามีฟิลด์ User_ID
                $checkLoginSql = "SELECT Logged_in FROM tb_user WHERE User_ID = ?";
                $checkLoginStmt = $conn->prepare($checkLoginSql);
                $checkLoginStmt->bind_param("i", $userId);
                $checkLoginStmt->execute();
                $checkLoginResult = $checkLoginStmt->get_result();

                if ($checkLoginResult->num_rows > 0) {
                    $loginStatus = $checkLoginResult->fetch_assoc();

                    if ($loginStatus['Logged_in'] == 1) {
                        // หากผู้ใช้ล็อกอินอยู่แล้วในที่อื่น
                        echo "❌ คุณได้เข้าสู่ระบบแล้วในที่อื่น กรุณาล็อกเอาต์จากที่อื่นก่อน";
                    } else {
                        // ถ้าไม่ล็อกอินอยู่ รีเซ็ต session id และอัปเดตสถานะการล็อกอินในฐานข้อมูล
                        session_regenerate_id(true);

                        // อัปเดตสถานะการล็อกอินใน tb_user
                        $updateLoginStatus = "UPDATE tb_user SET Logged_in = 1 WHERE User_ID = ?";
                        $updateStmt = $conn->prepare($updateLoginStatus);
                        $updateStmt->bind_param("i", $userId);
                        $updateStmt->execute();

                        // เก็บข้อมูล session
                        $_SESSION['user_data'] = [
                            'user_id' => $user['User_ID'],
                            'role' => $user['role'],
                            'fullname' => $user['fullname']
                        ];

                        // ดึงข้อมูลเพิ่มเติมจาก tb_user (ชื่อและรูปภาพ)
                        $userSql = "SELECT User_Firstname, User_Lastname, User_Picture FROM tb_user WHERE User_ID = ?";
                        $userStmt = $conn->prepare($userSql);
                        $userStmt->bind_param("i", $_SESSION['user_data']['user_id']);
                        $userStmt->execute();
                        $userResult = $userStmt->get_result();

                        if ($userResult->num_rows > 0) {
                            $userDetails = $userResult->fetch_assoc();
                            $_SESSION['user_data']['fullname'] = $userDetails['User_Firstname'] . ' ' . $userDetails['User_Lastname'];
                            $_SESSION['user_data']['user_picture'] = $userDetails['User_Picture'];
                        }

                        // Redirect ไปยังหน้า dashboard ตาม Role ของผู้ใช้
                        if ($_SESSION['user_data']['role'] == 0) {
                            header("Location: Admin/Ad-mainpage.php");
                        } elseif ($_SESSION['user_data']['role'] == 1) {
                            header("Location: Office/Of-mainpage.php");
                        } else {
                            header("Location: Mechanic/Mc-mainpage.php");
                        }
                        exit(); // หยุดการทำงานหลังจาก redirect
                    }
                }
            } 
        } 
    } 
}

?>
