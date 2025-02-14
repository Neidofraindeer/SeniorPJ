<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขรายการ</title>
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
        .form-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .form-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        .form-buttons .btn-save {
            background-color: #4CAF50;
            color: white;
        }
        .form-buttons .btn-save:hover {
            background-color: #45a049;
        }
        .form-buttons .btn-cancel {
            background-color: #f44336;
            color: white;
        }
        .form-buttons .btn-cancel:hover {
            background-color: #e53935;
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
        $sql = "SELECT w.Work_ID, w.Car_ID, w.User_ID, w.Work_Date, w.Work_Time,
                    c.CarNumber, c.CarModel, c.CarBrand, c.CarColor, c.CarDetail,
                    c.CarPicture, c.RepairPicture, c.CarInsurance
                FROM tb_work w
                JOIN tb_car c ON w.Car_ID = c.Car_ID
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
            <a onclick="document.location='Ad-mainpage.php'" class="back-link">&lt; </a>
            <a class="header">แก้ไขรายการ</a>
        </div>
        <form action="Ad-editcarsucc.php?Work_ID=<?= $row['Work_ID']; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="Work_ID" value="<?php echo $row['Work_ID']; ?>">
        <input type="hidden" name="Car_ID" value="<?php echo $row['Car_ID']; ?>">

        <!-- ข้อมูลรถยนต์ -->
            <div class="form-section">
                <h4>วันที่มอบหมายงาน : <?php echo $row['Work_Date']; ?></h4>
                <h3>ข้อมูลรถยนต์</h3>
                <div class="form-group">
                    <label for="photo">รูป:</label>
                    <img src="<?= htmlspecialchars($row['CarPicture']); ?>" alt="รูปภาพรถยนต์" width="200">
                    <input type="file" id="photo" name="CarPicture">
                </div>
                <div class="form-group">
                    <label>หมายเลขทะเบียน:</label>
                    <input type="text" name="CarNumber" placeholder="กรอกหมายเลขทะเบียน" value="<?php echo $row['CarNumber']; ?>" required>
                </div>
                <div class="form-group">
                    <label>ยี่ห้อ:</label>
                    <input type="text" name="CarBrand" placeholder="กรอกยี่ห้อ" value="<?php echo $row['CarBrand']; ?>" required>
                </div>
                <div class="form-group">
                    <label>รุ่น:</label>
                    <input type="text" name="CarModel" placeholder="กรอกรุ่น" value="<?php echo $row['CarModel']; ?>" required>
                </div>
                <div class="form-group">
                    <label>สีรถ:</label>
                    <input type="text" name="CarColor" placeholder="กรอกสี" value="<?php echo $row['CarColor']; ?>" required>
                </div>
                <div class="form-group">
                    <label>บริษัทประกัน:</label>
                    <input type="text" name="CarInsurance" placeholder="กรอกบริษัทประกัน" value="<?php echo $row['CarInsurance']; ?>" required>
                </div>
            </div>

            <!-- ข้อมูลตำแหน่งซ่อมแซม -->
            <div class="form-section">
                <h3>ข้อมูลตำแหน่งซ่อมแซม</h3>
                <div class="form-group">
                    <label for="repair_photo">รูป:</label>
                    <img src="<?= htmlspecialchars($row['RepairPicture']); ?>" alt="รูปตำแหน่งซ่อมแซม" width="200"> 
                    <input type="file" id="repair_photo" name="RepairPicture" multiple >
                </div>
                <div class="form-group">
                    <label>รายละเอียดตำแหน่งที่ซ่อมแซม:</label>
                    <textarea name="CarDetail" placeholder="กรอกรายละเอียดตำแหน่งที่ซ่อมแซม"required> <?php echo $row['CarDetail']; ?> </textarea>
                </div>
            </div>
            <div class="form-group">
                <label>ชื่อพนักงานช่าง:</label>
                <select name="User_ID" required>
                    <option value="">-- เลือกพนักงานช่าง --</option>
                    <?php
                    // ดึงข้อมูลพนักงานจาก tb_user
                    $sql = "SELECT u.User_ID, CONCAT(u.User_Firstname, ' ', u.User_Lastname) AS FullName, d.Department_name 
                            FROM tb_user u 
                            JOIN tb_role r
                            JOIN tb_department d ON u.Department_ID = d.Department_ID
                            WHERE r.Role_ID = '2' AND u.Department_ID IN (2, 3, 4, 5)";
                    $result = $conn->query($sql);
                    while ($row_emp = $result->fetch_assoc()) {
                        // หาก User_ID ตรงกับข้อมูลที่โหลดมา ให้เลือกค่า default
                        $selected = ($row_emp['User_ID'] == $row['User_ID']) ? 'selected' : '';
                        echo "<option value='{$row_emp['User_ID']}' $selected>{$row_emp['FullName']} - {$row_emp['Department_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- ปุ่มบันทึกและยกเลิก -->
            <div class="form-buttons">
                <button type="submit" class="btn-save">บันทึก</button>
            </div>
        </form>
    </div>
</body>
</html>