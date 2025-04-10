<?php
// login.php - Trang đăng nhập quản trị
session_start();
require_once "../config/db.php";
require_once "../includes/functions.php";

// Nếu đã đăng nhập, chuyển hướng đến trang quản trị
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: ../admin/index.php");
    exit;
}

// Kiểm tra thông báo lỗi hoặc thông báo thành công
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';

// Xóa thông báo sau khi đã hiển thị
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Hệ thống khảo sát</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
<<<<<<< HEAD
    <link href="assets/css/style.css" rel="stylesheet">
=======
    <link href="/survey_system/assets/css/style1.css" rel="stylesheet">
>>>>>>> 33daf9af81df39af9f6806f10c9a97e4ade11c0c
    <style>
        .login-form {
            max-width: 450px;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
    </style>
<<<<<<< HEAD
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Hệ thống khảo sát</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Trang chủ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
=======
    <script>
        function checkLogin(event) {
            // Kiểm tra trạng thái đăng nhập (giả sử bạn lưu trạng thái đăng nhập trong sessionStorage)
            var isLoggedIn = sessionStorage.getItem('isLoggedIn'); // Hoặc có thể kiểm tra cookie, localStorage,...

            if (!isLoggedIn) {
                // Nếu chưa đăng nhập, hiển thị thông báo và ngừng chuyển hướng
                alert('Bạn cần đăng nhập để truy cập trang quản trị.');

                // Ngừng chuyển hướng
                event.preventDefault();
            }
        }
    </script>
</head>

<body>
    <?php include '../includes/header.php'; ?>
>>>>>>> 33daf9af81df39af9f6806f10c9a97e4ade11c0c

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="login-form mt-5">
                    <h2 class="text-center mb-4">Đăng nhập quản trị</h2>
<<<<<<< HEAD
                    
=======

>>>>>>> 33daf9af81df39af9f6806f10c9a97e4ade11c0c
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
<<<<<<< HEAD
                    
=======

>>>>>>> 33daf9af81df39af9f6806f10c9a97e4ade11c0c
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>

                    <form action="process_login.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Đăng nhập</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<<<<<<< HEAD
    <footer class="mt-5 py-3 bg-light text-center">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Hệ thống khảo sát PHP</p>
        </div>
    </footer>
=======
    <?php include '../includes/footer.php'; ?>
>>>>>>> 33daf9af81df39af9f6806f10c9a97e4ade11c0c

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>