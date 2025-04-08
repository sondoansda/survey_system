<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống khảo sát</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Hệ thống khảo sát</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/survey_system/index.php">Trang chủ</a>
                    </li>
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <!-- Nếu đã đăng nhập thì hiển thị link đến trang quản trị -->
                        <li class="nav-item">
                            <a class="nav-link" href="/survey_system/admin/index.php">Quản trị</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/survey_system/account/logout.php">Đăng xuất (<?php echo htmlspecialchars($_SESSION['admin_username']); ?>)</a>
                        </li>
                    <?php else: ?>
                        <!-- Nếu chưa đăng nhập thì hiển thị link đến trang đăng nhập -->
                        <li class="nav-item">
                            <a class="nav-link" href="/survey_system/account/login.php">Quản trị</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>