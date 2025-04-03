<?php
require_once __DIR__ . '/../Models/Tool.php';

class ToolController extends Controller {
    private $toolModel;

    public function __construct($pdo) {
        parent::__construct($pdo); // Gọi constructor của Controller cha

        $this->toolModel = new Tool($pdo);
    }



    public function index() {
        $perPage = 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $tools = $this->toolModel->getAll($page, $perPage);
        $totalTools = $this->toolModel->getTotal();
        $totalPages = ceil($totalTools / $perPage);

        $this->view('tools/index', [
            'tools' => $tools,
            'mainTitle' => 'Quản lý Công cụ',
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'link' => $_POST['link'],
                'icon' => null, // Sẽ được cập nhật sau khi upload
                'public' => isset($_POST['public']) ? 1 : 0,
                'content' => $_POST['content'],
                'category_id' => $_POST['category_id'] ?: null
            ];

            // Xử lý upload icon
            if (!empty($_FILES['icon']['name'])) {
                $data['icon'] = $this->uploadFile($_FILES['icon'], 'uploads/tools/icons/');
            }

            if ($this->toolModel->create($data)) {
                header('Location: ' . BASE_URL . '/tools');
                exit;
            }
            echo "Error creating tool.";
        }
        $categories = $this->toolModel->getCategories();
        $this->view('tools/create', [
            'categories' => $categories,
            'mainTitle' => 'Thêm Công cụ'
        ]);
    }

    public function edit($id) {
        $tool = $this->toolModel->getById($id);
        if (!$tool) {
            die("Tool not found.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'link' => $_POST['link'],
                'icon' => $tool['icon'], // Giữ nguyên icon cũ nếu không upload mới
                'public' => isset($_POST['public']) ? 1 : 0,
                'content' => $_POST['content'],
                'category_id' => $_POST['category_id'] ?: null
            ];

            // Xử lý upload icon mới
            if (!empty($_FILES['icon']['name'])) {
                // Xóa icon cũ nếu có
                if ($tool['icon'] && file_exists(__DIR__ . '/../../public/' . $tool['icon'])) {
                    unlink(__DIR__ . '/../../public/' . $tool['icon']);
                }
                $data['icon'] = $this->uploadFile($_FILES['icon'], 'uploads/tools/icons/');
            }

            if ($this->toolModel->update($id, $data)) {
                header('Location: ' . BASE_URL . '/tools');
                exit;
            }
            echo "Error updating tool.";
        }
        $categories = $this->toolModel->getCategories();
        $this->view('tools/edit', [
            'tool' => $tool,
            'categories' => $categories,
            'mainTitle' => 'Sửa Công cụ'
        ]);
    }

    public function delete($id) {
        $tool = $this->toolModel->getById($id);
        if ($tool && $tool['icon'] && file_exists(__DIR__ . '/../../public/' . $tool['icon'])) {
            unlink(__DIR__ . '/../../public/' . $tool['icon']);
        }

        if ($this->toolModel->delete($id)) {
            header('Location: ' . BASE_URL . '/tools');
            exit;
        }
        echo "Error deleting tool.";
    }

    private function uploadFile($file, $targetDir) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $fileName = time() . '_' . basename($file['name']);
            $targetPath = __DIR__ . '/../../public/' . $targetDir . $fileName;
            if (!file_exists(__DIR__ . '/../../public/' . $targetDir)) {
                mkdir(__DIR__ . '/../../public/' . $targetDir, 0777, true);
            }
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                return $targetDir . $fileName;
            }
        }
        return null;
    }
}