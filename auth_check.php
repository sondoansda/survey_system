<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['error_message'] = "Vui lòng đăng nhập để truy cập trang quản trị!";
    header("Location: /survey_system/account/login.php");
    exit;
}
