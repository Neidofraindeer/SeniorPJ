<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการมอบหมายงาน</title>
    <link rel="stylesheet" href="style/stylemain.css">
</head>
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
                <li>ข้อมูลพนักงานช่าง</li>
                <li>ติดตามผล</li>
                <li>รายงานอนุมัติ</li>
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
            <button class="btn-add" onclick="document.location='inputcar.html'">เพิ่มรายการ</button>
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
