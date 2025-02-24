<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

echo "Hello " . $_SESSION['username'] . "! Role: " . $_SESSION['role'];

if ($_SESSION['role'] == 2) { 
    header("Location: Mechanic/Mc-mainpage.php");
    exit();
} elseif ($_SESSION['role'] == 1) {
    header("Location: Office/Of-mainpage.php");
    exit();
} elseif ($_SESSION['role'] == 0) { 
    header("Location: Admin/Ad-mainpage.php");
    exit();
    
}
?>
