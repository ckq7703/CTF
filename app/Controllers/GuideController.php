<?php
require_once __DIR__ . '/../Models/Guide.php';

class GuideController extends Controller {
    private $guideModel;

    public function __construct($pdo) {
        parent::__construct($pdo); // Gọi constructor của Controller cha
        $this->guideModel = new Guide($pdo);
    }

    public function index() {
        $perPage = 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $guides = $this->guideModel->getAll($page, $perPage);
        $totalGuides = $this->guideModel->getTotal();
        $totalPages = ceil($totalGuides / $perPage);

        $this->view('guides/index', [
            'guides' => $guides,
            'mainTitle' => 'Quản lý Sổ tay',
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
                'pdf_path' => null,
                'cover_image' => null,
                'front_cover' => null,
                'back_cover' => null,
                'is_public' => isset($_POST['is_public']) ? 1 : 0
            ];

            // Tạo guide trước để lấy ID
            if ($this->guideModel->create($data)) {
                $guideId = $this->pdo->lastInsertId();
                $uploadDir = __DIR__ . '/../../public/uploads/guides/' . $guideId . '/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Xử lý upload PDF
                if (!empty($_FILES['pdf_path']['name'])) {
                    $data['pdf_path'] = $this->uploadFile($_FILES['pdf_path'], $uploadDir, 'pdf');
                }

                // Xử lý upload cover_image
                if (!empty($_FILES['cover_image']['name'])) {
                    $data['cover_image'] = $this->uploadFile($_FILES['cover_image'], $uploadDir, 'cover');
                }

                // Xử lý upload front_cover
                if (!empty($_FILES['front_cover']['name'])) {
                    $data['front_cover'] = $this->uploadFile($_FILES['front_cover'], $uploadDir, 'front_cover');
                }

                // Xử lý upload back_cover
                if (!empty($_FILES['back_cover']['name'])) {
                    $data['back_cover'] = $this->uploadFile($_FILES['back_cover'], $uploadDir, 'back_cover');
                }

                // Cập nhật lại guide với các đường dẫn file
                $this->guideModel->update($guideId, $data);
                header('Location: ' . BASE_URL . '/guides');
                exit;
            }
            echo "Error creating guide.";
        }
        $this->view('guides/create', [
            'mainTitle' => 'Thêm Sổ tay'
        ]);
    }

    public function edit($id) {
        $guide = $this->guideModel->getById($id);
        if (!$guide) {
            die("Guide not found.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'pdf_path' => $guide['pdf_path'],
                'cover_image' => $guide['cover_image'],
                'front_cover' => $guide['front_cover'],
                'back_cover' => $guide['back_cover'],
                'is_public' => isset($_POST['is_public']) ? 1 : 0
            ];

            $uploadDir = __DIR__ . '/../../public/uploads/guides/' . $id . '/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Xử lý upload PDF mới
            if (!empty($_FILES['pdf_path']['name'])) {
                if ($guide['pdf_path'] && file_exists($uploadDir . $guide['pdf_path'])) {
                    unlink($uploadDir . $guide['pdf_path']);
                }
                $data['pdf_path'] = $this->uploadFile($_FILES['pdf_path'], $uploadDir, 'pdf');
            }

            // Xử lý upload cover_image mới
            if (!empty($_FILES['cover_image']['name'])) {
                if ($guide['cover_image'] && file_exists($uploadDir . $guide['cover_image'])) {
                    unlink($uploadDir . $guide['cover_image']);
                }
                $data['cover_image'] = $this->uploadFile($_FILES['cover_image'], $uploadDir, 'cover');
            }

            // Xử lý upload front_cover mới
            if (!empty($_FILES['front_cover']['name'])) {
                if ($guide['front_cover'] && file_exists($uploadDir . $guide['front_cover'])) {
                    unlink($uploadDir . $guide['front_cover']);
                }
                $data['front_cover'] = $this->uploadFile($_FILES['front_cover'], $uploadDir, 'front_cover');
            }

            // Xử lý upload back_cover mới
            if (!empty($_FILES['back_cover']['name'])) {
                if ($guide['back_cover'] && file_exists($uploadDir . $guide['back_cover'])) {
                    unlink($uploadDir . $guide['back_cover']);
                }
                $data['back_cover'] = $this->uploadFile($_FILES['back_cover'], $uploadDir, 'back_cover');
            }

            if ($this->guideModel->update($id, $data)) {
                header('Location: ' . BASE_URL . '/guides');
                exit;
            }
            echo "Error updating guide.";
        }
        $this->view('guides/edit', [
            'guide' => $guide,
            'mainTitle' => 'Sửa Sổ tay'
        ]);
    }

    public function delete($id) {
        $guide = $this->guideModel->getById($id);
        if ($guide) {
            $uploadDir = __DIR__ . '/../../public/uploads/guides/' . $id . '/';
            if (file_exists($uploadDir)) {
                // Xóa tất cả file trong thư mục
                $files = glob($uploadDir . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                rmdir($uploadDir);
            }
        }

        if ($this->guideModel->delete($id)) {
            header('Location: ' . BASE_URL . '/guides');
            exit;
        }
        echo "Error deleting guide.";
    }

    private function uploadFile($file, $targetDir, $prefix) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $fileName = $prefix . '_' . time() . '_' . basename($file['name']);
            $targetPath = $targetDir . $fileName;
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                return $fileName;
            }
        }
        return null;
    }
}