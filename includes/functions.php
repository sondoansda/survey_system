<?php
// includes/functions.php

// Lấy địa chỉ IP của người dùng
function getUserIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// Kiểm tra xem người dùng đã làm khảo sát chưa
function checkUserSurveyStatus($conn, $survey_id, $email, $ip)
{
    // Kiểm tra email
    $sql = "SELECT u.id, r.id as response_id, r.completed 
            FROM users u 
            LEFT JOIN responses r ON u.id = r.user_id AND r.survey_id = ?
            WHERE u.email = ? OR u.ip_address = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $survey_id, $email, $ip);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return [
            'user_exists' => true,
            'user_id' => $row['id'],
            'response_id' => $row['response_id'],
            'completed' => $row['completed']
        ];
    }

    return ['user_exists' => false];
}

// Tạo người dùng mới
function createUser($conn, $email, $ip)
{
    $sql = "INSERT INTO users (email, ip_address) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $ip);

    if (mysqli_stmt_execute($stmt)) {
        return mysqli_insert_id($conn);
    }

    return false;
}

// Tạo response mới
function createResponse($conn, $survey_id, $user_id)
{
    $sql = "INSERT INTO responses (survey_id, user_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $survey_id, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        return mysqli_insert_id($conn);
    }

    return false;
}

// Lưu câu trả lời
function saveAnswer($conn, $response_id, $question_id, $option_id)
{
    $sql = "INSERT INTO answers (response_id, question_id, option_id) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $response_id, $question_id, $option_id);

    return mysqli_stmt_execute($stmt);
}

// Đánh dấu khảo sát đã hoàn thành
function markSurveyCompleted($conn, $response_id)
{
    $sql = "UPDATE responses SET completed = TRUE WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $response_id);

    return mysqli_stmt_execute($stmt);
}

// Lấy câu hỏi tiếp theo dựa trên câu trả lời trước đó
function getNextQuestion($conn, $survey_id, $current_question_id = null, $selected_option_id = null)
{
    // Nếu không có câu hỏi hiện tại, lấy câu hỏi đầu tiên của survey
    if ($current_question_id === null) {
        $sql = "SELECT q.*, o.id as option_id, o.content as option_content, o.order_num as option_order 
                FROM questions q 
                LEFT JOIN options o ON q.id = o.question_id 
                WHERE q.survey_id = ? AND q.parent_question_id IS NULL 
                ORDER BY q.order_num, o.order_num";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $survey_id);
    } else {
        // Đầu tiên, kiểm tra xem có câu hỏi con nào dựa trên câu trả lời đã chọn không
        $sql = "SELECT q.*, o.id as option_id, o.content as option_content, o.order_num as option_order 
                FROM questions q 
                LEFT JOIN options o ON q.id = o.question_id 
                WHERE q.survey_id = ? AND q.parent_question_id = ? AND q.parent_option_id = ? 
                ORDER BY q.order_num, o.order_num";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $survey_id, $current_question_id, $selected_option_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Nếu có câu hỏi con, trả về câu hỏi con
        if (mysqli_num_rows($result) > 0) {
            $question = null;
            $options = [];
            while ($row = mysqli_fetch_assoc($result)) {
                if ($question === null) {
                    $question = [
                        'id' => $row['id'],
                        'content' => $row['content'],
                        'question_type' => $row['question_type'],
                        'order_num' => $row['order_num']
                    ];
                }
                if ($row['option_id']) {
                    $options[] = [
                        'id' => $row['option_id'],
                        'content' => $row['option_content'],
                        'order_num' => $row['option_order']
                    ];
                }
            }
            if ($question) {
                $question['options'] = $options;
            }
            return $question;
        }

        // Nếu không có câu hỏi con, lấy câu hỏi tiếp theo theo thứ tự
        $sql = "SELECT q.*, o.id as option_id, o.content as option_content, o.order_num as option_order 
                FROM questions q 
                LEFT JOIN options o ON q.id = o.question_id 
                WHERE q.survey_id = ? AND q.parent_question_id IS NULL AND 
                      q.order_num > (SELECT order_num FROM questions WHERE id = ?) 
                ORDER BY q.order_num, o.order_num";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $survey_id, $current_question_id);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Không có câu hỏi nào tiếp theo
    if (mysqli_num_rows($result) == 0) {
        return null;
    }

    $question = null;
    $options = [];
    $current_question_id = null;

    while ($row = mysqli_fetch_assoc($result)) {
        if ($current_question_id != $row['id']) {
            // Nếu đã có một câu hỏi trước đó, trả về câu hỏi đó
            if ($question !== null) {
                $question['options'] = $options;
                return $question;
            }

            $current_question_id = $row['id'];
            $question = [
                'id' => $row['id'],
                'content' => $row['content'],
                'question_type' => $row['question_type'],
                'order_num' => $row['order_num']
            ];
            $options = [];
        }

        if ($row['option_id']) {
            $options[] = [
                'id' => $row['option_id'],
                'content' => $row['option_content'],
                'order_num' => $row['option_order']
            ];
        }
    }

    // Trả về câu hỏi cuối cùng
    if ($question) {
        $question['options'] = $options;
    }

    return $question;
}

// Lấy thông tin khảo sát
function getSurveyInfo($conn, $survey_id)
{
    $sql = "SELECT * FROM surveys WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $survey_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

// Lấy dữ liệu kết quả khảo sát
function getSurveyResults($conn, $survey_id)
{
    $results = [];

    // Lấy tất cả các câu hỏi trong khảo sát
    $sql = "SELECT id, content FROM questions WHERE survey_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $survey_id);
    mysqli_stmt_execute($stmt);
    $questions_result = mysqli_stmt_get_result($stmt);

    while ($question = mysqli_fetch_assoc($questions_result)) {
        $question_id = $question['id'];
        $question_content = $question['content'];

        // Lấy tất cả các lựa chọn cho câu hỏi
        $sql = "SELECT o.id, o.content, COUNT(a.id) as count
                FROM options o
                LEFT JOIN answers a ON o.id = a.option_id
                LEFT JOIN responses r ON a.response_id = r.id
                WHERE o.question_id = ? AND (r.completed = TRUE OR r.completed IS NULL)
                GROUP BY o.id
                ORDER BY o.order_num";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $question_id);
        mysqli_stmt_execute($stmt);
        $options_result = mysqli_stmt_get_result($stmt);

        $options = [];
        $counts = [];

        while ($option = mysqli_fetch_assoc($options_result)) {
            $options[] = $option['content'];
            $counts[] = (int)$option['count'];
        }

        $results[] = [
            'question' => $question_content,
            'options' => $options,
            'counts' => $counts
        ];
    }

    return $results;
}
