<?php
require_once './config/Database.php';
require_once  './model/Question.php';

class QuestionRepository
{
    private $pdo;
    private $answerRepo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->connect();
        $this->answerRepo = new AnswerRepository();
    }

    public function findBySurveyId($surveyId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM questions WHERE survey_id = ?");
        $stmt->execute([$surveyId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Question');
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchObject('Question');
    }

    public function save(Question $question)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO questions (survey_id, question_text, question_type)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $question->survey_id,
            $question->question_text,
            $question->question_type
        ]);

        return $this->pdo->lastInsertId();
    }

    public function saveAndGetNextOption($response_id, $current_question_id, $selected_option_id)
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

    // Lấy câu hỏi tiếp theo dựa trên câu trả lời trước đó
    public function getNextQuestion($survey_id, $current_question_id = null, $selected_option_id = null)
    {
        if ($current_question_id === null) {
            // Câu hỏi đầu tiên
            $sql = "SELECT q.*, o.id as option_id, o.content as option_content, o.order_num as option_order 
                FROM questions q 
                LEFT JOIN options o ON q.id = o.question_id 
                WHERE q.survey_id = ? AND q.parent_question_id IS NULL 
                ORDER BY q.order_num, o.order_num";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$survey_id]);
        } else {
            // Kiểm tra câu hỏi con dựa vào option đã chọn
            $sql = "SELECT q.*, o.id as option_id, o.content as option_content, o.order_num as option_order 
                FROM questions q 
                LEFT JOIN options o ON q.id = o.question_id 
                WHERE q.survey_id = ? AND q.parent_question_id = ? AND q.parent_option_id = ? 
                ORDER BY q.order_num, o.order_num";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$survey_id, $current_question_id, $selected_option_id]);

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) > 0) {
                $question = null;
                $options = [];
                foreach ($rows as $row) {
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
                    return $question;
                }
            }

            // Nếu không có câu hỏi con, lấy câu hỏi tiếp theo theo thứ tự
            $sql = "SELECT q.*, o.id as option_id, o.content as option_content, o.order_num as option_order 
                FROM questions q 
                LEFT JOIN options o ON q.id = o.question_id 
                WHERE q.survey_id = ? AND q.parent_question_id IS NULL AND 
                      q.order_num > (SELECT order_num FROM questions WHERE id = ?) 
                ORDER BY q.order_num, o.order_num";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$survey_id, $current_question_id]);
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) === 0) {
            return null;
        }

        $question = null;
        $options = [];
        $current_id = null;

        foreach ($rows as $row) {
            if ($current_id !== $row['id']) {
                if ($question !== null) {
                    $question['options'] = $options;
                    return $question;
                }

                $current_id = $row['id'];
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

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM questions WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function getQuestionSurveyById($question_id): ?array
    {
        $sql = "SELECT q.*, s.title as survey_title FROM questions q JOIN surveys s ON q.survey_id = s.id WHERE q.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$question_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateQuestion($question_id, $question_text, $question_type, $question_order): bool
    {

        $sql = "UPDATE questions SET content = ?, question_type = ?, order_num = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$question_text, $question_type, $question_order, $question_id]);
        return $stmt->rowCount() > 0;
    }
    public function getSurveyIdByQuestionId($question_id)
    {

        $sql = "SELECT survey_id FROM questions WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$question_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['survey_id'] : false;
    }

    public function deleteQuestionAndRelations($question_id)
    {
        $this->pdo->beginTransaction();

        try {


            // Xóa câu trả lời
            $sql = "DELETE FROM answers WHERE question_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$question_id]);

            // Xóa tùy chọn
            $sql = "DELETE FROM options WHERE question_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$question_id]);

            // Cập nhật câu hỏi con
            $sql = "UPDATE questions SET parent_question_id = NULL, parent_option_id = NULL WHERE parent_question_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$question_id]);

            // Xóa chính câu hỏi
            $sql = "DELETE FROM questions WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$question_id]);



            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    public function getQuestionsBySurveyId($survey_id)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM questions WHERE survey_id = ?");
        $stmt->execute([$survey_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $questionIds = [];
        foreach ($result as $row) {
            $questionIds[] = $row['id'];
        }

        return $questionIds;
    }
    public function deleteQuestionsBySurveyId($survey_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM questions WHERE survey_id = ?");
        $stmt->execute([$survey_id]);
        return $stmt->rowCount() > 0;
    }
}
