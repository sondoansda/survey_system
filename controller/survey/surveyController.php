<?php

require_once './service/SurveyService.php';
require_once './service/UserService.php';
require_once './controller/Controller.php';
require_once './service/SurveyResponseService.php';
require_once './service/OptionService.php';
require_once './service/QuestionService.php';
class surveyController extends Controller
{
    private $surveyService;
    private $responService;
    private $userService;
    private $optionService;
    private $questionService;



    public function __construct()
    {
        $this->surveyService = new SurveyService();
        $this->responService = new SurveyResponseService();
        $this->userService = new UserService();
        $this->optionService = new OptionService();
        $this->questionService = new QuestionService();
    }

    public function index()
    {
        $surveys = $this->surveyService->getAllSurveys();
        $this->view('index', ['surveys' => $surveys]);
    }


    public function verification()
    {
        $survey_id = isset($_GET['survey_id']) ? intval($_GET['survey_id']) : 0;

        // Kiểm tra khảo sát có tồn tại không
        $survey_info = $this->surveyService->getSurveyInfo($survey_id);
        if (!$survey_info || $survey_info['status'] !== 'active') {
            header("Location: ./index.php");
            exit();
        }

        $error = "";
        $email = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            $user = new UserModel();
            $ip = $user->getUserIP(); // Gọi phương thức


            // Kiểm tra email có hợp lệ không
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Vui lòng nhập địa chỉ email hợp lệ.";
            } else {
                // Kiểm tra người dùng đã làm khảo sát chưa
                $status = $this->surveyService->checkUserSurveyStatus($survey_id, $email, $ip);

                if ($status['user_exists'] && $status['response_id'] && $status['completed']) {
                    $error = "Bạn đã hoàn thành khảo sát này rồi.";
                } else {
                    // Tạo người dùng mới nếu chưa tồn tại
                    $user_id = $status['user_exists'] ? $status['user_id'] : $this->userService->createUser($email, $ip);

                    // Tạo response mới
                    $response_id = $status['response_id'] ?? $this->responService->createResponse($survey_id, $user_id);


                    if ($response_id) {
                        // Lưu thông tin vào session
                        session_start();
                        $_SESSION['survey_id'] = $survey_id;
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['response_id'] = $response_id;

                        // Chuyển đến trang khảo sát
                        header("Location: ./survey.php");
                        exit();
                    } else {
                        $error = "Có lỗi xảy ra. Vui lòng thử lại.";
                    }
                }
            }
        }
        $this->view('survey/verification', [
            'survey_info' => $survey_info,
            'error' => $error,
            'email' => $email,
        ]);
    }
    public function survey()
    {
        session_start();

        // Kiểm tra session
        if (!isset($_SESSION['survey_id']) || !isset($_SESSION['user_id']) || !isset($_SESSION['response_id'])) {
            header("Location: ./index.php");
            exit();
        }


        // Lấy thông tin từ session
        $survey_id = $_SESSION['survey_id'];
        $user_id = $_SESSION['user_id'];
        $response_id = $_SESSION['response_id'];

        // Lấy và validate dữ liệu từ POST
        $current_question_id = isset($_POST['current_question_id']) ? (int)$_POST['current_question_id'] : null;
        $selected_option_id = isset($_POST['option'])
            ? (is_array($_POST['option']) ? array_map('intval', $_POST['option']) : (int)$_POST['option'])
            : null;

        // Xử lý form submission
        if ($_SERVER["REQUEST_METHOD"] === "POST" && $current_question_id !== null && $selected_option_id !== null) {
            $next_question_option_id = $this->optionService->saveAndGetNextOption($response_id, $current_question_id, $selected_option_id);
            $question = $this->questionService->getNextQuestion($survey_id, $current_question_id, $next_question_option_id);
        } else {
            // Lấy câu hỏi đầu tiên nếu không có dữ liệu POST
            $question = $this->questionService->getNextQuestion($survey_id, null, null);
        }



        // Nếu không còn câu hỏi, đánh dấu hoàn thành và chuyển đến trang cảm ơn
        if (!$question) {
            // tạo answer cho câu hỏi cuối cùng
            $this->surveyService->markSurveyCompleted($response_id);

            // Xóa session
            session_unset();
            session_destroy();

            header("Location: thank_you.php?survey_id=" . $survey_id);
            exit();
        }

        $survey_info = $this->surveyService->getSurveyInfo($survey_id);

        $this->view('survey/survey', [
            'survey_info' => $survey_info,
            'question' => $question,
            'selected_option_id' => $selected_option_id,
            'current_question_id' => $current_question_id,
            'response_id' => $response_id,
        ]);
    }
    public function thank_you()
    {
        $survey_id = isset($_GET['survey_id']) ? intval($_GET['survey_id']) : 0;
        $survey_info = $this->surveyService->getSurveyInfo($survey_id);
        $this->view('survey/thank_you', [
            'survey_info' => $survey_info,
            'survey_id' => $survey_id,
        ]);
    }
    public function results()
    {
        $survey_id = isset($_GET['survey_id']) ? intval($_GET['survey_id']) : 0;
        $survey_info = $this->surveyService->getSurveyInfo($survey_id);

        if (!$survey_info) {
            header("Location: ./index.php");
            exit();
        }

        $results = $this->surveyService->getSurveyResults($survey_id);
        $this->view(
            'result/results',
            [
                'survey_info' => $survey_info,
                'results' => $results,
            ]

        );
    }
}
