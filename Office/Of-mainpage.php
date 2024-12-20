<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการมอบหมายงาน</title>
    
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
    text-align: left;
    padding: 10px;
}

th {
    background-color:  #835eb7;
    color: white;
}

td {
    font-size: 14px;
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
</style>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <div class="profile">
                <img src="https://via.placeholder.com/100" alt="User Profile">
                <h3>ปัญญา ผู้นำ</h3>
            </div>
            <ul><br>
                <li>การแจ้งเตือน</li>
                <li>ข้อมูลผู้ใช้งาน</li>
                <li>ติดตามสถานะ</li>
                <li>ประวัติการซ่อมแซม</li>
            </ul>
        </div>
            <ul><br>
                <li>การตั้งค่า</li>
                <li>ออกจากระบบ</li>
            </ul>
    </div>

    <!-- Content -->
    <div class="content">
        <h1>รายการมอบหมายงาน</h1>

        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" placeholder="ค้นหา...">
            <button class="btn-search">ค้นหา</button>
            <button class="btn-add" onclick="document.location='Of-inputcar.html'">เพิ่มรายการ</button>
        </div>
        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>รหัส</th>
                    <th>ยี่ห้อ</th>
                    <th>ทะเบียนรถ</th>
                    <th>พนักงานช่าง</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>21-03-2567</td>
                    <td>0001</td>
                    <td>Toyota</td>
                    <td>กข 1088 ปท</td>
                    <td>ช่าง A</td>
                    <td><div class="status-container">
                        <span class="status-approved">อนุมัติ</span>
                        <label for="option1" class="label-options">...</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>21-03-2567</td>
                    <td>0002</td>
                    <td>Mitsubishi</td>
                    <td>กข 1234 กท</td>
                    <td>ช่าง B</td>
                    <td><div class="status-container">
                        <span class="status-approved">อนุมัติ</span>
                        <label for="option1" class="label-options">...</label>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
