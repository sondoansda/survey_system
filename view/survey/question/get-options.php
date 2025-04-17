<?php
// admin/get_options.php
require_once "./config/Database.php";

header('Content-Type: application/json');

$question_id = isset($_GET['question_id']) ? intval($_GET['question_id']) : 0;

if (!$question_id) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id, content FROM options WHERE question_id = ? ORDER BY order_num";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $question_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$options = [];
while ($row = mysqli_fetch_assoc($result)) {
    $options[] = [
        'id' => $row['id'],
        'content' => $row['content']
    ];
}

echo json_encode($options);
