<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'project');
$conn->query("SET NAMES utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามี session ผู้ใช้หรือไม่
if (!isset($_SESSION['User_ID'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$_SESSION['User_ID'] = $user['User_ID']; // เพิ่มลงใน session ตอนที่ผู้ใช้เข้าสู่ระบบ

// ดึงข้อมูลผู้ใช้
$query = $conn->prepare("
    SELECT User_Firstname, User_Lastname, User_Picture 
    FROM tb_user 
    WHERE User_ID = ?
");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode($user);
} else {
    echo json_encode(["error" => "User not found"]);
}

$query->close();
$conn->close();
?>
