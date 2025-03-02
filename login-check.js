window.onload = function() {
    let isLoggedIn = localStorage.getItem("Logged_in");
    let userRole = localStorage.getItem("Role_ID");

    // ถ้าผู้ใช้ล็อกอินอยู่แล้ว, ไปที่หน้า mainpage ตาม role
    if (isLoggedIn === "true" && userRole !== null) {  // เช็คให้มั่นใจว่า userRole ไม่เป็น null
        redirectToMainpage(userRole);
    }
};

// ฟังก์ชันเปลี่ยนเส้นทางไปยังหน้า mainpage ตาม role
function redirectToMainpage(role) {
    if (role === "0") {  // ตรวจสอบเป็น string "0"
        window.location.href = "Admin/Ad-mainpage.php";
    } else if (role === "1") {  // ตรวจสอบเป็น string "1"
        window.location.href = "Office/Of-mainpage.php";
    } else if (role === "2") {  // ตรวจสอบเป็น string "2"
        window.location.href = "Mechanic/Mc-mainpage.php";
    }
}
