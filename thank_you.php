<?php
// thank_you.php
require_once "./config/db.php";
require_once "./includes/functions.php";

$survey_id = isset($_GET['survey_id']) ? intval($_GET['survey_id']) : 0;
$survey_info = getSurveyInfo($conn, $survey_id);

include "./includes/header.php";
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-body">
                    <h1 class="display-4">Cảm ơn bạn!</h1>
                    <p class="lead">Cảm ơn bạn đã hoàn thành khảo sát <?php echo htmlspecialchars($survey_info['title'] ?? ''); ?>.</p>
                    <hr>
                    <p>Bạn có thể xem kết quả khảo sát tại đây:</p>
                    <a href="results.php?survey_id=<?php echo $survey_id; ?>" class="btn btn-primary">Xem kết quả</a>
                    <a href="./index.php" class="btn btn-secondary ml-2">Trở về trang chủ</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "./includes/footer.php"; ?>
