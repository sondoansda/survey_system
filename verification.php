<?php
// verification.php
require_once "./config/db.php";
require_once "./includes/functions.php";

$survey_id = isset($_GET['survey_id']) ? intval($_GET['survey_id']) : 0;

// Kiểm tra khảo sát có tồn tại không
$survey_info = getSurveyInfo($conn, $survey_id);
if (!$survey_info || $survey_info['status'] !== 'active') {
    header("Location: ./index.php");
    exit();
}

$error = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $ip = getUserIP();
    
    // Kiểm tra email có hợp lệ không
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Vui lòng nhập địa chỉ email hợp lệ.";
    } else {
        // Kiểm tra người dùng đã làm khảo sát chưa
        $status = checkUserSurveyStatus($conn, $survey_id, $email, $ip);
        
        if ($status['user_exists'] && $status['response_id'] && $status['completed']) {
            $error = "Bạn đã hoàn thành khảo sát này rồi.";
        } else {
            // Tạo người dùng mới nếu chưa tồn tại
            $user_id = $status['user_exists'] ? $status['user_id'] : createUser($conn, $email, $ip);
            
            // Tạo response mới
            $response_id = $status['response_id'] ?? createResponse($conn, $survey_id, $user_id);
            
            if ($response_id) {
                // Lưu thông tin vào session
                session_start();
                $_SESSION['survey_id'] = $survey_id;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['response_id'] = $response_id;
                
                // Chuyển đến trang khảo sát
                header("Location: ./survey.php");
                exit();
            } else {
                $error = "Có lỗi xảy ra. Vui lòng thử lại.";
            }
        }
    }
}

include "./includes/header.php";
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4><?php echo htmlspecialchars($survey_info['title']); ?></h4>
                </div>
                <div class="card-body">
                    <p><?php echo htmlspecialchars($survey_info['description']); ?></p>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="post" action="">
                        <div class="form-group mb-3">
                            <label for="email">Email của bạn:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                            <small class="form-text text-muted">Mỗi email chỉ được tham gia khảo sát một lần.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Bắt đầu khảo sát</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "./includes/footer.php"; ?>
