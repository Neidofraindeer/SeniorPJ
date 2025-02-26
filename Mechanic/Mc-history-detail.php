<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดรถ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        /* กรอปหัวข้อฟอร์ม */
        .form-title{
            width: 100%;
            height: 35px;
            padding: 10px;
        }
        /* หัวข้อฟอร์ม */
        .form-title .head {
            color: #835EB7; 
            font-size: 24px;
        }

        /* < กลับ */
        .form-title .back-link{
            color: #90879c;
            font-size: 24px;
            font-style: oblique;
        }
        .header {
            font-size: 24px;
            color: #5A47AB;
            margin-bottom: 20px;
        }
        .form-section {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .form-section:last-child {
            border-bottom: none;
        }
        .form-section h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 15px;
        }
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-group label {
            width: 150px;
            font-size: 14px;
            color: #555;
        }
        .form-group input[type="text"],
        .form-group select,
        .form-group textarea {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-group textarea {
            resize: none;
            height: 80px;
        }
        

    </style>
</head>
<body>
    <?php
    include '../conn.php';
    // ตรวจสอบว่ามี Work_ID ถูกส่งมาหรือไม่
    if (isset($_GET['id'])) {
        $Work_ID = $_GET['id'];

        // ดึงข้อมูลจาก tb_work และ tb_car
        $sql = "SELECT r.Return_Date, r.Return_Time, w.Work_Date, w.Work_Time, c.CarNumber, c.CarBrand, c.Car_ID, 
                 c.CarModel, c.CarColor, c.CarInsurance, c.CarPicture, c.RepairPicture, c.CarDetail,
                 w.Work_ID, w.User_ID, CONCAT(u.User_Firstname, ' ', u.User_Lastname) AS FullName, a.Approve_Status
                        FROM tb_return r
                        JOIN tb_work w ON r.Work_ID = w.Work_ID
                        JOIN tb_car c ON w.Car_ID = c.Car_ID
                        JOIN tb_approve a ON w.Work_ID = a.Work_ID
                        JOIN tb_user u ON w.User_ID = u.User_ID
                        WHERE w.Work_ID = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $Work_ID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "<p>ไม่พบข้อมูลที่ต้องการแก้ไข สำหรับ Work_ID: $Work_ID</p>";
            exit();
        }
    } else {
        echo "<p>ไม่พบ Work_ID</p>";
        exit();
    }
    ?>
<div class="container">
        <div class="form-title">
            <a onclick="document.location='Mc-history.php'" class="back-link">&lt;</a>
        </div>
        <h4>วันที่มอบหมายงาน : <?php echo $row['Work_Date']; ?> เวลา: <?php echo $row['Work_Time']; ?> </h4>
        <h4>วันที่ส่งมอบงาน : <?php echo $row['Return_Date']; ?> เวลา: <?php echo $row['Return_Time']; ?></h4>
        <!-- ข้อมูลรถยนต์ -->
            <div class="form-section">
                <h3>ข้อมูลรถยนต์</h3>
                <div class="form-group">
                    <label for="photo">รูป:</label>
                    <img src="<?= htmlspecialchars($row['CarPicture']); ?>" alt="รูปภาพรถยนต์" width="200">

                </div>
                <div class="form-group">
                    <label>หมายเลขทะเบียน:</label>
                    <?php echo $row['CarNumber']; ?>
                </div>
                <div class="form-group">
                    <label>ยี่ห้อ:</label>
                    <?php echo $row['CarBrand']; ?>
                </div>
                <div class="form-group">
                    <label>รุ่น:</label>
                    <?php echo $row['CarModel']; ?>
                </div>
                <div class="form-group">
                    <label>สีรถ:</label>
                    <?php echo $row['CarColor']; ?>
                </div>
                <div class="form-group">
                    <label>บริษัทประกัน:</label>
                    <?php echo $row['CarInsurance']; ?>
                </div>
            </div>

            <!-- ข้อมูลตำแหน่งซ่อมแซม -->
            <div class="form-section">
                <h3>ข้อมูลตำแหน่งซ่อมแซม</h3>
                <div class="form-group">
                    <label for="repair_photo">รูป:</label>
                    <img src="<?= htmlspecialchars($row['RepairPicture']); ?>" alt="รูปตำแหน่งซ่อมแซม" width="200"> 
                </div>
                <div class="form-group">
                    <label>รายละเอียดตำแหน่งที่ซ่อมแซม:</label>
                    <?php echo $row['CarDetail']; ?>
                </div>
            </div>
            <div class="form-group">
                <label>ชื่อพนักงานช่าง:</label>
                <?php
                $sql_emp = "SELECT CONCAT(u.User_Firstname, ' ', u.User_Lastname) AS FullName, d.Department_name 
                            FROM tb_user u
                            JOIN tb_department d ON u.Department_ID = d.Department_ID
                            WHERE u.User_ID = ?";
                $stmt_emp = $conn->prepare($sql_emp);
                $stmt_emp->bind_param("i", $row['User_ID']);
                $stmt_emp->execute();
                $result_emp = $stmt_emp->get_result();
                if ($row_emp = $result_emp->fetch_assoc()) {
                    echo "<p>" . htmlspecialchars($row_emp['FullName'] . " - " . $row_emp['Department_name']) . "</p>";
                } else {
                    echo "<p>ไม่พบข้อมูลพนักงาน</p>";
                }
                ?>
            </div>

        </form>
    </div>
</body>
</html>
