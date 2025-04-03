<?php
class Category {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare("SELECT * FROM categories ORDER BY created_at DESC LIMIT ? OFFSET ?");
        // Ép kiểu thành số nguyên
        $stmt->bindValue(1, (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM categories");
        return $stmt->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name, created_at) VALUES (?, NOW())");
        return $stmt->execute([$data['name']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}