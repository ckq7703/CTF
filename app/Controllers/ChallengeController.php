<?php
require_once __DIR__ . '/../Models/Challenge.php';

class ChallengeController extends Controller {
    private $challengeModel;

    public function __construct($pdo) {
        parent::__construct($pdo); // Gọi constructor của Controller cha
        $this->challengeModel = new Challenge($pdo);
    }

    public function index() {
        $perPage = 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $challenges = $this->challengeModel->getAll($page, $perPage);
        $totalChallenges = $this->challengeModel->getTotal();
        $totalPages = ceil($totalChallenges / $perPage);

        $this->view('challenges/index', [
            'challenges' => $challenges,
            'mainTitle' => 'Quản lý Thử thách',
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'guide' => $_POST['guide'],
                'points' => $_POST['points'],
                'is_public' => isset($_POST['is_public']) ? 1 : 0
            ];

            if ($this->challengeModel->create($data)) {
                header('Location: ' . BASE_URL . '/challenges');
                exit;
            }
            echo "Error creating challenge.";
        }
        $this->view('challenges/create', [
            'mainTitle' => 'Thêm Thử thách'
        ]);
    }

    public function edit($id) {
        $challenge = $this->challengeModel->getById($id);
        if (!$challenge) {
            die("Challenge not found.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'guide' => $_POST['guide'],
                'points' => $_POST['points'],
                'is_public' => isset($_POST['is_public']) ? 1 : 0
            ];

            if ($this->challengeModel->update($id, $data)) {
                header('Location: ' . BASE_URL . '/challenges');
                exit;
            }
            echo "Error updating challenge.";
        }
        $this->view('challenges/edit', [
            'challenge' => $challenge,
            'mainTitle' => 'Sửa Thử thách'
        ]);
    }

    public function delete($id) {
        if ($this->challengeModel->delete($id)) {
            header('Location: ' . BASE_URL . '/challenges');
            exit;
        }
        echo "Error deleting challenge.";
    }
}