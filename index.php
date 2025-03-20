<?php 
    session_start();
    include 'conn.php'; // เชื่อมต่อฐานข้อมูล

    $error = "";

    // ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['username']) && !empty($_POST['password'])) { 
            $username = $_POST['username'];
            $password = $_POST['password'];


            $sql = "SELECT * FROM tb_login WHERE Username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // ✅ ใช้ password_verify() เพื่อตรวจสอบรหัสผ่าน
                if (password_verify($password, $row['Password'])) {

                // ตรวจสอบว่า user นี้ล็อกอินอยู่หรือไม่
                $userId = $row['User_ID'];
                $checkLoginSql = "SELECT Logged_in FROM tb_user WHERE User_ID = ?";
                $checkLoginStmt = $conn->prepare($checkLoginSql);
                $checkLoginStmt->bind_param("i", $userId);
                $checkLoginStmt->execute();
                $checkLoginResult = $checkLoginStmt->get_result();
                
                if ($checkLoginResult->num_rows > 0) {
                    $loginStatus = $checkLoginResult->fetch_assoc();
                    
                    if ($loginStatus['Logged_in'] == 1) {
                        // ถ้าผู้ใช้ล็อกอินอยู่แล้ว
                        $error = "❌ คุณได้เข้าสู่ระบบแล้วในที่อื่น กรุณาล็อกเอาต์จากที่อื่นก่อน";
                    } else {
                        // รีเซ็ต session id ใหม่เพื่อป้องกันการทับซ้อน
                        session_regenerate_id(true);
                        
                        // อัปเดตสถานะการล็อกอินในฐานข้อมูล
                        $updateLoginStatus = "UPDATE tb_user SET Logged_in = 1 WHERE User_ID = ?";
                        $updateStmt = $conn->prepare($updateLoginStatus);
                        $updateStmt->bind_param("i", $userId);
                        $updateStmt->execute();

                        // เก็บข้อมูล session
                        $_SESSION['user_data'] = [
                            'user_id' => $row['User_ID'],
                            'role' => $row['Role_ID'],
                        ];

                        // ดึงข้อมูลเพิ่มเติมจาก tb_user
                        $userSql = "SELECT User_Firstname, User_Lastname, User_Picture FROM tb_user WHERE User_ID = ?";
                        $userStmt = $conn->prepare($userSql);
                        $userStmt->bind_param("i", $_SESSION['user_data']['user_id']);
                        $userStmt->execute();
                        $userResult = $userStmt->get_result();

                        if ($userResult->num_rows > 0) {
                            $userDetails = $userResult->fetch_assoc();
                            $_SESSION['user_data']['fullname'] = $userDetails['User_Firstname'] . ' ' . $userDetails['User_Lastname'];
                            $_SESSION['user_data']['profile_picture'] = $userDetails['User_Picture'];
                            
                        } else {
                            $_SESSION['user_data']['fullname'] = "ไม่พบข้อมูลผู้ใช้";
                            $_SESSION['user_data']['profile_picture'] = "default.jpg";
                        }

                        echo "<script>
                                localStorage.setItem('user_logged_in', 'true');
                                localStorage.setItem('user_role', '" . $_SESSION['user_data']['role'] . "');
                                alert('เข้าสู่ระบบสำเร็จ'); // แสดงข้อความเมื่อเข้าสู่ระบบสำเร็จ
                                window.location.href = getRedirectUrl(); // เปลี่ยนหน้าเว็บทันที
                                
                                function getRedirectUrl() {
                                    var role = " . $_SESSION['user_data']['role'] . ";
                                    if (role === 0) {
                                        return 'Admin/Ad-mainpage.php';
                                    } else if (role === 1) {
                                        return 'Office/Of-mainpage.php';
                                    } else {
                                        return 'Mechanic/Mc-mainpage.php';
                                    }
                                }
                            </script>";
                        exit();
                    }
                }
                }else {
                    $error = "❌ Username หรือ Password ไม่ถูกต้อง";
                }
            } else {
                $error = "❌ Username หรือ Password ไม่ถูกต้อง";
            }
            $stmt->close();
        } 
    }
    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>ล็อกอินเข้าสู่ระบบ</title>
    <style>
        body {
            font-family: Prompt;
            background-color: #835EB7; /* Purple background */
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .prompt-thin {
        font-family: "Prompt", sans-serif;
        font-weight: 100;
        font-style: normal;
        }
        
        .container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 350px;
        }

        .login-form h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #835EB7; /* Darker purple */
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #835EB7;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #835EB7;
        }

        a {
            color: #835EB7;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }


        </style>
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
                    <input type="password" id="password" name="password" minlength="8" required>
                </div>
                <button type="submit">เข้าสู่ระบบ</button>
            </form><br>
        </div>
    </div>
    <script src="script.js"></script> 
</body>
</html>