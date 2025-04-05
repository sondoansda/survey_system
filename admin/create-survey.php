<?php
// admin/create_survey.php
require_once "../config/db.php";
require_once "../includes/functions.php";

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];
    
    // Tạo khảo sát mới
    $sql = "INSERT INTO surveys (title, description, status) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $title, $description, $status);
    
    if (mysqli_stmt_execute($stmt)) {
        $survey_id = mysqli_insert_id($conn);
        $message = '<div class="alert alert-success">Tạo khảo sát thành công!</div>';
        
        // Chuyển đến trang chỉnh sửa để thêm câu hỏi
        header("Location: edit_survey.php?id=" . $survey_id);
        exit();
    } else {
        $message = '<div class="alert alert-danger">Có lỗi xảy ra: ' . mysqli_error($conn) . '</div>';
    }
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo khảo sát mới - Hệ thống khảo sát</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="./assets/css/style.css" rel="stylesheet">
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
                        <a class="nav-link" href="./index.php">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin/index.php">Quản trị</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./index.php">Quản trị</a></li>
                <li class="breadcrumb-item active">Tạo khảo sát mới</li>
            </ol>
        </nav>
        
        <h1 class="mb-4">Tạo khảo sát mới</h1>
        
        <?php echo $message; ?>
        
        <div class="card">
            <div class="card-body">
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề khảo sát:</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả:</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái:</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Không hoạt động</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Tạo khảo sát</button>
                    <a href="./index.php" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>

    <footer class="mt-5 py-3 bg-light text-center">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Hệ thống khảo sát PHP</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
