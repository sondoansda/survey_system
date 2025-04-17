<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị - Hệ thống khảo sát</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">
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
                    <li class="nav-item">
                        <a class="nav-link active" href="./index.php">Quản trị</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Quản lý khảo sát</h1>
            <a href="./create-survey.php" class="btn btn-success">Tạo khảo sát mới</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tiêu đề</th>
                                <th>Trạng thái</th>
                                <th>Số lượt trả lời</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($result) > 0): ?>
                                <?php foreach ($result as $survey): ?>
                                    <tr>
                                        <td><?php echo $survey['id']; ?></td>
                                        <td><?php echo htmlspecialchars($survey['title']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $survey['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                                <?php echo $survey['status'] == 'active' ? 'Đang hoạt động' : 'Không hoạt động'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $survey['responses_count']; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($survey['created_at'])); ?></td>
                                        <td>
                                            <a href="edit_survey.php?id=<?php echo $survey['id']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                                            <a href="../results.php?survey_id=<?php echo $survey['id']; ?>" class="btn btn-sm btn-info">Xem kết quả</a>
                                            <a href="delete_survey.php?survey_id=<?php echo $survey['id']; ?>" class="btn btn-sm btn-danger btn-delete">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach;  ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Không có khảo sát nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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
    <script>
        document.querySelectorAll('.btn-delete').forEach(link => {
            link.addEventListener('click', (e) => {
                if (!confirm('Bạn có muốn xóa khảo sát này không?')) {
                    e.preventDefault(); // Prevent the link from navigating if the user cancels
                }
            });
        });
    </script>
</body>

</html>