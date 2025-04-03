<?php
require_once __DIR__ . '/../Models/Flag.php';

class FlagController extends Controller {
    private $flagModel;

    public function __construct($pdo) {
        parent::__construct($pdo); // Gọi constructor của Controller cha
        $this->flagModel = new Flag($pdo);
    }

    public function index() {
        $perPage = 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $flags = $this->flagModel->getAll($page, $perPage);
        $totalFlags = $this->flagModel->getTotal();
        $totalPages = ceil($totalFlags / $perPage);

        $this->view('flags/index', [
            'flags' => $flags,
            'mainTitle' => 'Quản lý Flags',
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'challenge_id' => $_POST['challenge_id'],
                'flag_name' => $_POST['flag_name'],
                'flag_value' => $_POST['flag_value'],
                'description' => $_POST['description'],
                'is_image' => isset($_POST['is_image']) ? 1 : 0,
                'image_path' => null
            ];

            if ($data['is_image'] && !empty($_FILES['image_path']['name'])) {
                $data['image_path'] = $this->uploadFile($_FILES['image_path'], 'uploads/flags/');
            }

            if ($this->flagModel->create($data)) {
                header('Location: ' . BASE_URL . '/flags');
                exit;
            }
            echo "Error creating flag.";
        }
        $challenges = $this->flagModel->getChallenges();
        $this->view('flags/create', [
            'challenges' => $challenges,
            'mainTitle' => 'Thêm Flag'
        ]);
    }

    public function edit($id) {
        $flag = $this->flagModel->getById($id);
        if (!$flag) {
            die("Flag not found.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'challenge_id' => $_POST['challenge_id'],
                'flag_name' => $_POST['flag_name'],
                'flag_value' => $_POST['flag_value'],
                'description' => $_POST['description'],
                'is_image' => isset($_POST['is_image']) ? 1 : 0,
                'image_path' => $flag['image_path']
            ];

            if ($data['is_image'] && !empty($_FILES['image_path']['name'])) {
                // Xóa hình ảnh cũ nếu có
                if ($flag['image_path'] && file_exists(__DIR__ . '/../../public/' . $flag['image_path'])) {
                    unlink(__DIR__ . '/../../public/' . $flag['image_path']);
                }
                $data['image_path'] = $this->uploadFile($_FILES['image_path'], 'uploads/flags/');
            } elseif (!$data['is_image']) {
                // Nếu không chọn "Là hình ảnh", xóa hình ảnh cũ
                if ($flag['image_path'] && file_exists(__DIR__ . '/../../public/' . $flag['image_path'])) {
                    unlink(__DIR__ . '/../../public/' . $flag['image_path']);
                }
                $data['image_path'] = null;
            }

            if ($this->flagModel->update($id, $data)) {
                header('Location: ' . BASE_URL . '/flags');
                exit;
            }
            echo "Error updating flag.";
        }
        $challenges = $this->flagModel->getChallenges();
        $this->view('flags/edit', [
            'flag' => $flag,
            'challenges' => $challenges,
            'mainTitle' => 'Sửa Flag'
        ]);
    }

    public function delete($id) {
        $flag = $this->flagModel->getById($id);
        if ($flag && $flag['image_path'] && file_exists(__DIR__ . '/../../public/' . $flag['image_path'])) {
            unlink(__DIR__ . '/../../public/' . $flag['image_path']);
        }

        if ($this->flagModel->delete($id)) {
            header('Location: ' . BASE_URL . '/flags');
            exit;
        }
        echo "Error deleting flag.";
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