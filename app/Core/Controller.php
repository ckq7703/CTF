<?php
// app/Core/Controller.php

class Controller {
    protected $mainTitle = 'CTF App'; // Tiêu đề mặc định
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;

        // Kiểm tra đăng nhập
        $this->requireLogin();
    }

    protected function requireLogin() {
        // Kiểm tra xem session đã được khởi động chưa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public function view($view, $data = []) {
        // Trích xuất dữ liệu để sử dụng trong view
        extract($data);

        // Lưu tiêu đề từ dữ liệu (nếu có)
        if (isset($mainTitle)) {
            $this->mainTitle = $mainTitle;
        }

        // Bắt đầu bộ đệm đầu ra để lấy nội dung view
        ob_start();
        require_once __DIR__ . "/../Views/$view.php";
        $content = ob_get_clean();

        // Truyền tiêu đề vào layout
        $mainTitle = $this->mainTitle;

        // Render layout với nội dung view
        require_once __DIR__ . "/../Views/layouts/main.php";
    }

    // Phương thức render không sử dụng layout (dành cho trang đăng nhập)
    protected function renderWithoutLayout($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . "/../Views/$view.php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View file $view not found.");
        }
    }
}