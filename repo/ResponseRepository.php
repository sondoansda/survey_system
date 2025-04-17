<?php
require_once  './model/Response.php';

class ResponseRepository
{
    private $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function findResponse(int $survey_id, int $user_id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM responses WHERE survey_id = ? AND user_id = ?");
        $stmt->execute([$survey_id, $user_id]);
        $response = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($response) {
            return $response;
        }
        return null;
    }
    function createResponse($survey_id, $user_id)
    {
        $sql = "INSERT INTO responses (survey_id, user_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$survey_id, $user_id]);
        $lastInsertId = $this->conn->lastInsertId();
        if ($lastInsertId > 0) {
            return $lastInsertId;
        }

        return false;
    }
    public function deleteResponsesBySurveyId($survey_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM responses WHERE survey_id = ?");
        $stmt->execute([$survey_id]);
        return $stmt->rowCount() > 0;
    }
}
