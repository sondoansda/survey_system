<?php
// index.php
require_once "./config/db.php";
require_once "./includes/functions.php";

// Lấy danh sách các khảo sát đang hoạt động
$sql = "SELECT * FROM surveys WHERE status = 'active'";
$result = mysqli_query($conn, $sql);

include "./includes/header.php";
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Hệ thống khảo sát</h1>
    
    <div class="row">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($survey = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($survey['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($survey['description']); ?></p>
                            <a href="verification.php?survey_id=<?php echo $survey['id']; ?>" class="btn btn-primary">Tham gia khảo sát</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    Hiện tại không có khảo sát nào đang diễn ra.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include "./includes/footer.php"; ?>
