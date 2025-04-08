<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['error_message'] = "Vui lòng đăng nhập để truy cập trang quản trị!";
    header("Location: account/login.php");
    exit;
}
