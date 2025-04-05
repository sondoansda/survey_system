<?php
// results.php
require_once "./config/db.php";
require_once "./includes/functions.php";

$survey_id = isset($_GET['survey_id']) ? intval($_GET['survey_id']) : 0;
$survey_info = getSurveyInfo($conn, $survey_id);

if (!$survey_info) {
    header("Location: ./index.php");
    exit();
}

$results = getSurveyResults($conn, $survey_id);
$conn->close();

include "./includes/header.php";
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Kết quả khảo sát: <?php echo htmlspecialchars($survey_info['title']); ?></h1>

    <?php if (empty($results)): ?>
        <div class="alert alert-info">Chưa có dữ liệu khảo sát.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($results as $index => $result): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><?php echo htmlspecialchars($result['question']); ?></h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chart<?php echo $index; ?>" width="400" height="300"></canvas>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="./index.php" class="btn btn-primary">Trở về trang chủ</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php foreach ($results as $index => $result): ?>
            var ctx<?php echo $index; ?> = document.getElementById('chart<?php echo $index; ?>');
            if (ctx<?php echo $index; ?>) {
                new Chart(ctx<?php echo $index; ?>.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: <?php echo isset($result['options']) ? json_encode($result['options']) : '[]'; ?>,
                        datasets: [{
                            label: 'Số lượng người trả lời',
                            data: <?php echo isset($result['counts']) ? json_encode($result['counts']) : '[]'; ?>,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        <?php endforeach; ?>
    });
</script>

<?php include "./includes/footer.php"; ?>