<?php
// admin/delete_question.php
require_once "../config/db.php";

session_start();

if (!$conn) {
    $_SESSION['message'] = "Lỗi: Không thể kết nối đến cơ sở dữ liệu.";
    header('Location: index.php');
    exit;
}

if (!isset($_GET['question_id']) || empty($_GET['question_id'])) {
    $_SESSION['message'] = "Lỗi: Thiếu ID câu hỏi.";
    header('Location: index.php');
    exit;
}

$question_id = filter_var($_GET['question_id'], FILTER_VALIDATE_INT);
if ($question_id === false || $question_id <= 0) {
    header("HTTP/1.1 404 Not Found");
    $_SESSION['message'] = "Lỗi: ID câu hỏi không hợp lệ.";
    header('Location: 404.php'); // Trang lỗi 404 tùy chỉnh
    exit;
}

// Kiểm tra xem câu hỏi có tồn tại không
$sql = "SELECT survey_id FROM questions WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $question_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header("HTTP/1.1 404 Not Found");
    $_SESSION['message'] = "Lỗi: Câu hỏi không tồn tại.";
    header('Location: 404.php'); // Trang lỗi 404 tùy chỉnh
    exit;
}

$survey_id = $row['survey_id']; // Lấy survey_id để dùng cho redirect

mysqli_begin_transaction($conn);

try {
    // Xóa các câu trả lời
    $sql = "DELETE FROM answers WHERE question_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $question_id);
    mysqli_stmt_execute($stmt);

    // Xóa các tùy chọn
    $sql = "DELETE FROM options WHERE question_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $question_id);
    mysqli_stmt_execute($stmt);

    // Cập nhật câu hỏi con
    $sql = "UPDATE questions SET parent_question_id = NULL, parent_option_id = NULL WHERE parent_question_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $question_id);
    mysqli_stmt_execute($stmt);

    // Xóa câu hỏi
    $sql = "DELETE FROM questions WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $question_id);
    mysqli_stmt_execute($stmt);

    mysqli_commit($conn);
    $_SESSION['message'] = "Xóa câu hỏi thành công!";
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['message'] = "Có lỗi xảy ra: " . $e->getMessage();
}

header("Location:http://localhost:8080/survey_system/admin/edit_survey.php?id=$survey_id");
exit;
