<?php
// app/Models/LeaderboardModel.php

class LeaderboardModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy dữ liệu bảng xếp hạng (chỉ tính điểm từ flag không ở trạng thái 'pending')
    public function getLeaderboardData() {
        $stmt = $this->pdo->query("
            SELECT u.id, u.username, 
                   COALESCE(SUM(sf.points), 0) as score
            FROM users u
            LEFT JOIN solved_flags sf ON u.id = sf.user_id 
                AND sf.status IN ('correct', 'partial')
            WHERE u.role = 'user'
            GROUP BY u.id, u.username
            ORDER BY score DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tổng quan hệ thống
    public function getSystemOverview() {
        $total_challenges = $this->pdo->query("SELECT COUNT(*) FROM challenges")->fetchColumn();
        $total_flags = $this->pdo->query("SELECT COUNT(*) FROM flags")->fetchColumn();
        $total_users = $this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();

        return [
            'total_challenges' => $total_challenges,
            'total_flags' => $total_flags,
            'total_users' => $total_users
        ];
    }

    // Lấy dữ liệu xu hướng điểm số theo thời gian (cho top 5 user)
    public function getTrendData($top_users) {
        $trend_data = [];
        foreach ($top_users as $user) {
            $stmt = $this->pdo->prepare("
                SELECT DATE_FORMAT(solved_at, '%Y-%m-%d %H:00:00') as date_hour, SUM(points) as hourly_score
                FROM solved_flags
                WHERE user_id = :user_id 
                    AND status IN ('correct', 'partial')
                GROUP BY DATE_FORMAT(solved_at, '%Y-%m-%d %H:00:00')
                ORDER BY DATE_FORMAT(solved_at, '%Y-%m-%d %H:00:00')
            ");
            $stmt->execute(['user_id' => $user['id']]);
            $trend_data[$user['username']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $trend_data;
    }

    // Lấy lịch sử nộp flag gần đây
    public function getRecentSubmissions() {
        $stmt = $this->pdo->query("
            SELECT sf.*, u.username, c.title as challenge_title
            FROM solved_flags sf
            JOIN users u ON sf.user_id = u.id
            JOIN challenges c ON sf.challenge_id = c.id
            ORDER BY sf.solved_at DESC
            LIMIT 10
        ");
        $submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Định dạng thời gian nộp
        foreach ($submissions as &$submission) {
            $date = new DateTime($submission['solved_at']);
            $submission['solved_at_formatted'] = $date->format('d/m/Y H:i:s');
        }
        unset($submission);

        return $submissions;
    }

    // Lấy số flag đúng và số thử thách hoàn thành cho một user
    public function getUserStats($user_id) {
        // Số flag đúng (chỉ tính trạng thái 'correct' hoặc 'partial')
        $correct_flags = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM solved_flags 
            WHERE user_id = :user_id 
                AND status IN ('correct', 'partial')
        ");
        $correct_flags->execute(['user_id' => $user_id]);
        $correct_count = $correct_flags->fetchColumn();

        // Số thử thách hoàn thành (chỉ tính trạng thái 'correct' hoặc 'partial')
        $completed_challenges = $this->pdo->prepare("
            SELECT COUNT(DISTINCT challenge_id) 
            FROM solved_flags 
            WHERE user_id = :user_id 
                AND status IN ('correct', 'partial')
        ");
        $completed_challenges->execute(['user_id' => $user_id]);
        $completed_count = $completed_challenges->fetchColumn();

        return [
            'correct_count' => $correct_count,
            'completed_count' => $completed_count
        ];
    }
}