<?php
// app/Controllers/AuthController.php

require_once __DIR__ . '/../Models/User.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct($pdo) {
        parent::__construct($pdo); // Gọi constructor của Controller cha
        $this->userModel = new User($pdo);
    }

    // Ghi đè phương thức requireLogin để bỏ qua kiểm tra đăng nhập
    protected function requireLogin() {
        // Không làm gì cả, bỏ qua kiểm tra đăng nhập
    }

    public function login() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/tools');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header('Location: ' . BASE_URL . '/tools');
                exit;
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
                $this->renderWithoutLayout('auth/login', [
                    'mainTitle' => 'Đăng Nhập',
                    'error' => $error
                ]);
            }
        } else {
            $this->renderWithoutLayout('auth/login', [
                'mainTitle' => 'Đăng Nhập'
            ]);
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}