<?php
require_once './model/Answer.php';
require_once __DIR__ . '/../config/Database.php';
class AnswerRepository
{
    private $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    function saveAnswer($response_id, $question_id, $option_id)
    {
        $stmt = $this->conn->prepare("INSERT INTO answers (response_id, question_id, option_id) VALUES (?, ?, ?)");
        return $stmt->execute([$response_id, $question_id, $option_id]);
    }
    public function deleteAnswersByQuestionId(int $question_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM answers WHERE question_id = ?");
        $stmt->execute([$question_id]);
        return $stmt->rowCount() > 0;
    }
}
