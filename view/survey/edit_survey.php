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

                        // Kiểm tra nếu mảng $questions tồn tại và có ít nhất một câu hỏi
                        <?php if (count($questions) > 0): ?>
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
                                        <?php foreach ($questions as $question): ?>
                                            <tr>
                                                <td><?php echo $counter++; ?></td>
                                                <td><?php echo htmlspecialchars($question->content); ?></td>
                                                <td>
                                                    <?php
                                                    echo $question->question_type == 'multiple_choice' ? 'Nhiều lựa chọn' : 'Một lựa chọn';
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($question->parent_question_id): ?>
                                                        <small>
                                                            Hiển thị khi: <strong><?php echo htmlspecialchars($question->parent_question); ?></strong>
                                                            -> chọn: <strong><?php echo htmlspecialchars($question->parent_option); ?></strong>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted">Không có</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="edit_question.php?question_id=<?php echo $question->id; ?>" class="btn btn-sm btn-primary">Sửa</a>
                                                    <a href="delete_question.php?question_id=<?php echo $question->id; ?>"
                                                        class="btn btn-sm btn-danger btn-delete-question"
                                                        onclick="return confirm('Bạn có chắc muốn xóa câu hỏi này không?');">Xóa</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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
                                        foreach ($dropdown as $q):
                                        ?>
                                            <option value="<?php echo $q->id; ?>"><?php echo htmlspecialchars($q->content); ?></option>
                                        <?php endforeach; ?>
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



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabTriggerEl = document.querySelectorAll('#surveyTab button');
            tabTriggerEl.forEach((el) => {
                el.addEventListener('click', function(event) {
                    const tabId = event.target.getAttribute('data-bs-target');
                    new bootstrap.Tab(document.querySelector(tabId)).show();
                });
            });

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
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>