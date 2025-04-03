<?php
class Challenge {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare("SELECT * FROM challenges ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM challenges");
        return $stmt->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM challenges WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCategories() {
        $stmt = $this->pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO challenges (title, description, difficulty, points, category_id, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['difficulty'],
            $data['points'],
            $data['category_id']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE challenges 
            SET title = ?, description = ?, difficulty = ?, points = ?, category_id = ? 
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['difficulty'],
            $data['points'],
            $data['category_id'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM challenges WHERE id = ?");
        return $stmt->execute([$id]);
    }
}