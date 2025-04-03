<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (username, password, role, score, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'],
            $data['score']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET username = ?, role = ?, score = ? 
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['username'],
            $data['role'],
            $data['score'],
            $id
        ]);
    }

    public function updatePassword($id, $password) {
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }


    // Tìm user theo username
    public function findByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}