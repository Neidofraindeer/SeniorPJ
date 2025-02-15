<?php
$conn = new mysqli('localhost','root','','project');
$conn->query("SET NAMES utf8");
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ป้องกัน SQL Injection
    $query = $conn->prepare("SELECT * FROM tb_login WHERE username = ? AND password = ?");
    $query->bind_param("ss", $username, $password);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // ตรวจสอบรหัสผ่านที่ถูกเข้ารหัส
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit; // หยุดการทำงานของสคริปต์หลังจาก redirect
        } else {
            echo "Invalid credentials";
        }
    } 
}
?>
