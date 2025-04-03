<?php
require_once __DIR__ . '/../Models/Category.php';

class CategoryController extends Controller {
    private $categoryModel;

    public function __construct($pdo) {
        parent::__construct($pdo); // Gọi constructor của Controller cha
        $this->categoryModel = new Category($pdo);
    }



    public function index() {
        $perPage = 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $categories = $this->categoryModel->getAll($page, $perPage);
        $totalCategories = $this->categoryModel->getTotal();
        $totalPages = ceil($totalCategories / $perPage);

        $this->view('categories/index', [
            'categories' => $categories,
            'mainTitle' => 'Quản lý Danh mục Công cụ',
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name']
            ];

            if ($this->categoryModel->create($data)) {
                header('Location: ' . BASE_URL . '/categories');
                exit;
            }
            echo "Error creating category.";
        }
        $this->view('categories/create', [
            'mainTitle' => 'Thêm Danh mục'
        ]);
    }

    public function edit($id) {
        $category = $this->categoryModel->getById($id);
        if (!$category) {
            die("Category not found.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name']
            ];

            if ($this->categoryModel->update($id, $data)) {
                header('Location: ' . BASE_URL . '/categories');
                exit;
            }
            echo "Error updating category.";
        }
        $this->view('categories/edit', [
            'category' => $category,
            'mainTitle' => 'Sửa Danh mục'
        ]);
    }

    public function delete($id) {
        if ($this->categoryModel->delete($id)) {
            header('Location: ' . BASE_URL . '/categories');
            exit;
        }
        echo "Error deleting category.";
    }
}