<?php
// logout.php - Xử lý đăng xuất người dùng
session_start();

// Xóa tất cả các biến session
$_SESSION = array();

// Hủy phiên
session_destroy();

// Thiết lập thông báo thành công và chuyển hướng về trang đăng nhập
session_start();
$_SESSION['success_message'] = "Đăng xuất thành công!";
header("Location: login.php");
exit;