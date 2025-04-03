<?php


session_start(); // Khởi động session
// Định nghĩa BASE_URL
define('BASE_URL', '/SUBMIT/public');

// Nạp các file cần thiết
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Controllers/GuideController.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/CategoryController.php';
require_once __DIR__ . '/../app/Controllers/ChallengeController.php';
require_once __DIR__ . '/../app/Controllers/FlagController.php';
require_once __DIR__ . '/../app/Controllers/ToolController.php';
require_once __DIR__ . '/../app/Controllers/UserController.php';
require_once __DIR__ . '/../app/Controllers/LeaderboardController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/SolvedFlagController.php'; // Thêm SolvedFlagController

// Kết nối database
$pdo = Database::getConnection();

// Khởi tạo router
$router = new Router();

// Route mặc định: hiển thị trang chủ
$router->get('/', function() use ($pdo) {
    $controller = new HomeController($pdo);
    $controller->index();
});

// Định tuyến cho GuideController
$router->get('/guides', function() use ($pdo) {
    $controller = new GuideController($pdo);
    $controller->index();
});
$router->get('/guides/create', function() use ($pdo) {
    $controller = new GuideController($pdo);
    $controller->create();
});
$router->post('/guides/create', function() use ($pdo) {
    $controller = new GuideController($pdo);
    $controller->create();
});
$router->get('/guides/edit/:id', function($id) use ($pdo) {
    $controller = new GuideController($pdo);
    $controller->edit($id);
});
$router->post('/guides/edit/:id', function($id) use ($pdo) {
    $controller = new GuideController($pdo);
    $controller->edit($id);
});
$router->get('/guides/delete/:id', function($id) use ($pdo) {
    $controller = new GuideController($pdo);
    $controller->delete($id);
});

// Định tuyến cho CategoryController
$router->get('/categories', function() use ($pdo) {
    $controller = new CategoryController($pdo);
    $controller->index();
});
$router->get('/categories/create', function() use ($pdo) {
    $controller = new CategoryController($pdo);
    $controller->create();
});
$router->post('/categories/create', function() use ($pdo) {
    $controller = new CategoryController($pdo);
    $controller->create();
});
$router->get('/categories/edit/:id', function($id) use ($pdo) {
    $controller = new CategoryController($pdo);
    $controller->edit($id);
});
$router->post('/categories/edit/:id', function($id) use ($pdo) {
    $controller = new CategoryController($pdo);
    $controller->edit($id);
});
$router->get('/categories/delete/:id', function($id) use ($pdo) {
    $controller = new CategoryController($pdo);
    $controller->delete($id);
});

// Định tuyến cho ChallengeController
$router->get('/challenges', function() use ($pdo) {
    $controller = new ChallengeController($pdo);
    $controller->index();
});
$router->get('/challenges/create', function() use ($pdo) {
    $controller = new ChallengeController($pdo);
    $controller->create();
});
$router->post('/challenges/create', function() use ($pdo) {
    $controller = new ChallengeController($pdo);
    $controller->create();
});
$router->get('/challenges/edit/:id', function($id) use ($pdo) {
    $controller = new ChallengeController($pdo);
    $controller->edit($id);
});
$router->post('/challenges/edit/:id', function($id) use ($pdo) {
    $controller = new ChallengeController($pdo);
    $controller->edit($id);
});
$router->get('/challenges/delete/:id', function($id) use ($pdo) {
    $controller = new ChallengeController($pdo);
    $controller->delete($id);
});

// Định tuyến cho FlagController
$router->get('/flags', function() use ($pdo) {
    $controller = new FlagController($pdo);
    $controller->index();
});
$router->get('/flags/create', function() use ($pdo) {
    $controller = new FlagController($pdo);
    $controller->create();
});
$router->post('/flags/create', function() use ($pdo) {
    $controller = new FlagController($pdo);
    $controller->create();
});
$router->get('/flags/edit/:id', function($id) use ($pdo) {
    $controller = new FlagController($pdo);
    $controller->edit($id);
});
$router->post('/flags/edit/:id', function($id) use ($pdo) {
    $controller = new FlagController($pdo);
    $controller->edit($id);
});
$router->get('/flags/delete/:id', function($id) use ($pdo) {
    $controller = new FlagController($pdo);
    $controller->delete($id);
});

// Định tuyến cho ToolController
$router->get('/tools', function() use ($pdo) {
    $controller = new ToolController($pdo);
    $controller->index();
});
$router->get('/tools/create', function() use ($pdo) {
    $controller = new ToolController($pdo);
    $controller->create();
});
$router->post('/tools/create', function() use ($pdo) {
    $controller = new ToolController($pdo);
    $controller->create();
});
$router->get('/tools/edit/:id', function($id) use ($pdo) {
    $controller = new ToolController($pdo);
    $controller->edit($id);
});
$router->post('/tools/edit/:id', function($id) use ($pdo) {
    $controller = new ToolController($pdo);
    $controller->edit($id);
});
$router->get('/tools/delete/:id', function($id) use ($pdo) {
    $controller = new ToolController($pdo);
    $controller->delete($id);
});

// Định tuyến cho UserController
$router->get('/users', function() use ($pdo) {
    $controller = new UserController($pdo);
    $controller->index();
});
$router->get('/users/create', function() use ($pdo) {
    $controller = new UserController($pdo);
    $controller->create();
});
$router->post('/users/create', function() use ($pdo) {
    $controller = new UserController($pdo);
    $controller->create();
});
$router->get('/users/edit/:id', function($id) use ($pdo) {
    $controller = new UserController($pdo);
    $controller->edit($id);
});
$router->post('/users/edit/:id', function($id) use ($pdo) {
    $controller = new UserController($pdo);
    $controller->edit($id);
});
$router->get('/users/delete/:id', function($id) use ($pdo) {
    $controller = new UserController($pdo);
    $controller->delete($id);
});

// Định tuyến cho UserController
$router->get('/leaderboard', function() use ($pdo) {
    $controller = new LeaderboardController($pdo);
    $controller->index();
});

// Định tuyến cho AuthController
$router->get('/login', function() use ($pdo) {
    $controller = new AuthController($pdo);
    $controller->login();
});
$router->post('/login', function() use ($pdo) {
    $controller = new AuthController($pdo);
    $controller->login();
});
$router->get('/logout', function() use ($pdo) {
    $controller = new AuthController($pdo);
    $controller->logout();
});


// Định tuyến cho SolvedFlagController
$router->get('/solved_flags', function() use ($pdo) {
    $controller = new SolvedFlagController($pdo);
    $controller->index();
});
$router->post('/solved_flags', function() use ($pdo) {
    $controller = new SolvedFlagController($pdo);
    $controller->index();
});
// Chạy router
$router->dispatch();