<?php
require_once './repo/AnswerRepository.php';
class AnswerService
{
    private $repo;

    public function __construct()
    {
        $this->repo = new AnswerRepository();
    }
    public function deleteAnswersByQuestionId($question_id)
    {
        return $this->repo->deleteAnswersByQuestionId($question_id);
    }
}
