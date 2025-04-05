<?php
// admin/edit_question.php
require_once "../config/db.php";

$question_id = isset($_GET['question_id']) ? intval($_GET['question_id']) : 0;

// Lấy thông tin câu hỏi
$question_sql = "SELECT q.*, s.title as survey_title FROM questions q JOIN surveys s ON q.survey_id = s.id WHERE q.id = ?";
$stmt = mysqli_prepare($conn, $question_sql);
mysqli_stmt_bind_param($stmt, "i", $question_id);
mysqli_stmt_execute($stmt);
$question_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($question_result) == 0) {
    header("Location: index.php");
    exit();
}
$question = mysqli_fetch_assoc($question_result);
$survey_id = $question['survey_id'];

// Xử lý form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_text = trim($_POST['question_text']);
    $question_type = in_array($_POST['question_type'], ['single_choice', 'multiple_choice']) ? $_POST['question_type'] : $question['question_type'];
    $question_order = intval($_POST['question_order']);


    if ($question_text == $question['content'] && $question_type == $question['question_type'] && $question_order == $question['order_num']) {

        header("Location: survey_questions.php?id=$survey_id");
        exit();
    }

    if (!empty($question_text)) {
        // Đảm bảo $question_type luôn hợp lệ
        $question_type = in_array($question_type, ['single_choice', 'multiple_choice']) ? $question_type : 'single_choice';

        $update_sql = "UPDATE questions SET content = ?, question_type = ?, order_num = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt, "ssii", $question_text, $question_type, $question_order, $question_id);
        mysqli_stmt_execute($stmt);

        // Lấy lại thông tin câu hỏi
        $stmt = mysqli_prepare($conn, $question_sql);
        mysqli_stmt_bind_param($stmt, "i", $question_id);
        mysqli_stmt_execute($stmt);
        $question = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        header("Location: http://localhost:8080/survey_system/admin/edit_survey.php?id=" . $question_id);
        exit();
    }
}

$question_types = [
    'single_choice' => '1 lựa chọn',
    'multiple_choice' => 'Nhiều lựa chọn'
];

include "../includes/header.php";
?>

<div class="container mt-4">
    <h2>Chỉnh sửa câu hỏi</h2>
    <a href="edit_survey.php?id=<?php echo $survey_id; ?>" class="btn btn-secondary mb-3">Quay lại</a>

    <div class="card">
        <div class="card-header">
            <strong>Khảo sát: <?php echo htmlspecialchars($question['survey_title']); ?></strong>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label>Nội dung câu hỏi <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="question_text" required><?php echo htmlspecialchars($question['content']); ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Loại câu hỏi</label>
                        <select class="form-select" name="question_type">
                            <?php foreach ($question_types as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php echo $question['question_type'] == $value ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Thứ tự</label>
                        <input type="number" class="form-control" name="question_order" value="<?php echo $question['order_num']; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" class="form-check-input" name="is_required" <?php echo $question['id'] ? 'checked' : ''; ?>>
                            <label>Bắt buộc</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </form>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>