<?php
session_start();

if (!isset($_SESSION['user_data'])) {
    // Debug (ให้ดูว่ามี session หรือไม่)
    echo "Session lost or not set. Redirecting...";
    exit();
    header("Location: /SeniorPJ/index.php");
    exit();
}

$user_id = $_SESSION['user_data']['user_id']; // กำหนดค่า user_id
$profile_picture = $_SESSION['user_data']['profile_picture'] ?? 'default-profile.png';
$fullname = $_SESSION['user_data']['fullname'] ?? 'ผู้ใช้ไม่ระบุชื่อ';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการซ่อมแซ่ม</title>
    <script src="login-check.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
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

    /* Table */
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    th, td {
        border: 1px solid #ddd;
        text-align: left;
        padding: 10px;
    }

    th {
        background-color:  #835eb7;
        color: white;
        text-align: center;
    }

    td {
        font-size: 14px;
    }

    th:last-child, td:last-child {
        width: 100px; /* กำหนดความกว้างของคอลัมน์ "ส่งมอบงาน" */
        text-align: center;
    }

    .btn {
        background-color: #6495ED; 
        color: white;
        padding: 7px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }
    .btn:hover {
        background-color: #3474ea; 
    }
    .btn-return {
            background-color: #3474ea;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-return:hover {
            background-color:rgb(0, 80, 229);
        }
        /* จัดปุ่มอนุมัติให้กลาง */
        .btn-return-wrapper {
            display: flex;
            justify-content: center;
        }
        tr:hover {
            background-color:rgb(247, 242, 254);
            transition: 0.2s;
        }
        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

</style>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
            <div class="profile">
                <!-- ใช้รูปภาพจาก session หรือ URL ที่เก็บไว้ในฐานข้อมูล -->
                <img src="../uploads/<?php echo $profile_picture; ?>" alt="User Profile">
                <!-- แสดงคำทักทายพร้อมชื่อเต็ม -->
                <h3><?php echo $fullname; ?></h3>
            </div>
            <ul><br>
                <li onclick="document.location='Mc-history.php'">ประวัติการซ่อมแซม</li>
                <li onclick="document.location='Mc-setting.php'">แก้ไขข้อมูลส่วนตัว</li>
                <li onclick="document.location='Mc-repassword.php'">เปลี่ยนรหัสผ่าน</li>
                <li onclick="confirmLogout()">ออกจากระบบ</li>
            </ul>
    </div>
    <!-- Content -->
    <div class="content">
        <h1>รายการซ่อมแซ่ม</h1>
        <!-- Table -->
        <table>
            <thead>
                <tr class="main">
                    <th>วันที่</th>
                    <th>เวลา</th>
                    <th>รหัสรถ</th>
                    <th>ทะเบียนรถ</th>
                    <th>ยี่ห้อ</th>
                    <th>รายละเอียดตำแหน่งที่ซ่อมแซม</th>
                    <th>ส่งมอบงาน</th>
                </tr>
            </thead>
            <?php
                // เชื่อมต่อกับฐานข้อมูล
                include '../conn.php';


                // กำหนดจำนวนแถวที่ต้องการแสดง
                $limit = 11;
                // รับค่าหน้า (page) จาก URL ถ้าไม่มีค่าหน้า ให้ตั้งค่าเริ่มต้นเป็น 1
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                // คำนวณเริ่มต้นของการแสดงข้อมูลในตาราง
                $start = ($page - 1) * $limit;
                
                // ดึงข้อมูลจากฐานข้อมูลเฉพาะงานของผู้ที่เข้าสู่ระบบ (User_ID)
                $sql = "SELECT w.Work_ID, w.Work_Date, w.Work_Time, c.Car_ID, c.CarNumber,
                 c.CarBrand, c.CarDetail, a.Approve_Status, r.Return_ID
                FROM tb_work w
                JOIN tb_car c ON w.Car_ID = c.Car_ID
                LEFT JOIN tb_approve a ON w.Work_ID = a.Approve_ID
                LEFT JOIN tb_return r ON w.Work_ID = r.Work_ID
                WHERE w.User_ID = '$user_id' 
                AND a.Approve_Status IN ('approved', 'returned')
                GROUP BY w.Work_ID";

                $result = $conn->query($sql);

                // แสดงข้อมูลในตาราง
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr onclick=\"window.location='Mc-car-detail.php?id=" . $row['Work_ID'] . "'\" style='cursor: pointer;'>";
                        echo "<td>" . $row['Work_Date'] . "</td>";
                        echo "<td>" . $row['Work_Time'] . "</td>";
                        echo "<td>" . $row['Car_ID'] . "</td>";
                        echo "<td>" . $row['CarNumber'] . "</td>";
                        echo "<td>" . $row['CarBrand'] . "</td>";
                        echo "<td>" . $row['CarDetail'] . "</td>";

                        $status = $row['Approve_Status'] ?? 'approved';
                        if ($status == 'approved') {
                            echo "<td>
                                    <form action='Mc-update-return.php' method='POST'>
                                        <input type='hidden' name='Return_ID' value='" . $row['Return_ID'] . "'>
                                        <input type='hidden' name='Work_ID' value='" . $row['Work_ID'] . "'>
                                        <button type='submit' name='status' value='return' class='btn-return'>ส่งมอบ</button>
                                    </form>
                                </td>";
                        } elseif ($status == 'returned') {
                            echo "<td style='text-align: center;'>
                                    <button class='btn-return' disabled style='background-color: #ccc;'>ส่งมอบ</button>
                                </td>";
                        } else {
                            echo "<td style='text-align: center;'>" . ucfirst($status) . "</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align: center;'>ไม่มีข้อมูล</td></tr>";
                }
                        ?>
                    </div>
        </table>
    </div>
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
