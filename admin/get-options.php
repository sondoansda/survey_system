<?php
// Bật hiển thị lỗi để debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Định nghĩa đường dẫn tới thư mục gốc
$root_path = realpath(dirname(__FILE__) . '/..');

// Include các file cần thiết
require_once $root_path . "../config/db.php";
require_once $root_path . "../auth_check.php";
require_once $root_path . "../includes/functions.php";

// Thiết lập header JSON
header('Content-Type: application/json');

// Lấy question_id từ tham số GET
$question_id = isset($_GET['question_id']) ? intval($_GET['question_id']) : 0;

// Kiểm tra question_id hợp lệ
if (!$question_id) {
    echo json_encode(['error' => 'Question ID không hợp lệ']);
    exit;
}

try {
    // Truy vấn lấy các tùy chọn
    $sql = "SELECT id, content FROM options WHERE question_id = ? ORDER BY order_num";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        throw new Exception("Lỗi chuẩn bị truy vấn: " . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt, "i", $question_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Lỗi thực thi truy vấn: " . mysqli_stmt_error($stmt));
    }
    
    $result = mysqli_stmt_get_result($stmt);
    
    // Lấy dữ liệu
    $options = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $options[] = [
            'id' => $row['id'],
            'content' => $row['content']
        ];
    }
    
    // Trả về kết quả
    echo json_encode($options);
    
} catch (Exception $e) {
    // Xử lý lỗi
    echo json_encode(['error' => $e->getMessage()]);
}