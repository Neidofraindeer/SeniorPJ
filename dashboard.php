<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

echo "Hello " . $_SESSION['username'] . "! Role: " . $_SESSION['role'];

if ($_SESSION['role'] == 'admin') {
    echo "<br><a href='admin.php'>Go to Admin Page</a>";
}
?>
