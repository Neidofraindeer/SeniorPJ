<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการอนุมัติ</title>
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
            margin-bottom: 30px;
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
            background-color: #6495ED;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-search:hover {
            background-color: #3474ea;
        }
        /* < กลับ */
        .container .back-link{
            color: #90879c;
            font-size: 24px;
            font-style: oblique;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        .btn-approve {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-approve:hover {
            background-color: #45a049;
        }
        /* จัดปุ่มอนุมัติให้กลาง */
        .btn-approve-wrapper {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
    <a onclick="document.location='Ad-mainpage.php'" class="back-link">&lt; </a>
    <a class="header">  รายการอนุมัติ</a>
        <table>
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>เวลา</th>
                    <th>รหัสรถ</th>
                    <th>ทะเบียนรถ</th>
                    <th>ยี่ห้อ</th>
                    <th>ชื่อพนักงานช่าง</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
            <?php
            include('../conn.php');
            // Query ดึงข้อมูลจากตาราง tb_work และ tb_car
            $sql = "SELECT w.Work_ID, w.Work_Date, w.Work_Time, c.Car_ID, c.CarNumber, c.CarBrand, 
                            CONCAT(u.User_Firstname, ' ', u.User_Lastname) AS FullName 
                    FROM tb_work w
                    JOIN tb_car c ON w.Car_ID = c.Car_ID
                    JOIN tb_user u ON w.User_ID = u.User_ID
                    LEFT JOIN tb_approve a ON w.Work_ID = a.Approve_ID
                    WHERE a.Approve_Status = 'pending'";
            $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['Work_Date'] . "</td>";
                        echo "<td>" . $row['Work_Time'] . "</td>";
                        echo "<td>" . $row['Car_ID'] . "</td>";
                        echo "<td>" . $row['CarNumber'] . "</td>";
                        echo "<td>" . $row['CarBrand'] . "</td>";
                        echo "<td>" . $row['FullName'] . "</td>";
                        echo "<td>
                            <form action='update-status.php' method='POST'>
                                <input type='hidden' name='Work_ID' value='" . $row['Work_ID'] . "'>
                                <button type='submit' class='btn-approve'>อนุมัติ</button>
                            </form>
                        </td>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align: center;'>ไม่มีข้อมูล</td></tr>";
                }           
            ?>

            </tbody>
        </table>
    </div>
</body>
</html>