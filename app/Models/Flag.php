<?php
class Flag {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare("
            SELECT f.*, c.title AS challenge_title 
            FROM flags f 
            JOIN challenges c ON f.challenge_id = c.id 
            ORDER BY f.id DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM flags");
        return $stmt->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT f.*, c.title AS challenge_title 
            FROM flags f 
            JOIN challenges c ON f.challenge_id = c.id 
            WHERE f.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getChallenges() {
        $stmt = $this->pdo->query("SELECT id, title FROM challenges ORDER BY title ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO flags (challenge_id, flag_name, flag_value, description, is_image, image_path) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['challenge_id'],
            $data['flag_name'],
            $data['flag_value'],
            $data['description'],
            $data['is_image'],
            $data['image_path']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE flags 
            SET challenge_id = ?, flag_name = ?, flag_value = ?, description = ?, is_image = ?, image_path = ? 
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['challenge_id'],
            $data['flag_name'],
            $data['flag_value'],
            $data['description'],
            $data['is_image'],
            $data['image_path'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM flags WHERE id = ?");
        return $stmt->execute([$id]);
    }
}