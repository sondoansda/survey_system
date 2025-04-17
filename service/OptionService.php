<?php
require_once './repo/OptionRepository.php';

class OptionService
{
    private $repo;

    public function __construct()
    {
        $this->repo = new OptionRepository();
    }

    public function getOptionsByQuestion($questionId)
    {
        return $this->repo->findByQuestionId($questionId);
    }

    public function saveAndGetNextOption($response_id, $current_question_id, $selected_option_id)
    {
        return $this->repo->saveAndGetNextOption($response_id, $current_question_id, $selected_option_id);
    }
    public function deleteOptionsOfQuestion($questionId)
    {
        return $this->repo->deleteByQuestionId($questionId);
    }
    public function deleteOptionsByQuestionId($survey_id)
    {
        return $this->repo->deleteOptionsByQuestionId($survey_id);
    }
}
