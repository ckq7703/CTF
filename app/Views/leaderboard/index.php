<style>
    .header-section {
        background: linear-gradient(135deg, #1350a6, #1a6dc2);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        margin-bottom: 30px;
        margin-top: 50px;
        text-align: center;
        position: relative;
    }

    .header-section h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .header-section h1 i {
        font-size: 2.5rem;
        color: #ffd700;
    }

    .header-section p {
        color: #e0e0e0;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }

    .header-stats {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .header-stats .stat-item {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.1);
        padding: 10px 20px;
        border-radius: 10px;
        color: #ffffff;
        font-size: 1.1rem;
        font-weight: 500;
        transition: transform 0.3s ease;
    }

    .header-stats .stat-item:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.2);
    }

    .header-stats .stat-item i {
        font-size: 1.5rem;
        color: #ffd700;
    }

    .chart-row,
    .table-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 20px;
    }

    .chart-container,
    .table-container {
        flex: 1 1 calc(50% - 10px);
        background: #f8f9fd;
        border: 2px solid #1350a6;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .chart-container h3,
    .table-container h3 {
        font-size: 1.5rem;
        color: #1350a6;
        margin-bottom: 15px;
    }

    .table {
        background: #ffffff;
        color: #333;
    }

    .table thead {
        background: #1350a6;
        color: white;
    }

    .table tbody tr:hover {
        background: #e0e7ff;
    }

    .status-correct {
        color: #28a745;
        font-weight: bold;
    }

    .status-pending {
        color: #ffc107;
        font-weight: bold;
    }

    .status-incorrect {
        color: #dc3545;
        font-weight: bold;
    }

    .status-partial {
        color: #17a2b8;
        font-weight: bold;
    }

    .rank-icon {
        font-size: 1.5rem;
        vertical-align: middle;
    }

    .rank-gold {
        color: #ffd700;
    }

    .rank-silver {
        color: #c0c0c0;
    }

    .rank-bronze {
        color: #cd7f32;
    }

    .table th:first-child,
    .table td:first-child {
        text-align: center;
        vertical-align: middle;
    }

    @media (max-width: 768px) {
        .header-section h1 {
            font-size: 1.8rem;
        }

        .header-section h1 i {
            font-size: 1.8rem;
        }

        .header-section p {
            font-size: 0.9rem;
        }

        .header-stats .stat-item {
            font-size: 0.9rem;
            padding: 8px 15px;
        }

        .header-stats .stat-item i {
            font-size: 1.2rem;
        }

        .chart-container,
        .table-container {
            flex: 1 1 100%;
        }

        .chart-container h3,
        .table-container h3 {
            font-size: 1.2rem;
        }

        .rank-icon {
            font-size: 1.2rem;
        }
    }
</style>
<main class="container mt-5">
    <!-- Header Section -->
    <div class="header-section">
        <h1>
            <i class="bi bi-trophy-fill"></i>
            Bảng Xếp Hạng
        </h1>
        <p>Thống kê và xếp hạng hiệu suất của các nhóm tham gia</p>
        <div class="header-stats">
            <div class="stat-item">
                <i class="bi bi-puzzle-fill"></i>
                <span>Tổng số thử thách: <?php echo $total_challenges; ?></span>
            </div>
            <div class="stat-item">
                <i class="bi bi-flag-fill"></i>
                <span>Tổng số flag: <?php echo $total_flags; ?></span>
            </div>
            <div class="stat-item">
                <i class="bi bi-people-fill"></i>
                <span>Số nhóm tham gia: <?php echo $total_users; ?></span>
            </div>
        </div>
    </div>

    <!-- Dòng 1: Biểu Đồ Điểm Số và Xu Hướng -->
    <div class="chart-row">
        <!-- Biểu Đồ Điểm Số -->
        <div class="chart-container">
            <h3>Biểu Đồ Điểm Số (Top 5)</h3>
            <canvas id="scoreChart" height="100"></canvas>
        </div>

        <!-- Biểu Đồ Xu Hướng Điểm Số -->
        <div class="chart-container">
            <h3>Xu Hướng Điểm Số (Top 5)</h3>
            <canvas id="trendChart" height="100"></canvas>
        </div>
    </div>

    <!-- Dòng 2: Bảng Xếp Hạng và Lịch Sử Nộp Flag -->
    <div class="table-row">
        <!-- Bảng Xếp Hạng -->
        <div class="table-container">
            <h3>Bảng Xếp Hạng</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Xếp hạng</th>
                        <th>Nhóm</th>
                        <th>Điểm số</th>
                        <th>Số Flag Đúng</th>
                        <th>Thử Thách Hoàn Thành</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $rank = 1;
                        foreach ($users as $user) {
                            $stats = $model->getUserStats($user['id']);
                            $correct_count = $stats['correct_count'];
                            $completed_count = $stats['completed_count'];

                            echo "<tr>";
                            echo "<td>";
                            if ($rank == 1) {
                                echo '<i class="bi bi-trophy-fill rank-icon rank-gold" title="Hạng Nhất"></i>';
                            } else {
                                echo $rank;
                            }
                            echo "</td>";
                            echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                            echo "<td>" . ($user['score'] ?? 0) . "</td>";
                            echo "<td>$correct_count</td>";
                            echo "<td>$completed_count</td>";
                            echo "</tr>";
                            $rank++;
                        }
                        if (empty($users)) {
                            echo "<tr><td colspan='5' class='text-center'>Không có nhóm nào trong bảng xếp hạng.</td></tr>";
                        }
                        ?>
                </tbody>
            </table>
        </div>

        <!-- Lịch Sử Nộp Flag Gần Đây -->
        <div class="table-container">
            <h3>Lịch Sử Nộp Flag Gần Đây</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nhóm</th>
                        <th>Thử Thách</th>
                        <th>Thời Gian Nộp</th>
                        <th>Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_submissions as $submission): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($submission['username']); ?></td>
                        <td><?php echo htmlspecialchars($submission['challenge_title']); ?></td>
                        <td><?php echo htmlspecialchars($submission['solved_at_formatted']); ?></td>
                        <td>
                            <span class="status-<?php echo $submission['status']; ?>">
                                <?php
                                        switch ($submission['status']) {
                                            case 'pending':
                                                echo 'Chờ chấm';
                                                break;
                                            case 'correct':
                                                echo 'Đúng';
                                                break;
                                            case 'incorrect':
                                                echo 'Sai';
                                                break;
                                            case 'partial':
                                                echo 'Đúng 1 phần';
                                                break;
                                        }
                                        ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recent_submissions)): ?>
                    <tr>
                        <td colspan="4" class="text-center">Không có lịch sử nộp flag gần đây.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Thêm Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dữ liệu cho biểu đồ điểm số (đã giới hạn top 5)
const usernames = <?php echo json_encode($usernames); ?>;
const scores = <?php echo json_encode($scores); ?>;

const colors = [
    'rgba(19, 80, 166, 0.9)', // Màu chủ đạo #1350a6
    'rgba(75, 192, 192, 0.9)', // Xanh lam
    'rgba(255, 206, 86, 0.9)', // Vàng
    'rgba(153, 102, 255, 0.9)', // Tím
    'rgba(255, 159, 64, 0.9)', // Cam
];

const backgroundColors = usernames.map((_, index) => colors[index % colors.length]);
const borderColors = backgroundColors.map(color => color.replace('0.9', '1'));

// Biểu đồ điểm số
const scoreChart = new Chart(document.getElementById('scoreChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: usernames,
        datasets: [{
            label: 'Điểm số',
            data: scores,
            backgroundColor: backgroundColors,
            borderColor: borderColors,
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Điểm số',
                    color: '#1350a6'
                },
                ticks: {
                    color: '#333'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Nhóm',
                    color: '#1350a6'
                },
                ticks: {
                    color: '#333'
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Biểu đồ xu hướng điểm số
const trendChart = new Chart(document.getElementById('trendChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode($formatted_dates); ?>,
        datasets: <?php echo json_encode($trend_datasets); ?>
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Điểm số',
                    color: '#1350a6'
                },
                ticks: {
                    color: '#333'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Thời Gian (Ngày/Giờ)',
                    color: '#1350a6'
                },
                ticks: {
                    color: '#333',
                    maxRotation: 45,
                    minRotation: 45
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    color: '#333'
                }
            }
        }
    }
});
</script>