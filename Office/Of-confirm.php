<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันซ่อมเสร็จ</title>
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
            padding: 8px; /* ลดขนาด padding เพื่อให้ช่องเล็กลง */
            border: 1px solid #ddd;
        }

        th {
            background-color: #835EB7;
            color: white;
            text-align: center;
        }

        th:nth-child(1), td:nth-child(1) {
            width: 12%; /* วันที่มอบหมายงาน */
        }

        th:nth-child(2), td:nth-child(2) {
            width: 12%; /* เวลามอบหมายงาน */
        }

        th:nth-child(3), td:nth-child(3) {
            width: 12%; /* วันที่ส่งมอบรถ */
        }

        th:nth-child(4), td:nth-child(4) {
            width: 10%; /* เวลาส่งมอบรถ */
        }
        
        td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        td button {
            display: block;
            margin: 0 auto;
            background-color: #4CAF50; /* สีเขียว */
            color: white;
            padding: 7px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        td button:hover {
            background-color: #45a049; /* สีเขียวเข้มเมื่อ hover */
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
        .btn {
            background-color: #4CAF50; /* สีเขียว */
            color: white;
            padding: 7px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049; /* สีเขียวเข้มเมื่อ hover */
        }
        tr:hover {
            background-color:rgb(247, 242, 254);
            transition: 0.2s;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="form-title">
            <a onclick="document.location='Of-mainpage.php'" class="back-link">&lt;  </a>
            <a class="head">ยืนยันซ่อมเสร็จ</a>
        </div><br>
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="ค้นหา..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit" class="btn-search">ค้นหา</button>
        </form>
            <table>
            <thead>
                <tr>
                    <th>วันที่มอบหมายงาน</th>
                    <th>เวลามอบหมายงาน</th>
                    <th>วันที่ส่งมอบรถ</th>
                    <th>เวลาส่งมอบรถ</th>
                    <th>รหัสรถ</th>
                    <th>ยี่ห้อ</th>
                    <th>ทะเบียนรถ</th>
                    <th>พนักงานช่าง</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                // เชื่อมต่อกับฐานข้อมูล
                include '../conn.php'; // แก้ไขเส้นทางตามความเหมาะสม

                $search = isset($_GET['search']) ? trim($_GET['search']) : '';

                $search_query = "";
                if (!empty($search)) {
                    $search_query = " AND (c.CarNumber LIKE '%$search%' 
                                        OR c.CarBrand LIKE '%$search%' 
                                        OR w.Work_Date LIKE '%$search%'
                                        OR w.Work_Time LIKE '%$search%'
                                        OR r.Return_Date LIKE '%$search%'
                                        OR r.Return_Time LIKE '%$search%'
                                        OR c.Car_ID LIKE '%$search%'
                                        OR w.Work_ID LIKE '%$search%'
                                        OR CONCAT(u.User_Firstname, ' ', u.User_Lastname) LIKE '%$search%')";
                }
                // ดึงข้อมูลจากฐานข้อมูล tb_return
                $sql = "SELECT r.Return_Date, r.Return_Time, w.Work_Date, w.Work_Time, c.CarNumber, c.CarBrand, c.Car_ID,
                 w.Work_ID, CONCAT(u.User_Firstname, ' ', u.User_Lastname) AS FullName, a.Approve_Status
                        FROM tb_return r
                        JOIN tb_work w ON r.Work_ID = w.Work_ID
                        JOIN tb_car c ON w.Car_ID = c.Car_ID
                        JOIN tb_approve a ON w.Work_ID = a.Work_ID
                        JOIN tb_user u ON w.User_ID = u.User_ID
                        WHERE a.Approve_Status IN ('returned', 'confirm')
                        $search_query";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr onclick=\"window.location='Of-confirm-detail.php?id=" . $row['Work_ID'] . "'\" style='cursor: pointer;'>";
                        echo "<td>" . $row['Work_Date'] . "</td>";
                        echo "<td>" . $row['Work_Time'] . "</td>";
                        echo "<td>" . $row['Return_Date'] . "</td>";
                        echo "<td>" . $row['Return_Time'] . "</td>";
                        echo "<td>" . $row['Car_ID'] . "</td>";
                        echo "<td>" . $row['CarBrand'] . "</td>";
                        echo "<td>" . $row['CarNumber'] . "</td>";
                        echo "<td>" . $row['FullName'] . "</td>";
                        echo "<td>
                                <form action='Of-confirm-update.php' method='post'>
                                    <input type='hidden' name='Work_ID' value='" . $row['Work_ID'] . "'>
                                    <button type='submit' class='btn-confirm' " . ($row['Approve_Status'] == 'confirm' ?
                                     'disabled style="background-color: gray; cursor: not-allowed;"' : '') . "> ยืนยัน
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' style='text-align: center;'>ไม่มีข้อมูล</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
