<?php
require_once __DIR__ . '/../Models/User.php';

class UserController extends Controller {
    private $userModel;

    public function __construct($pdo) {
        parent::__construct($pdo); // Gọi constructor của Controller cha
        $this->userModel = new User($pdo);
    }

    public function index() {
        $perPage = 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $users = $this->userModel->getAll($page, $perPage);
        $totalUsers = $this->userModel->getTotal();
        $totalPages = ceil($totalUsers / $perPage);

        $this->view('users/index', [
            'users' => $users,
            'mainTitle' => 'Quản lý Người dùng',
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'role' => $_POST['role'],
                'score' => $_POST['score']
            ];

            if ($this->userModel->create($data)) {
                header('Location: ' . BASE_URL . '/users');
                exit;
            }
            echo "Error creating user.";
        }
        $this->view('users/create', [
            'mainTitle' => 'Thêm Người dùng'
        ]);
    }

    public function edit($id) {
        $user = $this->userModel->getById($id);
        if (!$user) {
            die("User not found.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'],
                'role' => $_POST['role'],
                'score' => $_POST['score']
            ];

            if (!empty($_POST['password'])) {
                $this->userModel->updatePassword($id, $_POST['password']);
            }

            if ($this->userModel->update($id, $data)) {
                header('Location: ' . BASE_URL . '/users');
                exit;
            }
            echo "Error updating user.";
        }
        $this->view('users/edit', [
            'user' => $user,
            'mainTitle' => 'Sửa Người dùng'
        ]);
    }

    public function delete($id) {
        if ($this->userModel->delete($id)) {
            header('Location: ' . BASE_URL . '/users');
            exit;
        }
        echo "Error deleting user.";
    }
}