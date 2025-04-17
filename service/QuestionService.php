<?php
require_once  './repo/QuestionRepository.php';

class QuestionService
{
    private $repo;

    public function __construct()
    {
        $this->repo = new QuestionRepository();
    }

    public function getQuestionsBySurvey($surveyId)
    {
        return $this->repo->findBySurveyId($surveyId);
    }

    public function getQuestionById($id)
    {
        return $this->repo->findById($id);
    }

    public function createQuestion($data)
    {
        $question = new Question();
        $question->survey_id = $data['survey_id'];
        $question->question_text = $data['question_text'];
        $question->question_type = $data['question_type'];

        return $this->repo->save($question);
    }
    public function getNextQuestion($conn, $survey_id, $current_question_id = null, $selected_option_id = null)
    {
        return $this->repo->getNextQuestion($conn, $survey_id, $current_question_id, $selected_option_id);
    }
    public function deleteQuestion($id)
    {
        return $this->repo->delete($id);
    }
    public function getQuestionSurveyById($question_id)
    {
        return $this->repo->getQuestionSurveyById($question_id);
    }
    public function updateQuestion($question_id, $question_text, $question_type, $question_order): bool
    {
        return $this->repo->updateQuestion($question_id, $question_text, $question_type, $question_order);
    }
    public function deleteQuestionAndRelations($question_id)
    {
        return $this->repo->deleteQuestionAndRelations($question_id);
    }
    public function getSurveyIdByQuestionId($question_id)
    {
        return $this->repo->getSurveyIdByQuestionId($question_id);
    }
    public function deleteQuestionsBySurveyId($survey_id)
    {
        return $this->repo->deleteQuestionsBySurveyId($survey_id);
    }
    public function getQuestionsBySurveyId($survey_id)
    {
        $result = $this->repo->findBySurveyId($survey_id);
        return $result;
    }
}
