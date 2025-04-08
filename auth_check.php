<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['error_message'] = "Vui lòng đăng nhập để truy cập trang quản trị!";
<<<<<<< HEAD
    header("Location: account/login.php");
=======
    header("Location: /survey_system/account/login.php");
>>>>>>> 33daf9af81df39af9f6806f10c9a97e4ade11c0c
    exit;
}
