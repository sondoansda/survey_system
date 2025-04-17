<?php
require_once  './repo/ResponseRepository.php';
require_once  './repo/AnswerRepository.php';

class SurveyResponseService
{
    private $responseRepo;

    public function __construct()
    {
        $this->responseRepo = new ResponseRepository();
    }

    function createResponse($survey_id, $user_id)
    {
        $response = $this->responseRepo->createResponse($survey_id, $user_id);
        if ($response) {
            return $response;
        }
        return false;
    }
    public function deleteResponsesBySurveyId($survey_id)
    {
        return $this->responseRepo->deleteResponsesBySurveyId($survey_id);
    }
}
