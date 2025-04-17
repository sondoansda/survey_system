<?php
require_once './service/AdminUserService.php';
require_once './config/Database.php';
require_once './service/QuestionService.php';
require_once './service/AnswerService.php';
require_once './service/OptionService.php';
require_once './service/SurveyService.php';
require_once './service/SurveyResponseService.php';

class adminController extends Controller
{
    private $adminService;
    private $surveyService;
    private $questionService;
    private $answerService;
    private $optionService;
    private $responseService;
    private $conn;

    public function __construct()
    {
        $this->questionService = new QuestionService();
        $this->adminService = new AdminUserService();
        $this->surveyService = new SurveyService();
        $this->answerService = new AnswerService();
        $this->optionService = new OptionService();
        $this->responseService = new SurveyResponseService();
        $db = new Database();
        $this->conn = $db->connect();
    }
    public function index()
    {
        $result = $this->surveyService->getList();

        $this->view('admin/index', ['result' => $result]);
    }

    public function logout()
    {
        session_start();
        // Xóa tất cả các biến session
        $_SESSION = array();
        // Hủy phiên
        session_destroy();
        // Thiết lập thông báo thành công và chuyển hướng về trang đăng nhập
        session_start();
        $_SESSION['success_message'] = "Đăng xuất thành công!";
        header("Location: login.php");
        exit;
    }
    public function edit_survey()
    {

        $survey_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        $survey = $this->surveyService->getSurveyById($survey_id);
        if (!$survey) {
            header("Location: index.php");
            exit();
        }

        $message = '';

        // Cập nhật khảo sát
        if (isset($_POST['update_survey'])) {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $status = $_POST['status'];

            if ($this->surveyService->update($survey_id, $title, $description, $status)) {
                $message = '<div class="alert alert-success">Cập nhật khảo sát thành công!</div>';
                $survey = $this->surveyService->getSurveyById($survey_id); // Lấy lại thông tin
            } else {
                $message = '<div class="alert alert-danger">Có lỗi xảy ra!</div>';
            }
        }

        // Thêm câu hỏi
        if (isset($_POST['add_question'])) {
            $question_content = trim($_POST['question_content']);
            $question_type = $_POST['question_type'];
            $parent_question_id = !empty($_POST['parent_question_id']) ? intval($_POST['parent_question_id']) : null;
            $parent_option_id = !empty($_POST['parent_option_id']) ? intval($_POST['parent_option_id']) : null;

            $order_num = $this->surveyService->getNextOrder($survey_id);
            $question_id = $this->surveyService->addQuestion($survey_id, $question_content, $question_type, $order_num, $parent_question_id, $parent_option_id);

            if ($question_id) {
                $options = $_POST['options'];
                foreach ($options as $index => $option_content) {
                    if (!empty(trim($option_content))) {
                        $this->surveyService->addOption($question_id, $option_content, $index);
                    }
                }
                $message = '<div class="alert alert-success">Thêm câu hỏi thành công!</div>';
            } else {
                $message = '<div class="alert alert-danger">Không thể thêm câu hỏi!</div>';
            }
        }

        // Lấy danh sách câu hỏi và dropdown
        $questions = $this->questionService->getQuestionsBySurvey($survey_id);
        $dropdown = $this->surveyService->getQuestionsDropdown($survey_id);

        $this->view('survey/edit_survey', [
            'survey' => $survey,
            'questions' => $questions,
            'dropdown' => $dropdown,
            'message' => $message,
        ]);
    }
    public function edit_question()
    {
        $question_id = isset($_GET['question_id']) ? intval($_GET['question_id']) : 0;
        $question = $this->questionService->getQuestionSurveyById($question_id);
        $survey_id = $question['survey_id'];

        if (!$question) {
            header("Location: index.php");
            exit();
        }

        // Xử lý form cập nhật câu hỏi
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $question_text = trim($_POST['question_text']);
            $question_type = in_array($_POST['question_type'], ['single_choice', 'multiple_choice']) ? $_POST['question_type'] : $question['question_type'];
            $question_order = intval($_POST['question_order']);
            //thong bao ra man hinh dang thuc thi


            if ($question_text == $question['content'] && $question_type == $question['question_type'] && $question_order == $question['order_num']) {
                header("Location: survey_questions.php?id=$survey_id");
                exit();
            }

            if (!empty($question_text)) {
                $question_type = in_array($question_type, ['single_choice', 'multiple_choice']) ? $question_type : 'single_choice';
                $this->questionService->updateQuestion($question_id, $question_text, $question_type, $question_order);
                header("Location: http://localhost:8080/survey_system/admin/edit_survey.php?id=" . $question_id);
                exit();
            }
        }

        $question_types = [
            'single_choice' => '1 lựa chọn',
            'multiple_choice' => 'Nhiều lựa chọn'
        ];

        $this->view('survey/question/edit_question', [
            'question' => $question,
            'survey_id' => $survey_id,
            'question_types' => $question_types,
        ]);
    }
    public function delete_question()
    {
        session_start();
        if (!isset($_GET['question_id']) || empty($_GET['question_id'])) {
            $_SESSION['message'] = "Lỗi: Thiếu ID câu hỏi.";
            header('Location: index.php');
            exit;
        }


        $question_id = filter_var($_GET['question_id'], FILTER_VALIDATE_INT);
        if ($question_id === false || $question_id <= 0) {
            $_SESSION['message'] = "Lỗi: ID câu hỏi không hợp lệ.";
            header('Location: /survey_system/404.php');
            exit;
        }


        $survey_id = $this->questionService->getSurveyIdByQuestionId($question_id);

        if (!$survey_id) {
            $_SESSION['message'] = "Lỗi: Câu hỏi không tồn tại.";
            header('Location: /survey_system/404.php');
            exit;
        }

        $result = $this->questionService->deleteQuestionAndRelations($question_id);

        $_SESSION['message'] = $result ? "Xóa câu hỏi thành công!" : "Có lỗi xảy ra khi xóa.";

        header("Location: /survey_system/admin/edit_survey.php?id=$survey_id");
        exit;
    }
    public function deleteSurvey()
    {
        session_start();
        if (!isset($_GET['survey_id']) || empty($_GET['survey_id'])) {
            $_SESSION['message'] = "Lỗi: Thiếu ID câu hỏi.";
            header('Location: index.php');
            exit;
        }
        $survey_id = $_GET['survey_id'];
        log($survey_id);
        if (!$survey_id || $survey_id <= 0) {
            $_SESSION['message'] = "Lỗi: ID khảo sát không hợp lệ.";
            header("Location: ../index.php");
            exit;
        }

        $this->conn->beginTransaction(); // Bắt đầu transaction

        try {
            $questions = $this->questionService->getQuestionsBySurveyId($survey_id);
            foreach ($questions as $question) {
                foreach ($questions as $question) {
                    $this->answerService->deleteAnswersByQuestionId($question->id);
                    $this->optionService->deleteOptionsByQuestionId($question->id);
                }
            }

            $this->questionService->deleteQuestionsBySurveyId($survey_id);
            $this->responseService->deleteResponsesBySurveyId($survey_id);
            $this->surveyService->deleteSurvey($survey_id);

            $this->conn->commit();
            $_SESSION['message'] = "Xóa khảo sát thành công!";
        } catch (Exception $e) {
            $this->conn->rollBack();
            $_SESSION['message'] = "Có lỗi xảy ra: " . $e->getMessage();
        }

        header("Location: ../index.php");
        exit;
    }
    public function checkLogin()
    {
        session_start();

        // Nếu đã đăng nhập, chuyển hướng về index
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            header("Location: index.php");
            exit;
        }

        // Nếu người dùng gửi form login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $admin = $this->adminService->checkLogin($username, $password);

            if ($admin) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['success_message'] = "Đăng nhập thành công!";
                header("Location: index.php");
                exit;
            } else {
                $_SESSION['error_message'] = "Tài khoản hoặc mật khẩu không đúng!";
                header("Location: login.php");
                exit;
            }
        }

        // Nếu không gửi form, hiển thị login
        $error_message = $_SESSION['error_message'] ?? '';
        $success_message = $_SESSION['success_message'] ?? '';
        unset($_SESSION['error_message']);
        unset($_SESSION['success_message']);

        $this->view('admin/login', [
            'error_message' => $error_message,
            'success_message' => $success_message,
        ]);
    }
    public function createSurvey()
    {
        $message = '';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $status = $_POST['status'];

            // Tạo khảo sát mới bằng model
            $survey_id = $this->surveyService->createSurvey($title, $description, $status);

            if ($survey_id) {
                $message = '<div class="alert alert-success">Tạo khảo sát thành công!</div>';
                // Chuyển hướng đến trang chỉnh sửa khảo sát với id vừa tạo
                header("Location: edit_survey.php?id=" . $survey_id);
                exit();
            } else {
                $message = '<div class="alert alert-danger">Có lỗi xảy ra khi tạo khảo sát.</div>';
            }
        }

        // Trả thông báo về view
        $this->view('survey/create-survey', [
            'message' => $message,
        ]);
    }
}
