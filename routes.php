<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controller/survey/surveyController.php';
require_once __DIR__ . '/service/SurveyService.php';
require_once './controller/admin/adminController.php';
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Xóa phần tiền tố thư mục (nếu có), ví dụ nếu dùng localhost/project/
$basePath = '/survey_system'; // đổi theo thư mục bạn đặt

$uri = str_replace($basePath, '', $requestUri);

switch ($uri) {
    case '/':
    case  '/index':
    case '/index.php':

        $surveyController = new surveyController();
        $surveyController->index();
        break;
    case '/verification':
    case '/verification.php':
        $surveyController = new surveyController();
        $surveyController->verification();
        break;
    case '/survey':
    case '/survey.php':
        $surveyController = new surveyController();
        $surveyController->survey();
        break;
    case '/thank_you.php':
    case "/thank_you":
        $surveyController = new surveyController();
        $surveyController->thank_you();
        break;
    case '/results':
    case '/results.php':
        $surveyController = new surveyController();
        $surveyController->results();
        break;

    case '/admin/index':
    case '/admin/index.php':
        $adminController = new adminController();
        $adminController->index();
        break;
    case '/admin/edit_survey':
    case '/admin/edit_survey.php':
        $adminController = new adminController();
        $adminController->edit_survey();
        break;

    case '/admin/edit_question':
    case '/admin/edit_question.php':
        $adminController = new adminController();
        $adminController->edit_question();
        break;
    case '/admin/delete_question':
    case '/admin/delete_question.php':
        $adminController = new adminController();
        $adminController->delete_question();
        break;
    case '/admin/delete_survey':
    case '/admin/delete_survey.php':
        $adminController = new adminController();
        $adminController->deleteSurvey();
        break;
    case '/admin/login':
    case '/admin/login.php':
        $adminController = new adminController();
        $adminController->checkLogin();
        break;
    case '/admin/create-survey.php':
    case '/admin/create-survey':
        $adminController = new adminController();
        $adminController->createSurvey();
        break;
}
