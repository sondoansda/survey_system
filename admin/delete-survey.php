<?php
require_once "../config/db.php";
session_start();

// Check if the database connection is successful
if (!$conn) {
    $_SESSION['message'] = "Lỗi: Không thể kết nối đến cơ sở dữ liệu.";
    header('Location: index.php');
    exit;
}

if (!isset($_GET['survey_id']) || empty($_GET['survey_id'])) {
    $_SESSION['message'] = "Lỗi: Không tìm thấy ID khảo sát.";
    header('Location: index.php');
    exit;
}

// Sanitize the survey ID
$survey_id = filter_var($_GET['survey_id'], FILTER_VALIDATE_INT);
if ($survey_id === false || $survey_id <= 0) {
    $_SESSION['message'] = "Lỗi: ID khảo sát không hợp lệ.";
    header('Location: index.php');
    exit;
}

mysqli_begin_transaction($conn);
try {
    // Delete related answers and options for each question
    $sql = "SELECT id FROM questions WHERE survey_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) throw new Exception("Lỗi SELECT questions: " . mysqli_error($conn));
    mysqli_stmt_bind_param($stmt, "i", $survey_id);
    mysqli_stmt_execute($stmt);
    $questions_result = mysqli_stmt_get_result($stmt);

    while ($question = mysqli_fetch_assoc($questions_result)) {
        $question_id = $question['id'];

        // Delete answers for the question
        $sql = "DELETE FROM answers WHERE question_id = ?";
        $stmt_answers = mysqli_prepare($conn, $sql);
        if (!$stmt_answers) throw new Exception("Lỗi DELETE answers: " . mysqli_error($conn));
        mysqli_stmt_bind_param($stmt_answers, "i", $question_id);
        mysqli_stmt_execute($stmt_answers);
        mysqli_stmt_close($stmt_answers);

        // Delete options for the question
        $sql = "DELETE FROM options WHERE question_id = ?";
        $stmt_options = mysqli_prepare($conn, $sql);
        if (!$stmt_options) throw new Exception("Lỗi DELETE options: " . mysqli_error($conn));
        mysqli_stmt_bind_param($stmt_options, "i", $question_id);
        mysqli_stmt_execute($stmt_options);
        mysqli_stmt_close($stmt_options);
    }
    mysqli_stmt_close($stmt);

    // Delete questions for the survey
    $sql = "DELETE FROM questions WHERE survey_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) throw new Exception("Lỗi DELETE questions: " . mysqli_error($conn));
    mysqli_stmt_bind_param($stmt, "i", $survey_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Delete responses for the survey
    $sql = "DELETE FROM responses WHERE survey_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) throw new Exception("Lỗi DELETE responses: " . mysqli_error($conn));
    mysqli_stmt_bind_param($stmt, "i", $survey_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Delete the survey itself
    $sql = "DELETE FROM surveys WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) throw new Exception("Lỗi DELETE surveys: " . mysqli_error($conn));
    mysqli_stmt_bind_param($stmt, "i", $survey_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Commit the transaction
    mysqli_commit($conn);
    $_SESSION['message'] = "Xóa khảo sát thành công!";
} catch (Exception $e) {
    // Roll back the transaction on error
    mysqli_rollback($conn);
    $_SESSION['message'] = "Có lỗi xảy ra: " . $e->getMessage();
}

// Close the database connection
mysqli_close($conn);

// Redirect back to the survey list page
header('Location: index.php');
exit;
