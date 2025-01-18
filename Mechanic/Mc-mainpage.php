<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการซ่อมแซ่ม</title>
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

.status-approved {
    color: green;
    font-weight: bold;
}

.status-pending {
    color: orange;
    font-weight: bold;
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



</style>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <div class="profile">
                <img src="https://via.placeholder.com/100" alt="User Profile">
                <h3>ปัญญา ผู้นำ</h3>
            </div>
        </div>
            <ul><br>
                <li onclick="document.location='Mc-history.php'">ประวัติการซ่อมแซม</li>
                <li>การแจ้งเตือน</li>
                <li onclick="document.location='Mc-setting.php'">การตั้งค่า</li>
                <li onclick="document.location='/SeniorPJ/index.php'">ออกจากระบบ</li>
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
                    <th>รหัส</th>
                    <th>ยี่ห้อ</th>
                    <th>ทะเบียนรถ</th>
                    <th>ส่งมอบงาน</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>21-03-2567</td>
                    <td>0001</td>
                    <td>Toyota</td>
                    <td>กข 1088 ปท</td>
                    <td><button class="btn">ส่งมอบ</button></td>
                </tr>
                <tr>
                    <td>21-03-2567</td>
                    <td>0002</td>
                    <td>Mitsubishi</td>
                    <td>กข 1234 กท</td>
                    <td><button class="btn">ส่งมอบ</button></td>
                </tr>
                <tr>
                    <td>21-03-2567</td>
                    <td>0002</td>
                    <td>Mitsubishi</td>
                    <td>กข 1234 กท</td>
                    <td><button class="btn">ส่งมอบ</button></td>
                </tr><tr>
                    <td>21-03-2567</td>
                    <td>0002</td>
                    <td>Mitsubishi</td>
                    <td>กข 1234 กท</td>
                    <td><button class="btn">ส่งมอบ</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
