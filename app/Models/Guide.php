<?php
class Guide {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare("SELECT * FROM guides ORDER BY created_at DESC LIMIT ? OFFSET ?");
        // Ép kiểu thành số nguyên
        $stmt->bindValue(1, (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM guides");
        return $stmt->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM guides WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO guides (title, description, pdf_path, cover_image, front_cover, back_cover, is_public, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['pdf_path'],
            $data['cover_image'],
            $data['front_cover'],
            $data['back_cover'],
            $data['is_public']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE guides 
            SET title = ?, description = ?, pdf_path = ?, cover_image = ?, front_cover = ?, back_cover = ?, is_public = ?, updated_at = NOW() 
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['pdf_path'],
            $data['cover_image'],
            $data['front_cover'],
            $data['back_cover'],
            $data['is_public'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM guides WHERE id = ?");
        return $stmt->execute([$id]);
    }
}