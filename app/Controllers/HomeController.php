<?php
class HomeController extends Controller {
    public function __construct($pdo) {
        // Không cần PDO nếu trang chủ không truy vấn database
    }

    public function index() {
        $this->view('home/index', [
            'title' => 'Welcome to CTF App',
            'mainTitle' => 'Trang Chủ' // Truyền tiêu đề
        ]);
    }
}