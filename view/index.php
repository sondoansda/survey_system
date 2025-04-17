<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chu</title>
</head>

<body>

    <?php
    include "includes/header.php";
    ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Hệ thống khảo sát</h1>

        <div class="row">
            <?php if (count($surveys) > 0): ?>
                <?php foreach ($surveys as $survey): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($survey['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($survey['description']); ?></p>
                                <a href="verification.php?survey_id=<?php echo $survey['id']; ?>" class="btn btn-primary">Tham gia khảo sát</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        Hiện tại không có khảo sát nào đang diễn ra.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>
</body>

</html>