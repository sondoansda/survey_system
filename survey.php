<?php
// survey.php
require_once "./config/db.php";
require_once "./includes/functions.php";

session_start();

// Kiểm tra session
if (!isset($_SESSION['survey_id']) || !isset($_SESSION['user_id']) || !isset($_SESSION['response_id'])) {
    header("Location: ./index.php");
    exit();
}


// Lấy thông tin từ session
$survey_id = $_SESSION['survey_id'];
$user_id = $_SESSION['user_id'];
$response_id = $_SESSION['response_id'];

// Hàm debug (giữ nguyên, giả định đã được định nghĩa trước)
function dd(...$args)
{
    // Nội dung hàm debug
}

// Lấy và validate dữ liệu từ POST
$current_question_id = isset($_POST['current_question_id']) ? (int)$_POST['current_question_id'] : null;
$selected_option_id = isset($_POST['option'])
    ? (is_array($_POST['option']) ? array_map('intval', $_POST['option']) : (int)$_POST['option'])
    : null;

// Xử lý form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && $current_question_id !== null && $selected_option_id !== null) {
    $next_question_option_id = saveAndGetNextOption($conn, $response_id, $current_question_id, $selected_option_id);
    $question = getNextQuestion($conn, $survey_id, $current_question_id, $next_question_option_id);
} else {
    // Lấy câu hỏi đầu tiên nếu không có dữ liệu POST
    $question = getNextQuestion($conn, $survey_id, null, null);
}

/**
 * Lưu câu trả lời và trả về option ID để xác định câu hỏi tiếp theo
 */
function saveAndGetNextOption($conn, $response_id, $current_question_id, $selected_option_id)
{
    if (is_array($selected_option_id)) {
        foreach ($selected_option_id as $option_id) {
            saveAnswer($conn, $response_id, $current_question_id, $option_id);
        }
        return $selected_option_id[0]; // Dùng option đầu tiên cho luồng tiếp theo
    }

    saveAnswer($conn, $response_id, $current_question_id, $selected_option_id);
    return $selected_option_id;
}
// Nếu không còn câu hỏi, đánh dấu hoàn thành và chuyển đến trang cảm ơn
if (!$question) {
    // tạo answer cho câu hỏi cuối cùng
    markSurveyCompleted($conn, $response_id);

    // Xóa session
    session_unset();
    session_destroy();

    header("Location: thank_you.php?survey_id=" . $survey_id);
    exit();
}

$survey_info = getSurveyInfo($conn, $survey_id);

include "./includes/header.php";
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><?php echo htmlspecialchars($survey_info['title']); ?></h4>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <input type="hidden" name="current_question_id" value="<?php echo $question['id']; ?>">

                        <h5 class="mb-3"><?php echo htmlspecialchars($question['content']); ?></h5>

                        <?php if ($question['question_type'] == 'multiple_choice'): ?>
                            <?php foreach ($question['options'] as $option): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="option[]" id="option_<?php echo $option['id']; ?>" value="<?php echo $option['id']; ?>">
                                    <label class="form-check-label" for="option_<?php echo $option['id']; ?>">
                                        <?php echo htmlspecialchars($option['content']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php foreach ($question['options'] as $option): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="option" id="option_<?php echo $option['id']; ?>" value="<?php echo $option['id']; ?>" required>
                                    <label class="form-check-label" for="option_<?php echo $option['id']; ?>">
                                        <?php echo htmlspecialchars($option['content']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary mt-3">Tiếp theo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "./includes/footer.php"; ?>