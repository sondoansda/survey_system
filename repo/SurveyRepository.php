<?php
require_once  './config/Database.php';

require_once './model/UserModel.php';
require_once './model/Survey.php';
require_once './repo/QuestionRepository.php';
require_once './repo/OptionRepository.php';
require_once './repo/ResponseRepository.php';

class SurveyRepository
{
    private $conn;


    public function __construct()
    {
        $db = new Database();

        $this->conn = $db->connect();
    }
    public function findAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM surveys");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSurveyById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM surveys WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkUserSurveyStatus($survey_id, $email, $ip)
    {

        $sql = "SELECT u.id, r.id as response_id, r.completed 
                FROM users u 
                LEFT JOIN responses r ON u.id = r.user_id AND r.survey_id = ?
                WHERE u.email = ? OR u.ip_address = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$survey_id, $email, $ip]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = count($result) > 0 ? $result : null;
        if ($result) {
            $user_id = $result[0]['id'];
            $response_id = $result[0]['response_id'];
            $completed = $result[0]['completed'];

            // Nếu đã hoàn thành khảo sát
            if ($completed) {
                return ['user_exists' => true, 'completed' => true, 'user_id' => $user_id, 'response_id' => $response_id];
            } else {
                return ['user_exists' => true, 'completed' => false, 'user_id' => $user_id, 'response_id' => $response_id];
            }
        }

        return ['user_exists' => false];
    }

    function markSurveyCompleted($response_id)
    {
        $sql = "UPDATE responses SET completed = TRUE WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$response_id]);

        return $stmt->rowCount() > 0;
    }
    function getSurveyInfo($survey_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM surveys WHERE id = ?");
        $stmt->execute([$survey_id]);
        $survey = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($survey) {
            return $survey;
        }
    }

    // Lấy dữ liệu kết quả khảo sát
    function getSurveyResults($survey_id)
    {
        $results = [];
        // Lấy tất cả các câu hỏi trong khảo sát
        $sql = "SELECT id, content FROM questions WHERE survey_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$survey_id]);
        $questions_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($questions_result as $question) {
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

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$question_id]);
            $options_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $options = [];
            $counts = [];

            foreach ($options_result as $option) {
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
    public function getList()
    {
        // Danh sách các khảo sát
        $sql = "SELECT s.*, 
        (SELECT COUNT(DISTINCT r.user_id) FROM responses r WHERE r.survey_id = s.id AND r.completed = TRUE) as responses_count 
        FROM surveys s 
        ORDER BY s.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update($id, $title, $description, $status)
    {

        $sql = "UPDATE surveys SET title = ?, description = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$title, $description, $status, $id]);
        return $stmt->rowCount() > 0;
    }
    public function getQuestions($survey_id)
    {
        $sql = "SELECT q.*, 
                       (SELECT content FROM questions WHERE id = q.parent_question_id) as parent_question,
                       (SELECT content FROM options WHERE id = q.parent_option_id) as parent_option
                FROM questions q
                WHERE q.survey_id = ?
                ORDER BY q.order_num";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$survey_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuestionsDropdown($survey_id)
    {
        $sql = "SELECT id, content FROM questions WHERE survey_id = ? order by order_num";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$survey_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addQuestion($survey_id, $content, $type, $order_num, $parent_q, $parent_opt)
    {
        $sql = "INSERT INTO questions (survey_id, content, question_type, order_num, parent_question_id, parent_option_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$survey_id, $content, $type, $order_num, $parent_q, $parent_opt]);
        if ($stmt->rowCount() > 0) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getNextOrder($survey_id)
    {
        $sql = "SELECT MAX(order_num) as max_order FROM questions WHERE survey_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$survey_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row['max_order'] ?? 0) + 1;
    }

    public function addOption($question_id, $content, $order)
    {
        $sql = "INSERT INTO options (question_id, content, order_num) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$question_id, $content, $order]);
    }
    public function deleteSurvey($survey_id): bool
    {
        $sql = "DELETE FROM surveys WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$survey_id]);
        return $stmt->rowCount() > 0;
    }
    public function createSurvey($title, $description, $status)
    {
        $stmt = $this->conn->prepare("INSERT INTO surveys (title, description, status) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $status]);
        $lastInsertId = $this->conn->lastInsertId();
        if ($lastInsertId > 0) {
            return $lastInsertId;
        }

        return false; // nếu có lỗi xảy ra
    }
}
