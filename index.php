<?php
// index.php
session_start(); // Thêm phiên cho toàn bộ trang web
require_once "./config/db.php";
require_once "./includes/functions.php";

// Lấy danh sách các khảo sát đang hoạt động
$sql = "SELECT * FROM surveys WHERE status = 'active'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<<<<<<< HEAD
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống khảo sát</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="./assets/css/style1.css" rel="stylesheet">
</head>
<body>
    <!-- Thêm thanh điều hướng -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Hệ thống khảo sát</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Trang chủ</a>
                    </li>
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <!-- Nếu đã đăng nhập thì hiển thị link đến trang quản trị -->
                        <li class="nav-item">
                            <a class="nav-link" href="admin/index.php">Quản trị</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="account/logout.php">Đăng xuất (<?php echo htmlspecialchars($_SESSION['admin_username']); ?>)</a>
                        </li>
                    <?php else: ?>
                        <!-- Nếu chưa đăng nhập thì hiển thị link đến trang đăng nhập -->
                        <li class="nav-item">
                            <a class="nav-link" href="account/login.php">Quản trị</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

=======

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống khảo sát</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="./assets/css/style1.css" rel="stylesheet">

</head>

<body>
    <!-- Thêm thanh điều hướng -->
    <?php include './includes/header.php'; ?>

>>>>>>> 33daf9af81df39af9f6806f10c9a97e4ade11c0c
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Hệ thống khảo sát</h1>
        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($survey = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($survey['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($survey['description']); ?></p>
                                <a href="verification.php?survey_id=<?php echo $survey['id']; ?>" class="btn btn-primary">Tham gia khảo sát</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        Hiện tại không có khảo sát nào đang diễn ra.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

<<<<<<< HEAD
    <footer class="mt-5 py-3 bg-light text-center">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Hệ thống khảo sát PHP</p>
        </div>
    </footer>
=======
    <?php @include './includes/footer.php'; ?>
>>>>>>> 33daf9af81df39af9f6806f10c9a97e4ade11c0c

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<<<<<<< HEAD
=======

>>>>>>> 33daf9af81df39af9f6806f10c9a97e4ade11c0c
</html>