<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการมอบหมายงาน</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Icon ปากกา */
        .fa-pencil-alt {
            color: rgb(144, 127, 201); /* สีน้ำเงิน */
            font-size: 18px; /* ขนาดไอคอน */
            transition: color 0.3s ease; /* เพิ่มเอฟเฟกต์การเปลี่ยนสี */
        }

        .fa-pencil-alt:hover {
            color: #835EB7; /* สีเข้มเมื่อ hover */
        }
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        /* Body */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            display: flex;
        }
        /* Sidebar */
        .sidebar {
            width: 240px;
            height: 100vh;
            background-color:  #835eb7;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar .profile {
            text-align: center;
        }
        .sidebar img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: white;
            margin-bottom: 10px;
        }
        .sidebar h3 {
            margin: 10px 0;
            font-size: 18px;
        }
        .sidebar ul {
            list-style: none;
        }
        .sidebar ul li {
            margin: 15px 0;
            font-size: 16px;
            cursor: pointer;
        }
        .sidebar ul li:hover {
            text-decoration: underline;
        }
        /* Content */
        .content {
            flex: 1;
            padding: 20px;
        }
        .content h1 {
            color:  #835eb7;
            font-size: 24px;
            margin-bottom: 20px;
        }
        /* Search Bar */
        .search-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .search-bar input[type="text"] {
            flex: 1;
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-search {
            background-color: #6495ED; /* สีน้ำเงิน */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-right: 10px;
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
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            text-align: left; /* ทำให้ข้อความอยู่ตรงกลาง */
            padding: 8px; /* ลดขนาด Padding เพื่อให้ช่องไม่กว้างเกินไป */
        }

        th {
            background-color: #835eb7;
            color: white;
            text-align: center;
            white-space: nowrap; /* ป้องกันข้อความล้นบรรทัด */
            font-size: 16px; /* ลดขนาดตัวอักษรในหัวข้อ */
        }

        td {
            font-size: 14px; /* ขนาดตัวอักษรสำหรับข้อมูล */
            white-space: nowrap; /* ป้องกันข้อความล้นบรรทัด */
        }

        th:nth-child(1), td:nth-child(1),
        th:nth-child(2), td:nth-child(2) {
            text-align: center; /* ทำให้วันที่และรหัสอยู่ตรงกลาง */
            width: 150px; /* กำหนดความกว้างให้พอดี */
        }
        
        th:nth-child(5), 
        td:nth-child(5) {
            width: 245px; /* กำหนดความกว้างคอลัมน์พนักงานช่าง */
            
        }
        .status-approved {
            color: green;
            font-weight: bold;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .btn-options {
            background-color: #ccc;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-options:hover {
            background-color: #bbb;
        }
        /* สไตล์ของ label */
        .label-options {
            display: inline-block;
            background-color: #ccc;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 16px;
        }
        /* ทำให้ label ชิดขวาของคอลัมน์ */
        .status-container {
            display: flex;
            justify-content: space-between; /* จัดให้ออกชิดขวา */
            align-items: center;
        }
        /* เพิ่มเอฟเฟกต์เมื่อ hover */
        .label-options:hover {
            background-color: #bbb;
        }
        .label-options:active {
            background-color: #aaa;
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

    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <div class="profile">
                <img src="https://via.placeholder.com/100" alt="User Profile">
                <h3>Admin</h3>
            </div>
            <ul><br>
                <li>การแจ้งเตือน</li>
                <li onclick="document.location='Ad-user.php'">ข้อมูลผู้ใช้งาน</li>
                <li onclick="document.location='Ad-status.php'">ติดตามสถานะ</li>
                <li onclick="document.location='Ad-approve.php'">รายการอนุมัติ</li>
                <li onclick="document.location='Ad-history.php'">ประวัติการซ่อมแซม</li>
            </ul>
        </div>
        <ul><br>
            <li onclick="document.location='Ad-setting.php'">การตั้งค่า</li>
            <li onclick="document.location='/SeniorPJ/index.php'">ออกจากระบบ</li>
        </ul>
    </div>
    <!-- Content -->
    <div class="content">
        <h1>รายการมอบหมายงาน</h1>
        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" placeholder="ค้นหา...">
            <button class="btn-search">ค้นหา</button>
            <button class="btn-add" onclick="document.location='Ad-inputcar.php'">เพิ่มรายการ</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>รหัสรถ</th>
                    <th>ทะเบียนรถ</th>
                    <th>ยี่ห้อ</th>
                    <th>พนักงานช่าง</th>
                    <th>สถานะ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
            // เชื่อมต่อกับฐานข้อมูล
            include '../conn.php';

            // กำหนดจำนวนแถวที่ต้องการแสดง
            $limit = 11;

            // รับค่าหน้า (page) จาก URL ถ้าไม่มีค่าหน้า ให้ตั้งค่าเริ่มต้นเป็น 1
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

            // คำนวณเริ่มต้นของการแสดงข้อมูลในตาราง
            $start = ($page - 1) * $limit;

            // ดึงข้อมูลจากฐานข้อมูล
            $sql = "SELECT w.CarRepair_Date, w.CarRepair_Time, c.Car_ID, c.CarNumber, c.CarBrand, 
                    CONCAT(u.User_Firstname, ' ', u.User_Lastname) AS FullName, 
                    a.Approve_Status, a.Approve_ID, a.Approve_Date, u.User_ID
                FROM tb_work w
                JOIN tb_car c ON w.Car_ID = c.Car_ID
                JOIN tb_user u ON w.User_ID = u.User_ID
                LEFT JOIN tb_approve a ON u.User_ID = a.User_ID
                ORDER BY 
                    CASE WHEN a.Approve_Status = 'approved' THEN 1 ELSE 2 END, -- อนุมัติแล้วมาก่อน
                    a.Approve_Date DESC, -- วันที่อนุมัติเรียงจากล่าสุดไปหาเก่าสุด
                    w.CarRepair_Date DESC, -- วันที่ซ่อมเรียงจากล่าสุด
                    w.CarRepair_Time DESC -- เวลาซ่อมเรียงจากล่าสุด
                LIMIT $start, $limit";

            $result = $conn->query($sql);

            // แสดงข้อมูลในตาราง
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['CarRepair_Date'] . "</td>";
                    echo "<td>" . $row['Car_ID'] . "</td>";
                    echo "<td>" . $row['CarNumber'] . "</td>";
                    echo "<td>" . $row['CarBrand'] . "</td>";
                    echo "<td>" . $row['FullName'] . "</td>";

                    // แสดงสถานะการอนุมัติ
                    if ($row['Approve_Status'] == 'approved') {
                        echo "<td><div class='status-approved'>อนุมัติ</div></td>";
                    } else {
                        echo "<td><div class='status-pending'>รออนุมัติ</div></td>";
                    }
                        echo "<td><a href='Ad-editcar.php?id=" . $row['Car_ID'] . "'><i class='fa fa-pencil-alt'></i></a></td>";  // ปุ่มแก้ไข
                        echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' style='text-align: center;'>ไม่มีข้อมูล</td></tr>";
            }

            // คำนวณจำนวนหน้าทั้งหมด
            $sql_count = "SELECT COUNT(*) AS total FROM tb_work w
                        JOIN tb_car c ON w.Car_ID = c.Car_ID
                        JOIN tb_user u ON w.User_ID = u.User_ID
                        LEFT JOIN tb_approve a ON u.User_ID = a.User_ID";
            $result_count = $conn->query($sql_count);
            $row_count = $result_count->fetch_assoc();
            $total_records = $row_count['total'];

            // คำนวณจำนวนหน้าทั้งหมด
            $total_pages = ceil($total_records / $limit);

            // ปิดการเชื่อมต่อฐานข้อมูล
            $conn->close();
            ?>
            </table>
            <div class="pagination">
            <?php
            // ปุ่มก่อนหน้า
            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "'>ก่อนหน้า</a>";
            }
            // แสดงเลขหน้า
            $start_page = max(1, $page - 1);  // หน้าที่เริ่มต้นแสดง
            $end_page = min($total_pages, $page + 1);  // หน้าสุดท้ายแสดง
            for ($i = $start_page; $i <= $end_page; $i++) {
                if ($i == $page) {
                    echo "<span style='padding: 8px 16px; margin: 0 5px; background-color: #835EB7; color: white; border-radius: 5px;'>$i</span>";
                } else {
                    echo "<a href='?page=$i'>$i</a>";
                }
            }
            // ปุ่มถัดไป
            if ($page < $total_pages) {
                echo "<a href='?page=" . ($page + 1) . "'>ถัดไป</a>";
            }
            ?>
        </div>
    </div>
</body>
</html>