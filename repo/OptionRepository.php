<?php
require_once './config/Database.php';
require_once './model/Option.php';
require_once './repo/AnswerRepository.php';
class OptionRepository
{
    private $pdo;
    private $answerRepo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->connect();
        $this->answerRepo = new AnswerRepository();
    }

    public function findByQuestionId($questionId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM options WHERE question_id = ?");
        $stmt->execute([$questionId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Option');
    }
    function saveAndGetNextOption($response_id, $current_question_id, $selected_option_id)
    {
        if (is_array($selected_option_id)) {
            foreach ($selected_option_id as $option_id) {
                $this->answerRepo->saveAnswer($response_id, $current_question_id, $option_id);
            }
            return $selected_option_id[0]; // Dùng option đầu tiên cho luồng tiếp theo
        }

        $this->answerRepo->saveAnswer($response_id, $current_question_id, $selected_option_id);
        return $selected_option_id;
    }

    public function deleteByQuestionId($questionId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM options WHERE question_id = ?");
        return $stmt->execute([$questionId]);
    }
    public function deleteOptionsByQuestionId($question_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM options WHERE question_id = ?");
        $stmt->execute([$question_id]);
    }

    public function deleteQuestionsBySurveyId($survey_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM questions WHERE survey_id = ?");
        $stmt->execute([$survey_id]);
    }
}
