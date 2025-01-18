<?php
require '../conn.php'; // เชื่อมต่อกับฐานข้อมูล

// กำหนดจำนวนแถวที่แสดงในแต่ละหน้า
$limit = 10;

// ตรวจสอบว่า URL มีค่า `page` หรือไม่
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // ถ้าไม่มีให้เริ่มที่หน้า 1

// คำนวณตำแหน่งเริ่มต้น
$start = ($page - 1) * $limit;

// ดึงข้อมูลผู้ใช้จาก tb_user และแผนกจาก tb_department พร้อมกับ LIMIT
$sql = "SELECT u.User_ID, l.Username, u.User_Firstname, u.User_Lastname, d.Department_Name 
        FROM tb_user u
        LEFT JOIN tb_login l ON u.User_ID = l.User_ID
        LEFT JOIN tb_department d ON u.Department_ID = d.Department_ID
        ORDER BY u.User_ID ASC
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
        th:first-child, td:first-child {
            text-align: center; /* จัดข้อความให้อยู่ตรงกลาง */
            width: 100px; /* กำหนดความกว้าง (ถ้าต้องการ) */
        }
        th:last-child, td:last-child {
            width: 120px; /* กำหนดความกว้าง */
            text-align: center; /* จัดให้อยู่ตรงกลาง */
        }
        .actions button {
            padding: 5px 8px; /* ลดขนาด padding */
            font-size: 14px; /* ลดขนาดตัวอักษร */
            margin: 2px; /* ลดช่องว่างระหว่างปุ่ม */
        }
        .actions button {
            background-color:rgb(233, 155, 45); /* สีน้ำเงิน */
            color: white;
            border: none;
            padding: 5px 10px;
            margin: 0 5px;
            border-radius: 5px;
            cursor: pointer;
        }

        .actions button:hover {
            background-color: rgb(189, 118, 19);
        }

        .actions button:nth-child(2) {
            background-color: #E74C3C; /* สีแดง */
        }

        .actions button:nth-child(2):hover {
            background-color: #c0392b; /* สีแดงเข้มเมื่อ hover */
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
            background-color: rgb(144, 127, 201);
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .pagination a:hover {
            background-color: #835EB7;
        }
        
    </style>
</head>
<body>
<div class="container">
        <div class="form-title">
            <a onclick="document.location='Ad-mainpage.php'" class="back-link">&lt;  </a>
            <a class="header">ข้อมูลผู้ใช้งาน</a>
        </div><br>
        <div class="search-bar">
            <input type="text" placeholder="ค้นหา...">
            <button class="btn-search">ค้นหา</button>
            <button class="btn-add" onclick="document.location='Ad-inputuser.php'">เพิ่มรายการ</button>
        </div>
        <table>
        <thead>
            <tr>
                <th>รหัส</th>
                <th>ชื่อผู้ใช้</th>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>แผนก</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // แสดงข้อมูลในตาราง
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td style='text-align: center;'>" . $row['User_ID'] . "</td>
                            <td>" . $row['Username'] . "</td>
                            <td>" . $row['User_Firstname'] . "</td>
                            <td>" . $row['User_Lastname'] . "</td>
                            <td>" . $row['Department_Name'] . "</td>
                            <td class='actions'>
                                <button onclick=\"document.location='Ad-edituser.php?id=" . $row['User_ID'] . "'\">แก้ไข</button>
                                 <button onclick=\"deleteUser(" . $row['User_ID'] . ")\">ลบ</button>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>ไม่มีข้อมูล</td></tr>";
            }
            ?>
        </tbody>
        </table>
        <!-- การแสดง pagination -->
        <div class="pagination">
            <?php
            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "'>ก่อนหน้า</a>";
            }
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo "<span style='padding: 8px 16px; margin: 0 5px; background-color: #835EB7; color: white; border-radius: 5px;'>$i</span>";
                } else {
                    echo "<a href='?page=$i'>$i</a>";
                }
            }
            if ($page < $total_pages) {
                echo "<a href='?page=" . ($page + 1) . "'>ถัดไป</a>";
            }
            ?>
        </div>
    </div>
    <script>
        function deleteUser(userId) {
            if (confirm('คุณต้องการลบผู้ใช้นี้หรือไม่?')) {
                window.location.href = 'Ad-deleteuser.php?id=' + userId;
            }
        }
    </script>
</body>
</html>

<?php
$conn->close(); // ปิดการเชื่อมต่อ
?>
