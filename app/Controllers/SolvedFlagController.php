<?php
// app/Controllers/SolvedFlagController.php

require_once __DIR__ . '/../Models/SolvedFlag.php';

class SolvedFlagController extends Controller {
    private $solvedFlagModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->solvedFlagModel = new SolvedFlag($pdo);
    }

    public function index() {
        // Số lượng bản ghi trên mỗi trang
        $recordsPerPage = 10;

        // Xác định trang hiện tại
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

        // Lấy bộ lọc
        $filterUserId = isset($_GET['user_id']) && $_GET['user_id'] !== '' ? (int)$_GET['user_id'] : null;
        $filterChallengeId = isset($_GET['challenge_id']) && $_GET['challenge_id'] !== '' ? (int)$_GET['challenge_id'] : null;

        // Xử lý chấm điểm
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score_submission'])) {
            try {
                $submissionId = $_POST['submission_id'];
                $status = $_POST['status'];
                $comment = isset($_POST['comment']) ? trim($_POST['comment']) : null;

                $submission = $this->solvedFlagModel->getById($submissionId);

                if (!$submission) {
                    $error = "Không tìm thấy bài nộp.";
                } else {
                    // Tính điểm tối đa
                    $pointsPerFlag = $submission['flag_count'] > 0 ? round($submission['challenge_points'] / $submission['flag_count']) : 0;
                    $maxPoints = $pointsPerFlag;
                    if ($submission['solved_flag_count'] == $submission['flag_count'] && $submission['solved_at'] == $submission['last_solved_at']) {
                        $previousPoints = $pointsPerFlag * ($submission['solved_flag_count'] - 1);
                        $maxPoints = $submission['challenge_points'] - $previousPoints;
                    }

                    $points = null;
                    if ($status === 'pending') {
                        $points = 0;
                    } elseif ($status === 'correct') {
                        $points = $maxPoints;
                    } elseif ($status === 'incorrect') {
                        $points = 0;
                    } elseif ($status === 'partial') {
                        $points = isset($_POST['points']) ? (int)$_POST['points'] : null;
                        if ($points === null || $points < 0 || $points > $maxPoints) {
                            $error = "Điểm tùy chỉnh phải từ 0 đến $maxPoints.";
                        }
                    }

                    if (!isset($error)) {
                        if ($this->solvedFlagModel->updateScore($submissionId, $status, $points, $comment)) {
                            $success = "Chấm điểm thành công!";
                            $redirectUrl = BASE_URL . "/solved_flags?page=$currentPage";
                            if ($filterUserId) {
                                $redirectUrl .= "&user_id=$filterUserId";
                            }
                            if ($filterChallengeId) {
                                $redirectUrl .= "&challenge_id=$filterChallengeId";
                            }
                            header("Location: $redirectUrl");
                            exit;
                        } else {
                            $error = "Có lỗi xảy ra khi chấm điểm.";
                        }
                    }
                }
            } catch (PDOException $e) {
                $error = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
            }
        }

        // Lấy danh sách người dùng và thử thách để hiển thị trong dropdown
        $users = $this->solvedFlagModel->getUsers();
        $challenges = $this->solvedFlagModel->getChallenges();

        // Lấy danh sách solved flags
        $solvedFlags = $this->solvedFlagModel->getAll($currentPage, $recordsPerPage, $filterUserId, $filterChallengeId);
        $totalRecords = $this->solvedFlagModel->getTotal($filterUserId, $filterChallengeId);
        $totalPages = ceil($totalRecords / $recordsPerPage);

        // Render view
        $this->view('solved_flags/index', [
            'mainTitle' => 'Danh Sách Kết Quả',
            'solvedFlags' => $solvedFlags,
            'users' => $users,
            'challenges' => $challenges,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'filterUserId' => $filterUserId,
            'filterChallengeId' => $filterChallengeId,
            'success' => $success ?? null,
            'error' => $error ?? null
        ]);
    }
}