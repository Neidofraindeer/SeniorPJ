<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดตามสถานะ</title>
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
            gap: 10px;
        }
        .search-bar input[type="text"]{
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar input[type="date"]{
            border: 1px solid #ccc;
            border-radius: 5px;
            color: #726b7c;
        }

        .search-bar label {
            align-items: center;
            white-space: nowrap;
            color: #646168;
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
        tr:hover {
            background-color:rgb(247, 242, 254); /* เปลี่ยนสีพื้นหลัง */
            transition: 0.2s;
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
            <a onclick="document.location='Of-mainpage.php'" class="back-link">&lt;  </a>
            <a class="header"> ติดตามสถานะ</a>
        </div>
        <form method="GET" class="search-bar">
                <input type="text" name="search" placeholder="ค้นหา..." value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                <button type="submit" class="btn-search">ค้นหา</button>
            </form>
        <table>
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>รหัสรถ</th>
                    <th>ยี่ห้อ</th>
                    <th>ทะเบียนรถ</th>
                    <th>พนักงานช่าง</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
            <?php
                // เชื่อมต่อฐานข้อมูล
                include('../conn.php');

                $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                // กำหนดจำนวนรายการต่อหน้า
                $limit = 8;
                // รับค่าหน้าปัจจุบัน ถ้าไม่มีให้เริ่มที่หน้า 1
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $start = ($page - 1) * $limit;

                $search_query = "";
                if (!empty($search)) {
                    $search_query= " AND (c.CarBrand LIKE '%$search%' 
                    OR c.CarNumber LIKE '%$search%' 
                    OR s.Status_Car LIKE '%$search%'
                    OR w.Work_Date LIKE '%$search%'
                    OR w.Work_Time LIKE '%$search%' 
                    OR CONCAT(u.User_Firstname, ' ', u.User_Lastname) LIKE '%$search%')
                    OR s.Status_Car LIKE '%$search%' ";
                }

                /// Query ข้อมูล
                $sql = "SELECT w.Work_ID, w.Work_Date, w.Work_Time, c.Car_ID, c.CarBrand, c.CarNumber, 
                                CONCAT(u.User_Firstname, ' ', u.User_Lastname) AS FullName, s.Status_Car
                        FROM tb_work w
                        JOIN tb_car c ON w.Car_ID = c.Car_ID
                        JOIN tb_user u ON w.User_ID = u.User_ID
                        JOIN tb_status s ON w.Status_ID = s.Status_ID
                        JOIN tb_approve a ON w.Work_ID = a.Work_ID
                        WHERE a.Approve_Status = 'approved'
                $search_query
                LIMIT $limit OFFSET $start";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr onclick=\"window.location='Of-status-detail.php?id=" . $row['Work_ID'] . "'\" style='cursor: pointer;'>";
                        echo "<td>" . $row['Work_Date'] . "</td>";
                        echo "<td>" . $row['Car_ID'] . "</td>";
                        echo "<td>" . $row['CarBrand'] . "</td>";
                        echo "<td>" . $row['CarNumber'] . "</td>"; 
                        echo "<td>" . $row['FullName'] . "</td>";
                        echo "<td>" . $row['Status_Car'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align: center;'>ไม่มีข้อมูล</td></tr>";
                }
                // คำนวณจำนวนหน้า
                $sql_count = "SELECT COUNT(*) AS total FROM tb_work w
                            JOIN tb_car c ON w.Car_ID = c.Car_ID
                            JOIN tb_user u ON w.User_ID = u.User_ID
                            JOIN tb_status s ON w.Status_ID = s.Status_ID
                            JOIN tb_approve a ON w.Work_ID = a.Work_ID
                            WHERE a.Approve_Status = 'approved'
                            $search_query";
                $result_count = $conn->query($sql_count);
                $row_count = $result_count->fetch_assoc();
                $total_records = $row_count['total'];
                $total_pages = ceil($total_records / $limit);

                $conn->close();
                ?>
            </tbody>
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