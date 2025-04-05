<?php
// admin/edit_survey.php
require_once "../config/db.php";
require_once "../includes/functions.php";

$message = '';
$survey_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin khảo sát
$survey_sql = "SELECT * FROM surveys WHERE id = ?";
$stmt = mysqli_prepare($conn, $survey_sql);
mysqli_stmt_bind_param($stmt, "i", $survey_id);
mysqli_stmt_execute($stmt);
$survey_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($survey_result) == 0) {
    header("Location: index.php");
    exit();
}

$survey = mysqli_fetch_assoc($survey_result);

// Cập nhật thông tin khảo sát
if (isset($_POST['update_survey'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    $update_sql = "UPDATE surveys SET title = ?, description = ?, status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $status, $survey_id);

    if (mysqli_stmt_execute($stmt)) {
        $message = '<div class="alert alert-success">Cập nhật khảo sát thành công!</div>';
        // Cập nhật thông tin khảo sát hiện tại
        $survey['title'] = $title;
        $survey['description'] = $description;
        $survey['status'] = $status;
    } else {
        $message = '<div class="alert alert-danger">Có lỗi xảy ra: ' . mysqli_error($conn) . '</div>';
    }
}

// Thêm câu hỏi mới
if (isset($_POST['add_question'])) {
    $question_content = trim($_POST['question_content']);
    $question_type = $_POST['question_type'];
    $parent_question_id = !empty($_POST['parent_question_id']) ? intval($_POST['parent_question_id']) : null;
    $parent_option_id = !empty($_POST['parent_option_id']) ? intval($_POST['parent_option_id']) : null;

    // Lấy order_num lớn nhất hiện tại
    $order_sql = "SELECT MAX(order_num) as max_order FROM questions WHERE survey_id = ?";
    $stmt = mysqli_prepare($conn, $order_sql);
    mysqli_stmt_bind_param($stmt, "i", $survey_id);
    mysqli_stmt_execute($stmt);
    $order_result = mysqli_stmt_get_result($stmt);
    $order_row = mysqli_fetch_assoc($order_result);
    $order_num = ($order_row['max_order'] ?? 0) + 1;

    // Thêm câu hỏi
    $insert_sql = "INSERT INTO questions (survey_id, content, question_type, order_num, parent_question_id, parent_option_id) 
                   VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($stmt, "issiii", $survey_id, $question_content, $question_type, $order_num, $parent_question_id, $parent_option_id);

    if (mysqli_stmt_execute($stmt)) {
        $question_id = mysqli_insert_id($conn);

        // Thêm các tùy chọn
        $options = $_POST['options'];
        foreach ($options as $index => $option_content) {
            if (!empty(trim($option_content))) {
                $option_sql = "INSERT INTO options (question_id, content, order_num) VALUES (?, ?, ?)";
                $option_stmt = mysqli_prepare($conn, $option_sql);
                mysqli_stmt_bind_param($option_stmt, "isi", $question_id, $option_content, $index);
                mysqli_stmt_execute($option_stmt);
            }
        }

        $message = '<div class="alert alert-success">Thêm câu hỏi thành công!</div>';
    } else {
        $message = '<div class="alert alert-danger">Có lỗi xảy ra: ' . mysqli_error($conn) . '</div>';
    }
}

// Lấy danh sách câu hỏi
$questions_sql = "SELECT q.*, 
                 (SELECT content FROM questions WHERE id = q.parent_question_id) as parent_question,
                 (SELECT content FROM options WHERE id = q.parent_option_id) as parent_option
                 FROM questions q
                 WHERE q.survey_id = ?
                 ORDER BY q.order_num";
$stmt = mysqli_prepare($conn, $questions_sql);
mysqli_stmt_bind_param($stmt, "i", $survey_id);
mysqli_stmt_execute($stmt);
$questions_result = mysqli_stmt_get_result($stmt);

// Lấy danh sách câu hỏi để hiển thị trong dropdown
$questions_dropdown_sql = "SELECT id, content FROM questions WHERE survey_id = ?";
$stmt = mysqli_prepare($conn, $questions_dropdown_sql);
mysqli_stmt_bind_param($stmt, "i", $survey_id);
mysqli_stmt_execute($stmt);
$questions_dropdown = mysqli_stmt_get_result($stmt);
//
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa khảo sát - Hệ thống khảo sát</title>
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
                        <a class="nav-link" href="index.php">Quản trị</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa khảo sát</li>
            </ol>
        </nav>

        <h1 class="mb-4">Chỉnh sửa khảo sát</h1>

        <?php echo $message; ?>

        <ul class="nav nav-tabs mb-4" id="surveyTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Thông tin chung</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="questions-tab" data-bs-toggle="tab" data-bs-target="#questions" type="button" role="tab">Câu hỏi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="add-question-tab" data-bs-toggle="tab" data-bs-target="#add-question" type="button" role="tab">Thêm câu hỏi</button>
            </li>
        </ul>

        <div class="tab-content" id="surveyTabContent">
            <!-- Tab thông tin chung -->
            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề khảo sát:</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($survey['title']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả:</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($survey['description']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái:</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" <?php echo $survey['status'] == 'active' ? 'selected' : ''; ?>>Hoạt động</option>
                                    <option value="inactive" <?php echo $survey['status'] == 'inactive' ? 'selected' : ''; ?>>Không hoạt động</option>
                                </select>
                            </div>

                            <button type="submit" name="update_survey" class="btn btn-primary">Cập nhật</button>
                            <a href="index.php" class="btn btn-secondary">Quay lại</a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab danh sách câu hỏi -->
            <div class="tab-pane fade" id="questions" role="tabpanel" aria-labelledby="questions-tab">
                <div class="card">
                    <div class="card-body">
                        <?php if (mysqli_num_rows($questions_result) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Nội dung</th>
                                            <th>Loại</th>
                                            <th>Điều kiện</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter = 1; ?>
                                        <?php while ($question = mysqli_fetch_assoc($questions_result)): ?>
                                            <tr>
                                                <td><?php echo $counter++; ?></td>
                                                <td><?php echo htmlspecialchars($question['content']); ?></td>
                                                <td>
                                                    <?php echo $question['question_type'] == 'multiple_choice' ? 'Nhiều lựa chọn' : 'Một lựa chọn'; ?>
                                                </td>
                                                <td>
                                                    <?php if ($question['parent_question_id']): ?>
                                                        <small>
                                                            Hiển thị khi: <strong><?php echo htmlspecialchars($question['parent_question']); ?></strong>
                                                            -> chọn: <strong><?php echo htmlspecialchars($question['parent_option']); ?></strong>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted">Không có</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="edit_question.php?question_id=<?php echo $question['id']; ?>" class="btn btn-sm btn-primary">Sửa</a>

                                                    <a href="delete-question.php?question_id=<?php echo $question['id']; ?>"
                                                        class="btn btn-sm btn-danger btn-delete-question"
                                                        onclick="return confirm('Bạn có chắc muốn xóa câu hỏi này không?');">Xóa</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Chưa có câu hỏi nào. Hãy thêm câu hỏi mới.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tab thêm câu hỏi -->
            <div class="tab-pane fade" id="add-question" role="tabpanel" aria-labelledby="add-question-tab">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="" id="questionForm">
                            <div class="mb-3">
                                <label for="question_content" class="form-label">Nội dung câu hỏi:</label>
                                <input type="text" class="form-control" id="question_content" name="question_content" required>
                            </div>

                            <div class="mb-3">
                                <label for="question_type" class="form-label">Loại câu hỏi:</label>
                                <select class="form-select" id="question_type" name="question_type">
                                    <option value="single_choice">Một lựa chọn</option>
                                    <option value="multiple_choice">Nhiều lựa chọn</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="has_condition" name="has_condition">
                                    <label class="form-check-label" for="has_condition">
                                        Hiển thị dựa trên câu trả lời trước đó
                                    </label>
                                </div>
                            </div>

                            <div id="conditionFields" style="display: none;">
                                <div class="mb-3">
                                    <label for="parent_question_id" class="form-label">Câu hỏi điều kiện:</label>
                                    <select class="form-select" id="parent_question_id" name="parent_question_id">
                                        <option value="">Chọn câu hỏi</option>
                                        <?php
                                        mysqli_data_seek($questions_dropdown, 0);
                                        while ($q = mysqli_fetch_assoc($questions_dropdown)):
                                        ?>
                                            <option value="<?php echo $q['id']; ?>"><?php echo htmlspecialchars($q['content']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="parent_option_id" class="form-label">Lựa chọn điều kiện:</label>
                                    <select class="form-select" id="parent_option_id" name="parent_option_id" disabled>
                                        <option value="">Chọn lựa chọn</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Các lựa chọn:</label>
                                <div id="optionsContainer">
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="options[]" placeholder="Lựa chọn 1" required>
                                        <button type="button" class="btn btn-outline-secondary btn-remove-option" disabled>Xóa</button>
                                    </div>
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="options[]" placeholder="Lựa chọn 2" required>
                                        <button type="button" class="btn btn-outline-secondary btn-remove-option" disabled>Xóa</button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btnAddOption">+ Thêm lựa chọn</button>
                            </div>

                            <button type="submit" name="add_question" class="btn btn-primary">Thêm câu hỏi</button>
                        </form>
                    </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Hiển thị/ẩn các trường điều kiện
            const hasConditionCheckbox = document.getElementById('has_condition');
            const conditionFields = document.getElementById('conditionFields');

            hasConditionCheckbox.addEventListener('change', function() {
                conditionFields.style.display = this.checked ? 'block' : 'none';
            });

            // Xử lý thêm lựa chọn
            const btnAddOption = document.getElementById('btnAddOption');
            const optionsContainer = document.getElementById('optionsContainer');

            btnAddOption.addEventListener('click', function() {
                const optionCount = optionsContainer.children.length + 1;
                const newOption = document.createElement('div');
                newOption.className = 'input-group mb-2';
                newOption.innerHTML = `
                    <input type="text" class="form-control" name="options[]" placeholder="Lựa chọn ${optionCount}" required>
                    <button type="button" class="btn btn-outline-secondary btn-remove-option">Xóa</button>
                `;
                optionsContainer.appendChild(newOption);

                // Xử lý sự kiện xóa lựa chọn
                const btnRemove = newOption.querySelector('.btn-remove-option');
                btnRemove.addEventListener('click', function() {
                    optionsContainer.removeChild(newOption);
                    updateOptionPlaceholders();
                });
            });

            // Cập nhật placeholders
            function updateOptionPlaceholders() {
                const options = optionsContainer.querySelectorAll('.input-group');
                options.forEach((option, index) => {
                    const input = option.querySelector('input');
                    input.placeholder = `Lựa chọn ${index + 1}`;
                });
            }



            // Xử lý lấy các tùy chọn cho câu hỏi điều kiện
            const parentQuestionSelect = document.getElementById('parent_question_id');
            const parentOptionSelect = document.getElementById('parent_option_id');

            parentQuestionSelect.addEventListener('change', function() {
                const questionId = this.value;

                if (questionId) {
                    // Lấy danh sách các tùy chọn của câu hỏi được chọn
                    fetch(`get_options.php?question_id=${questionId}`)
                        .then(response => response.json())
                        .then(data => {
                            parentOptionSelect.innerHTML = '<option value="">Chọn lựa chọn</option>';

                            data.forEach(option => {
                                const optionElement = document.createElement('option');
                                optionElement.value = option.id;
                                optionElement.textContent = option.content;
                                parentOptionSelect.appendChild(optionElement);
                            });

                            parentOptionSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Có lỗi xảy ra khi lấy danh sách tùy chọn.');
                        });
                } else {
                    parentOptionSelect.innerHTML = '<option value="">Chọn lựa chọn</option>';
                    parentOptionSelect.disabled = true;
                }
            });
        });
    </script>
</body>

</html>