<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// แสดงชื่อผู้ใช้ที่เข้าสู่ระบบ
echo "Welcome, " . $_SESSION['fullname']; // แสดงชื่อเต็มของผู้ใช้

if ($_SESSION['role'] == 'admin') {
    echo "<br><a href='Ad-mainpage.php'>Go to Admin Page</a>";
} elseif ($_SESSION['role'] == 'office') {
    echo "<br><a href='Of-mainpage.php'>Go to Office Page</a>";
} elseif ($_SESSION['role'] == 'mechanic') {
    echo "<br><a href='Mc-mainpage.php'>Go to Mechanic Page</a>";
} else {
    echo "<br>Unauthorized access!";
}
?>
