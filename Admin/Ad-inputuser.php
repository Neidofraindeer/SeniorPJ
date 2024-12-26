<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลผู้ใช้งาน</title>
</head>
<style>
    /* กำหนดรูปแบบพื้นฐาน */
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f9f9f9;
}

/* กรอบฟอร์ม */
.form-container {
    max-width: 900px;
    height: 465px;
    margin: auto;
    border: 1px solid #ccc;
    padding: 20px;
    border-radius: 8px;
    background-color: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

/* กลุ่มฟอร์ม */
.form-group {
    display: flex; 
    align-items: center; 
    margin-bottom: 15px;
}

.form-group label {
    width: 25%; /* กำหนดความกว้างของ label */
    margin-right: 10px; /* เพิ่มช่องว่างระหว่าง label และ input */
    text-align: left; /* จัดข้อความให้ชิดขวา */
}

.form-group input,
.form-group select {
    flex: 1; /* ให้ input/select ขยายเต็มพื้นที่ที่เหลือ */
    padding: 8px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* ปุ่มฟอร์ม */
.form-actions {
    text-align: right;
    margin-top: 20px;
}

.form-actions button {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

/* ปุ่มบันทึก */
.form-actions .btn-save {
    background-color: #28a745;
    color: white;
    
}

/* ปุ่มยกเลิก */
.form-actions .btn-cancel {
    background-color: #dc3545;
    color: white;
    margin-left: 10px;
    
}

/* การเพิ่มเอฟเฟกต์ */
.form-actions button:hover {
    opacity: 0.9;
}
</style>
<body>
    <div class="form-title">
        <a onclick="document.location='Ad-user.php'" class="back-link">&lt; </a>
        <a class="head"> เพิ่มข้อมูลผู้ใช้งาน</a>
    </div>
    <div class="form-container">
        <form action="process.php" method="post" enctype="multipart/form-data"><br>
            <div class="form-group">
                <label for="photo">รูป:</label>
                <input type="file" id="photo" name="photo">
            </div>
            <div class="form-group">
                <label for="first_name">ชื่อ:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">นามสกุล:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="nickname">ชื่อเล่น:</label>
                <input type="text" id="nickname" name="nickname">
            </div>
            <div class="form-group">
                <label for="phone">เบอร์โทร:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="department">แผนก:</label>
                <select id="department" name="department">
                    <option value="ผู้ดูแลระบบ">ผู้ดูแลระบบ</option>
                    <option value="พนักงานออฟฟิศ">พนักงานออฟฟิศ</option>
                    <option value="เครื่องยนต์">พนักงานช่างเครื่องยนต์</option>
                    <option value="เคาะ">พนักงานช่างเคาะ</option>
                    <option value="สี">พนักงานช่างสี</option>
                    <option value="ประกอบ">พนักงานช่างประกอบ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-actions"><br>
                <button type="submit" class="btn-save">บันทึก</button>
                <button type="reset" class="btn-cancel">ยกเลิก</button>
            </div>
        </form>
    </div>
</body>
</html>
