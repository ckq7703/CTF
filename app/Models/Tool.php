<?php
class Tool {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare("
            SELECT t.*, c.name AS category_name 
            FROM tools t 
            LEFT JOIN categories c ON t.category_id = c.id 
            ORDER BY t.created_at DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM tools");
        return $stmt->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT t.*, c.name AS category_name 
            FROM tools t 
            LEFT JOIN categories c ON t.category_id = c.id 
            WHERE t.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCategories() {
        $stmt = $this->pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO tools (name, description, link, icon, public, created_at, content, category_id) 
            VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)
        ");
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['link'],
            $data['icon'],
            $data['public'],
            $data['content'],
            $data['category_id']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE tools 
            SET name = ?, description = ?, link = ?, icon = ?, public = ?, content = ?, category_id = ? 
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['link'],
            $data['icon'],
            $data['public'],
            $data['content'],
            $data['category_id'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM tools WHERE id = ?");
        return $stmt->execute([$id]);
    }
}