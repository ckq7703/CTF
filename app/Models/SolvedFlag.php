<?php
// app/Models/SolvedFlag.php

class SolvedFlag {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy danh sách solved flags với phân trang và lọc
    public function getAll($page, $perPage, $filterUserId = null, $filterChallengeId = null) {
        $offset = ($page - 1) * $perPage;

        $query = "
            SELECT sf.*, u.username, c.title AS challenge_title, c.points AS challenge_points, f.flag_value, f.description AS flag_description, f.image_path,
                   (SELECT COUNT(*) FROM flags WHERE challenge_id = sf.challenge_id) AS flag_count,
                   (SELECT COUNT(*) FROM solved_flags WHERE user_id = sf.user_id AND challenge_id = sf.challenge_id) AS solved_flag_count,
                   (SELECT MAX(solved_at) FROM solved_flags WHERE user_id = sf.user_id AND challenge_id = sf.challenge_id) AS last_solved_at
            FROM solved_flags sf
            JOIN users u ON sf.user_id = u.id
            JOIN flags f ON sf.flag_id = f.id
            JOIN challenges c ON f.challenge_id = c.id
        ";

        $conditions = [];
        $params = [];
        if ($filterUserId) {
            $conditions[] = "sf.user_id = :user_id";
            $params[':user_id'] = $filterUserId;
        }
        if ($filterChallengeId) {
            $conditions[] = "sf.challenge_id = :challenge_id";
            $params[':challenge_id'] = $filterChallengeId;
        }
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY sf.solved_at DESC LIMIT :offset, :records_per_page";

        $stmt = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        }
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':records_per_page', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm tổng số bản ghi để tính phân trang
    public function getTotal($filterUserId = null, $filterChallengeId = null) {
        $query = "SELECT COUNT(*) FROM solved_flags sf";
        $conditions = [];
        $params = [];
        if ($filterUserId) {
            $conditions[] = "sf.user_id = :user_id";
            $params[':user_id'] = $filterUserId;
        }
        if ($filterChallengeId) {
            $conditions[] = "sf.challenge_id = :challenge_id";
            $params[':challenge_id'] = $filterChallengeId;
        }
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    // Lấy thông tin bài nộp để chấm điểm
    public function getById($id) {
        $query = "
            SELECT sf.*, c.points AS challenge_points, sf.challenge_id, sf.solved_at,
                   (SELECT COUNT(*) FROM flags WHERE challenge_id = sf.challenge_id) AS flag_count,
                   (SELECT COUNT(*) FROM solved_flags WHERE user_id = sf.user_id AND challenge_id = sf.challenge_id) AS solved_flag_count,
                   (SELECT MAX(solved_at) FROM solved_flags WHERE user_id = sf.user_id AND challenge_id = sf.challenge_id) AS last_solved_at
            FROM solved_flags sf
            JOIN flags f ON sf.flag_id = f.id
            JOIN challenges c ON f.challenge_id = c.id
            WHERE sf.id = :id LIMIT 1
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật trạng thái, điểm và nhận xét cho bài nộp
    public function updateScore($id, $status, $points, $comment) {
        $query = "
            UPDATE solved_flags
            SET status = :status, points = :points, comment = :comment
            WHERE id = :id
        ";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            'status' => $status,
            'points' => $points,
            'comment' => $comment,
            'id' => $id
        ]);
    }

    // Xóa bài nộp
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM solved_flags WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Lấy danh sách người dùng để hiển thị trong dropdown
    public function getUsers() {
        $stmt = $this->pdo->query("SELECT id, username FROM users ORDER BY username ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách thử thách để hiển thị trong dropdown
    public function getChallenges() {
        $stmt = $this->pdo->query("SELECT id, title FROM challenges ORDER BY title ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}