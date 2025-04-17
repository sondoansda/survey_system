<?php
require_once './view/includes/header.php';
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
                            <input type="email" class="form-control" id="email" name="email" value="" required>
                            <small class="form-text text-muted">Mỗi email chỉ được tham gia khảo sát một lần.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Bắt đầu khảo sát</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "./view/includes/footer.php"; ?>