<?php
require '../conn.php'; // เชื่อมต่อกับฐานข้อมูล

// กำหนดจำนวนแถวที่แสดงในแต่ละหน้า
$limit = 10;

// ตรวจสอบว่า URL มีค่า `page` หรือไม่
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // ถ้าไม่มีให้เริ่มที่หน้า 1

// คำนวณตำแหน่งเริ่มต้น
$start = ($page - 1) * $limit;

// ดึงข้อมูลผู้ใช้จาก tb_user และแผนกจาก tb_department พร้อมกับ LIMIT
$sql = "SELECT u.User_ID, u.User_Firstname, u.User_Lastname, d.Department_Name 
        FROM tb_user u
        LEFT JOIN tb_department d ON u.Department_ID = d.Department_ID
        LIMIT $start, $limit";

$result = $conn->query($sql); // ดำเนินการ query

// คำนวณจำนวนหน้า
$count_sql = "SELECT COUNT(*) AS total FROM tb_user";
$count_result = $conn->query($count_sql);
$row = $count_result->fetch_assoc();
$total_records = $row['total'];
$total_pages = ceil($total_records / $limit);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลผู้ใช้งาน</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            color: #835eb7;
            margin-bottom: 20px;
        }
        .search-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .search-bar input[type="text"] {
            width: 70%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-search {
            background-color: #6495ED; /* สีน้ำเงิน */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-right: 150px;
        }   
        .btn-search:hover {
            background-color: #3474ea;
        }
        .btn-add {
            background-color: #4CAF50; /* สีเขียว */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-add:hover {
            background-color: #45a049; /* สีเขียวเข้มเมื่อ hover */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #835EB7;
            color: white;
            text-align: center;
        }
        .actions button {
            background-color: #ddd;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .actions button:hover {
            background-color: #ccc;
        }
        /* กรอปหัวข้อฟอร์ม */
        .form-title{
            width: 100%;
            height: 35px;
            padding: 10px;
        }
        /* หัวข้อฟอร์ม */
        .form-title .head {
            width: 90%;
            color: #835eb7; 
            font-size: 24px;
        }

        /* < กลับ */
        .form-title .back-link{
            color: #90879c;
            font-size: 24px;
            font-style: oblique;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 16px;
            margin: 0 5px;
            background-color: #6495ED;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .pagination a:hover {
            background-color: #3474ea;
        }
    </style>
</head>
<body>
<div class="container">
        <div class="form-title">
            <a onclick="document.location='Of-mainpage.php'" class="back-link">&lt;  </a>
            <a class="head">ข้อมูลผู้ใช้งาน</a>
        </div><br>
        <div class="search-bar">
            <input type="text" placeholder="ค้นหา...">
            <button class="btn-search">ค้นหา</button>
            <button class="btn-add" onclick="document.location='Of-inputuser.php'">เพิ่มรายการ</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>รหัส</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>แผนก</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // แสดงข้อมูลในตาราง
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['User_ID'] . "</td>
                                <td>" . $row['User_Firstname'] . "</td>
                                <td>" . $row['User_Lastname'] . "</td>
                                <td>" . $row['Department_Name'] . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>ไม่มีข้อมูล</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- การแสดง pagination -->
        <div class="pagination">
            <?php
            // ปุ่มก่อนหน้า
            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "'>ก่อนหน้า</a>";
            }

            // ปุ่มหน้าถัดไป
            if ($page < $total_pages) {
                echo "<a href='?page=" . ($page + 1) . "'>ถัดไป</a>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close(); // ปิดการเชื่อมต่อ
?>
