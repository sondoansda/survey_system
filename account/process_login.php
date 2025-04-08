<?php
// process_login.php - Xử lý đăng nhập người dùng
session_start();
require_once "../config/db.php";
require_once "../includes/functions.php";

// Kiểm tra nếu form đã được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form và làm sạch
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Kiểm tra tài khoản trong cơ sở dữ liệu
    $sql = "SELECT * FROM admin_users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Xác thực mật khẩu
        if (password_verify($password, $user['password'])) {
            // Đăng nhập thành công
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            
            // Chuyển hướng đến trang quản trị
            header("Location: ../admin/index.php");
            exit;
        } else {
            // Mật khẩu không chính xác
            $_SESSION['error_message'] = "Mật khẩu không chính xác!";
            header("Location: login.php");
            exit;
        }
    } else {
        // Không tìm thấy tài khoản
        $_SESSION['error_message'] = "Tài khoản không tồn tại!";
        header("Location: login.php");
        exit;
    }
} else {
    // Nếu không phải phương thức POST, chuyển hướng về trang đăng nhập
    header("Location: login.php");
    exit;
}