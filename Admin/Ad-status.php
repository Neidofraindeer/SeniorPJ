<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดตามสถานะ</title>
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
        }
        .search-bar input[type="text"] {
            width: 70%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-search {
            background-color: #6495ED; /* สีน้ำเงิน */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-right: 255px;
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
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #835EB7;
            color: white;
            text-align: center;
        }
        .actions button {
            background-color: #ddd;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .actions button:hover {
            background-color: #ccc;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="form-title">
            <a onclick="document.location='Ad-mainpage.php'" class="back-link">&lt;  </a>
            <a class="head">ติดตามสถานะ</a>
        </div><br>
        <div class="search-bar">
            <input type="text" placeholder="ค้นหา...">
            <button class="btn-search">ค้นหา</button>
        </div>
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
                    <td>000001</td>
                    <td>ชื่อ... นามสกุล...</td>
                    <td>ยี่ห้อ A</td>
                    <td>กย 1111 </td>
                    <td>ช่าง...</td>
                    <td>สถานะ A</td>
                </tr>
                <tr>
                    <td>000001</td>
                    <td>ชื่อ... นามสกุล...</td>
                    <td>ยี่ห้อ A</td>
                    <td>กย 1111 </td>
                    <td>ช่าง...</td>
                    <td>สถานะ A</td>
                </tr>
                <tr>
                    <td>000001</td>
                    <td>ชื่อ... นามสกุล...</td>
                    <td>ยี่ห้อ A</td>
                    <td>กย 1111 </td>
                    <td>ช่าง...</td>
                    <td>สถานะ A</td>
                </tr>
                <tr>
                    <td>000001</td>
                    <td>ชื่อ... นามสกุล...</td>
                    <td>ยี่ห้อ A</td>
                    <td>กย 1111 </td>
                    <td>ช่าง...</td>
                    <td>สถานะ A</td>
                </tr>
                <tr>
                    <td>000001</td>
                    <td>ชื่อ... นามสกุล...</td>
                    <td>ยี่ห้อ A</td>
                    <td>กย 1111 </td>
                    <td>ช่าง...</td>
                    <td>สถานะ A</td>
                </tr>
                <tr>
                    <td>000001</td>
                    <td>ชื่อ... นามสกุล...</td>
                    <td>ยี่ห้อ A</td>
                    <td>กย 1111 </td>
                    <td>ช่าง...</td>
                    <td>สถานะ A</td>
                </tr>
                <tr>
                    <td>000001</td>
                    <td>ชื่อ... นามสกุล...</td>
                    <td>ยี่ห้อ A</td>
                    <td>กย 1111 </td>
                    <td>ช่าง...</td>
                    <td>สถานะ A</td>
                </tr>
                <tr>
                    <td>000001</td>
                    <td>ชื่อ... นามสกุล...</td>
                    <td>ยี่ห้อ A</td>
                    <td>กย 1111 </td>
                    <td>ช่าง...</td>
                    <td>สถานะ A</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
