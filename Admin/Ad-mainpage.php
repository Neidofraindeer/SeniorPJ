<?php
session_start();

// เช็คว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_data'])) {
    header("Location: /SeniorPJ/index.php"); // กลับไปหน้าเข้าสู่ระบบ
    exit();
}
// ตรวจสอบว่า 'profile_picture' ถูกตั้งค่าใน session หรือไม่
$profile_picture = isset($_SESSION['user_data']['profile_picture']) ? $_SESSION['user_data']['profile_picture'] : 'default-profile.png'; // กำหนดค่าดีฟอลต์ในกรณีไม่มีรูป

// ตรวจสอบว่า 'fullname' ถูกตั้งค่าใน session หรือไม่
$fullname = isset($_SESSION['user_data']['fullname']) ? $_SESSION['user_data']['fullname'] : 'ผู้ใช้ไม่ระบุชื่อ';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการมอบหมายงาน</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="login-check.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .fa-trash-alt {
            color: #E74C3C;
            font-size: 18px; /* ขนาดไอคอน */
            transition: color 0.3s ease; /* เพิ่มเอฟเฟกต์การเปลี่ยนสี */
            
        }
        .fa-trash-alt:hover {
           color: #c0392b; 
        }
        .actions a i {
            margin-left: 5px; /* เพิ่มระยะห่างระหว่างไอคอน */
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
            margin-top: 60px; /* เพิ่มระยะห่างจาก profile */
        }
        .sidebar li {
            list-style: none;
            
        }
        .sidebar  li {
            font-size: 16px;
            cursor: pointer;
            padding:  10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin: 0;
        }
        .sidebar  li:hover {
            background-color:rgb(121, 86, 171);
            text-decoration: none;
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
        tr:hover {
            background-color:rgb(247, 242, 254);
            transition: 0.2s;
        }
        

    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div>
        <div class="profile">
                <!-- ใช้รูปภาพจาก session หรือ URL ที่เก็บไว้ในฐานข้อมูล -->
                <img src="../uploads/<?php echo $profile_picture; ?>" alt="User Profile">
                <!-- แสดงคำทักทายพร้อมชื่อเต็ม -->
                <h3><?php echo $fullname; ?></h3>
            </div><br>
            <ul><br>
                <li onclick="document.location='Ad-user.php'">ข้อมูลผู้ใช้งาน</li>
                <li onclick="document.location='Ad-status.php'">ติดตามสถานะ</li>
                <li onclick="document.location='Ad-approve.php'">รายการอนุมัติ</li>
                <li onclick="document.location='Ad-history.php'">ประวัติการซ่อมแซม</li>
            </ul>
        </div>
        <ul><br>
            <li onclick="document.location='Ad-setting.php'">แก้ไขข้อมูลส่วนตัว</li>
            <li onclick="document.location='Ad-repassword.php'">เปลี่ยนรหัสผ่าน</li>
            <li onclick="confirmLogout()">ออกจากระบบ</li>
        </ul>
    </div>
    <!-- Content -->
    <div class="content">
        <h1>รายการมอบหมายงาน</h1>
        <!-- Search Bar -->
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="ค้นหา..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit" class="btn-search">ค้นหา</button>
            <button type="button" class="btn-add" onclick="document.location='Ad-inputcar.php'">เพิ่มรายการ</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>เวลา</th>
                    <th>รหัสรถ</th>
                    <th>ทะเบียนรถ</th>
                    <th>ยี่ห้อ</th>
                    <th>พนักงานช่าง</th>
                    <th>สถานะ</th>
                    <th></th>
                </tr>
            </thead>
            <?php
                // เชื่อมต่อกับฐานข้อมูล
                include '../conn.php';
                // รับค่าค้นหาจาก GET
                $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                // กำหนดจำนวนแถวที่ต้องการแสดง
                $limit = 11;
                // รับค่าหน้า (page) จาก URL ถ้าไม่มีค่าหน้า ให้ตั้งค่าเริ่มต้นเป็น 1
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                // คำนวณเริ่มต้นของการแสดงข้อมูลในตาราง
                $start = ($page - 1) * $limit;
                // เงื่อนไขการค้นหา
                $search_query = "";
                if (!empty($search)) {
                    $search_query = " AND (c.CarNumber LIKE '%$search%' 
                                        OR c.CarBrand LIKE '%$search%'
                                        OR w.Work_Date LIKE '%$search%'
                                        OR w.Work_Time LIKE '%$search%' 
                                        OR CONCAT(u.User_Firstname, ' ', u.User_Lastname) LIKE '%$search%')";
                }
                // ดึงข้อมูลจากฐานข้อมูล
                $sql = "SELECT w.Work_ID, w.Work_Date, w.Work_Time, c.Car_ID, c.CarNumber, c.CarBrand,u.User_Picture, 
                    CONCAT(u.User_Firstname, ' ', u.User_Lastname) AS FullName, a.Approve_Status
                FROM tb_work w
                JOIN tb_car c ON w.Car_ID = c.Car_ID
                JOIN tb_user u ON w.User_ID = u.User_ID
                LEFT JOIN tb_approve a ON w.Work_ID = a.Approve_ID
                WHERE a.Approve_Status IN ('approved', 'pending') 
                $search_query
                LIMIT $limit OFFSET $start";
                $result = $conn->query($sql);
                // แสดงข้อมูลในตาราง
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr onclick=\"window.location='Ad-editcar.php?id=" . $row['Work_ID'] . "'\" style='cursor: pointer;'>";
                        echo "<td>" . $row['Work_Date'] . "</td>";
                        echo "<td>" . $row['Work_Time'] . "</td>";
                        echo "<td>" . $row['Car_ID'] . "</td>";
                        echo "<td>" . $row['CarNumber'] . "</td>";
                        echo "<td>" . $row['CarBrand'] . "</td>";
                        echo "<td>" . $row['FullName']. "</td>"; 
                        // แสดงสถานะการอนุมัติ
                        if ($row['Approve_Status'] == 'approved') {
                            echo "<td><div class='status-approved'>อนุมัติ</div></td>";
                        } else {
                            echo "<td><div class='status-pending'>รออนุมัติ</div></td>";
                        }
                        echo "<td class='actions'>";
                        echo "<a href='Ad-editcar.php?id=" . $row['Car_ID'] . "' onclick='event.stopPropagation();'><i class='fa fa-pencil-alt'></i></a> ";
                        echo "<a href='javascript:void(0)' onclick='event.stopPropagation(); deletework(" . $row['Car_ID'] . ")'><i class='fas fa-trash-alt'></i></a>";
                        echo "</td>";
                
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align: center;'>ไม่มีข้อมูล</td></tr>";
                }
                // คำนวณจำนวนหน้าทั้งหมด
                $sql_count = "SELECT COUNT(*) AS total FROM tb_work w
                            JOIN tb_car c ON w.Car_ID = c.Car_ID
                            JOIN tb_user u ON w.User_ID = u.User_ID
                            LEFT JOIN tb_approve a ON w.Work_ID = a.Approve_ID
                            WHERE a.Approve_Status IN ('approved', 'pending') 
                            $search_query";
                $result_count = $conn->query($sql_count);
                $row_count = $result_count->fetch_assoc();
                $total_records = $row_count['total'];
                $total_pages = ceil($total_records / $limit);
                    // ปิดการเชื่อมต่อฐานข้อมูล
                    $conn->close();
                    ?>
        </table>
                    <div class="pagination">
                        <?php
                        if ($page > 1) {
                            echo "<a href='?page=1&search=$search'>หน้าแรก</a>";
                        }

                        $start_page = max(1, $page - 1);
                        $end_page = min($total_pages, $page + 1);
                        
                        for ($i = $start_page; $i <= $end_page; $i++) {
                            if ($i == $page) {
                                echo "<span style='padding: 8px 16px; margin: 0 5px; background-color: #835EB7; color: white; border-radius: 5px;'>$i</span>";
                            } else {
                                echo "<a href='?page=$i&search=$search'>$i</a>"; // เพิ่ม &search=$search เพื่อให้ค้นหาคงอยู่
                            }
                        }

                        if ($page < $total_pages) {
                            echo "<a href='?page=" . $total_pages . "&search=$search'>สุดท้าย</a>";
                        }
                        ?>
                    </div>
    </div>
    <script>
        function deletework(workId) {
            if (confirm('คุณต้องการลบผู้ใช้นี้หรือไม่?')) {
                window.location.href = 'Ad-deletework.php?id=' + workId;
            } 
        }
    </script>
    <script>
        function confirmLogout() {
            Swal.fire({
                title: "คุณแน่ใจหรือไม่?",
                text: "คุณต้องการออกจากระบบหรือไม่?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745", // สีเขียว
                cancelButtonColor: "#d33", // สีแดง
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/SeniorPJ/logout.php";
                }
            });
        }
    </script>
</body>
</html>      
        