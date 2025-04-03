<?php
// app/Controllers/LeaderboardController.php

require_once __DIR__ . '/../Models/LeaderboardModel.php';

class LeaderboardController extends Controller {
    private $leaderboardModel;

    public function __construct($pdo) {
        parent::__construct($pdo); // Gọi constructor của Controller cha
        $this->leaderboardModel = new LeaderboardModel($pdo);
        
    }

    public function index() {
        // Kiểm tra quyền truy cập
        // if (!isAdmin() && !isLeader()) {
        //     header("Location: " . BASE_URL . "/index.php");
        //     exit;
        // }

        // Lấy dữ liệu bảng xếp hạng
        $users = $this->leaderboardModel->getLeaderboardData();

        // Giới hạn top 5 user cho biểu đồ điểm số
        $top_5_users = array_slice($users, 0, 5);
        $usernames = array_column($top_5_users, 'username');
        $scores = array_column($top_5_users, 'score');

        // Lấy tổng quan hệ thống
        $overview = $this->leaderboardModel->getSystemOverview();
        $total_challenges = $overview['total_challenges'];
        $total_flags = $overview['total_flags'];
        $total_users = $overview['total_users'];

        // Lấy dữ liệu xu hướng điểm số - Theo giờ
        $trend_data = $this->leaderboardModel->getTrendData($top_5_users);

        // Chuẩn bị dữ liệu cho biểu đồ xu hướng
        $dates = [];
        foreach ($trend_data as $username => $data) {
            foreach ($data as $entry) {
                $dates[] = $entry['date_hour'];
            }
        }
        $dates = array_unique($dates);
        sort($dates);

        // Định dạng nhãn cho trục X (hiển thị ngày và giờ: "27/03 14:00")
        $formatted_dates = array_map(function($date) {
            $dateTime = new DateTime($date);
            return $dateTime->format('d/m H:i');
        }, $dates);

        $trend_datasets = [];
        foreach ($trend_data as $username => $data) {
            $scores_by_hour = array_fill(0, count($dates), 0);
            foreach ($data as $entry) {
                $index = array_search($entry['date_hour'], $dates);
                $scores_by_hour[$index] = $entry['hourly_score'];
            }
            $trend_datasets[] = [
                'label' => $username,
                'data' => $scores_by_hour,
                'borderColor' => 'rgba(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ', 1)',
                'fill' => false
            ];
        }

        // Lấy lịch sử nộp flag gần đây
        $recent_submissions = $this->leaderboardModel->getRecentSubmissions();

        // Truyền dữ liệu vào view
        $this->view('leaderboard/index', [
            'users' => $users,
            'usernames' => $usernames,
            'scores' => $scores,
            'total_challenges' => $total_challenges,
            'total_flags' => $total_flags,
            'total_users' => $total_users,
            'trend_data' => $trend_data,
            'formatted_dates' => $formatted_dates,
            'trend_datasets' => $trend_datasets,
            'recent_submissions' => $recent_submissions,
            'mainTitle' => 'Bảng Xếp Hạng',
            'model' => $this->leaderboardModel // Truyền model để sử dụng trong view
        ]);
    }
}