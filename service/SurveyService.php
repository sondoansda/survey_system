<?php
require_once './repo/SurveyRepository.php';

class SurveyService
{
    private $repo;

    public function __construct()
    {
        $this->repo = new SurveyRepository();
    }

    public function getAllSurveys()
    {
        return $this->repo->findAll();
    }
    public function getSurveyById($id)
    {
        return $this->repo->getSurveyById($id);
    }
    public function checkUserSurveyStatus($survey_id, $email, $ip)
    {
        return $this->repo->checkUserSurveyStatus($survey_id, $email, $ip);
    }
    public function markSurveyCompleted($response_id)
    {
        return $this->repo->markSurveyCompleted($response_id);
    }
    public function getSurveyInfo($survey_id)
    {
        return $this->repo->getSurveyInfo($survey_id);
    }
    public function getSurveyResults($survey_id)
    {
        return $this->repo->getSurveyResults($survey_id);
    }
    public function getList()
    {
        return $this->repo->getList();
    }
    public function update($id, $title, $description, $status)
    {
        return $this->repo->update($id, $title, $description, $status);
    }
    public function getQuestion($survey_id)
    {
        return $this->repo->getQuestions($survey_id);
    }
    public function getQuestionsDropdown($survey_id)
    {
        return $this->repo->getQuestionsDropdown($survey_id);
    }
    public function addQuestion($survey_id, $content, $type, $order_num, $parent_q, $parent_opt)
    {
        return $this->repo->addQuestion($survey_id, $content, $type, $order_num, $parent_q, $parent_opt);
    }
    public function getNextOrder($survey_id)
    {
        return $this->repo->getNextOrder($survey_id);
    }
    public function addOption($question_id, $content, $order)
    {
        return $this->repo->addOption($question_id, $content, $order);
    }
    public function deleteSurvey($survey_id)
    {
        return $this->repo->deleteSurvey($survey_id);
    }
    public function createSurvey($title, $description, $status)
    {
        return $this->repo->createSurvey($title, $description, $status);
    }
}
