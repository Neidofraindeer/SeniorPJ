<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มรายการ</title>
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
<div class="container">
        <div class="form-title">
            <a onclick="document.location='Ad-mainpage.php'" class="back-link">&lt; </a>
            <a class="header">เพิ่มรายการ</a>
        </div>
        <form action="Ad-inputcarsucc.php" method="POST" enctype="multipart/form-data">
            <!-- ข้อมูลรถยนต์ -->
            <div class="form-section">
                <h3>ข้อมูลรถยนต์</h3>
                <div class="form-group">
                    <label for="photo">รูป:</label>
                    <input type="file" id="photo" name="CarPicture" required>
                </div>
                <div class="form-group">
                    <label>หมายเลขทะเบียน:</label>
                    <input type="text" name="CarNumber" placeholder="กรอกหมายเลขทะเบียน" required>
                </div>
                <div class="form-group">
                    <label>ยี่ห้อ:</label>
                    <input type="text" name="CarBrand" placeholder="กรอกยี่ห้อ" required>
                </div>
                <div class="form-group">
                    <label>รุ่น:</label>
                    <input type="text" name="CarModel" placeholder="กรอกรุ่น" required>
                </div>
                <div class="form-group">
                    <label>สีรถ:</label>
                    <input type="text" name="CarColor" placeholder="กรอกสี" required>
                </div>
                <div class="form-group">
                    <label>บริษัทประกัน:</label>
                    <input type="text" name="CarInsurance" placeholder="กรอกบริษัทประกัน" required>
                </div>
            </div>

            <!-- ข้อมูลตำแหน่งซ่อมแซม -->
            <div class="form-section">
                <h3>ข้อมูลตำแหน่งซ่อมแซม</h3>
                <div class="form-group">
                    <label for="repair_photo">รูป:</label>
                    <input type="file" id="repair_photo" name="RepairPicture" required>
                </div>
                <div class="form-group">
                    <label>รายละเอียดตำแหน่งที่ซ่อมแซม:</label>
                    <textarea name="CarDetail" placeholder="กรอกรายละเอียดตำแหน่งที่ซ่อมแซม" required></textarea>
                </div>
            </div>
            <div class="form-group">
                <label>ชื่อพนักงานช่าง:</label>
                <select name="User_ID" required>
                    <option value="">-- เลือกพนักงานช่าง --</option>
                    <?php
                    include '../conn.php';
                    // เลือกพนักงานที่มี Role = 2 (พนักงานช่าง) และ Department_ID เป็น 2, 3, 4 หรือ 5 จาก tb_user
                    // และ JOIN กับ tb_department เพื่อดึงชื่อแผนก
                    $sql = "SELECT u.User_ID, CONCAT(u.User_Firstname, ' ', u.User_Lastname) AS FullName, d.Department_name 
                            FROM tb_user u 
                            JOIN tb_login l ON u.User_ID = l.User_ID
                            JOIN tb_department d ON u.Department_ID = d.Department_ID
                            WHERE l.Role = '2' AND u.Department_ID IN (2, 3, 4, 5)";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        // แสดงชื่อพนักงานและชื่อแผนก
                        echo "<option value='{$row['User_ID']}'>{$row['FullName']} - {$row['Department_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- ปุ่มบันทึกและยกเลิก -->
            <div class="form-buttons">
                <button type="submit" class="btn-save">บันทึก</button>
                <button type="button" class="btn-cancel" onclick="window.history.back()">ยกเลิก</button>
            </div>
        </form>
    </div>
</body>
</html>
