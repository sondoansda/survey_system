<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa câu hỏi</title>
    <!-- Bootstrap CSS nếu chưa có -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-4">
        <h2>Chỉnh sửa câu hỏi</h2>
        <a href="edit_survey.php?id=<?php echo htmlspecialchars($survey_id); ?>" class="btn btn-secondary mb-3">Quay lại</a>

        <div class="card">
            <div class="card-header">
                <strong>Khảo sát: <?php echo htmlspecialchars($question['survey_title']); ?></strong>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Nội dung câu hỏi <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="question_text" required><?php echo htmlspecialchars($question['content']); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Loại câu hỏi</label>
                            <select class="form-select" name="question_type">
                                <?php foreach ($question_types as $value => $label): ?>
                                    <option value="<?php echo htmlspecialchars($value); ?>" <?php echo ($question['question_type'] === $value) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Thứ tự</label>
                            <input type="number" class="form-control" name="question_order" value="<?php echo htmlspecialchars($question['order_num']); ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" name="is_required" id="is_required"
                                    <?php echo isset($question['is_required']) && $question['is_required'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_required">Bắt buộc</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Lưu</button>
                </form>
            </div>
        </div>
    </div>

    <?php include "./view/includes/footer.php"; ?>

    <!-- Bootstrap JS nếu chưa có -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>