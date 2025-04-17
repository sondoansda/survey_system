<?php
require_once './view/includes/header.php';
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

<?php require_once "./view/includes/footer.php"; ?>