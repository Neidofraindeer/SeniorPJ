<?php
$conn = new mysqli('localhost','root','','project');
$conn->query("SET NAMES utf8");

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // ป้องกัน SQL Injection และใช้การเข้ารหัสรหัสผ่าน
        $query = $conn->prepare("SELECT * FROM tb_login WHERE username = ?");
        $query->bind_param("s", $username);  // ใช้แค่ 'username' ในการค้นหา
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['Password'])) {
                $_SESSION['username'] = $user['Username'];
                $_SESSION['role'] = $user['Role_ID'];
                header("Location: dashboard.php");
                exit;
            
        } 
        }
      } 
    }


?>
