<?php
// app/Views/solved_flags/index.php
?>

<main class="container mt-4">
    <h1>Danh Sách Kết Quả</h1>

    <!-- Display success/error messages -->
    <?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Form lọc theo user và thử thách -->
    <form method="GET" class="filter-form">
        <div class="form-group">
            <label for="user_id" class="form-label">Lọc theo người dùng:</label>
            <select name="user_id" id="user_id" class="form-select">
                <option value="">Tất cả người dùng</option>
                <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['id']; ?>"
                    <?php echo $filterUserId === $user['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($user['username']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="challenge_id" class="form-label">Lọc theo thử thách:</label>
            <select name="challenge_id" id="challenge_id" class="form-select">
                <option value="">Tất cả thử thách</option>
                <?php foreach ($challenges as $challenge): ?>
                <option value="<?php echo $challenge['id']; ?>"
                    <?php echo $filterChallengeId === $challenge['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($challenge['title']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="border-radius: 10px; padding: 10px 20px;">Lọc</button>
        <!-- Ẩn input page để giữ giá trị khi lọc -->
        <input type="hidden" name="page" value="<?php echo $currentPage; ?>">
    </form>

    <!-- Bảng hiển thị solved flags -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Người Dùng</th>
                    <th>Thử Thách</th>
                    <th>Mô Tả Flag</th>
                    <th>Kết Quả</th>
                    <th>Điểm Chấm</th>
                    <th>Trạng Thái</th>
                    <th>Thời Gian Giải</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($solvedFlags) > 0): ?>
                <?php foreach ($solvedFlags as $row): ?>
                <?php
                        // Tính điểm tối đa
                        $pointsPerFlag = $row['flag_count'] > 0 ? round($row['challenge_points'] / $row['flag_count']) : 0;
                        $maxPoints = $pointsPerFlag;
                        if ($row['solved_flag_count'] == $row['flag_count'] && $row['solved_at'] == $row['last_solved_at']) {
                            $previousPoints = $pointsPerFlag * ($row['solved_flag_count'] - 1);
                            $maxPoints = $row['challenge_points'] - $previousPoints;
                        }
                        ?>
                <tr data-solved-flag-id="<?php echo $row['id']; ?>">
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['challenge_title']); ?></td>
                    <td><?php echo htmlspecialchars($row['flag_description']); ?></td>
                    <td>
                        <?php
                                if (isset($row['flag_type']) && $row['flag_type'] === 'image' && $row['submitted_value']) {
                                    $imagePaths = json_decode($row['submitted_value'], true);
                                    if (is_array($imagePaths)) {
                                        foreach ($imagePaths as $imagePath) {
                                            ?>
                        <img src="<?php echo htmlspecialchars($imagePath); ?>" class="thumbnail" data-bs-toggle="modal"
                            data-bs-target="#imageModal" data-image="<?php echo htmlspecialchars($imagePath); ?>"
                            alt="Hình ảnh đã gửi">
                        <?php
                                        }
                                    } else {
                                        ?>
                        <img src="<?php echo htmlspecialchars($row['submitted_value']); ?>" class="thumbnail"
                            data-bs-toggle="modal" data-bs-target="#imageModal"
                            data-image="<?php echo htmlspecialchars($row['submitted_value']); ?>" alt="Hình ảnh đã gửi">
                        <?php
                                    }
                                } elseif ($row['submitted_value']) {
                                    echo htmlspecialchars($row['submitted_value']);
                                } else {
                                    ?>
                        <span class="text-muted">Không có giá trị</span>
                        <?php
                                }
                                ?>
                    </td>
                    <td>
                        <?php if ($row['status'] === 'pending'): ?>
                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Chưa chấm điểm">
                            0
                        </span>
                        <?php elseif ($row['points'] !== null): ?>
                        <span data-bs-toggle="tooltip" data-bs-placement="top"
                            title="<?php echo htmlspecialchars($row['comment'] ?? 'Không có nhận xét'); ?>">
                            <?php echo htmlspecialchars($row['points']); ?>
                        </span>
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="status-<?php echo $row['status']; ?>">
                            <?php
                                    switch ($row['status']) {
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
                    <td><?php echo $row['solved_at']; ?></td>
                    <td>
                        <div class="action-icons">
                            
                            <i class="bi bi-pencil-square action-icon score-icon" title="Chấm điểm"
                                data-bs-toggle="modal" data-bs-target="#scoreModal<?php echo $row['id']; ?>"></i>
                            <i class="bi bi-trash action-icon delete-icon" title="Xóa kết quả"
                                data-solved-flag-id="<?php echo $row['id']; ?>"></i>
                        </div>
                    </td>
                </tr>

                <!-- Score Modal -->
                <div class="modal fade" id="scoreModal<?php echo $row['id']; ?>" tabindex="-1"
                    aria-labelledby="scoreModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scoreModalLabel<?php echo $row['id']; ?>">Chấm điểm bài nộp
                                    #<?php echo $row['id']; ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST">
                                    <input type="hidden" name="submission_id" value="<?php echo $row['id']; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Người dùng:
                                            <?php echo htmlspecialchars($row['username']); ?></label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Thử thách:
                                            <?php echo htmlspecialchars($row['challenge_title']); ?></label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Flag nộp:</label>
                                        <?php
                                                if (isset($row['flag_type']) && $row['flag_type'] === 'image') {
                                                    $imagePaths = json_decode($row['submitted_value'], true);
                                                    if (is_array($imagePaths)) {
                                                        foreach ($imagePaths as $imagePath) {
                                                            echo '<img src="' . htmlspecialchars($imagePath) . '" alt="Flag Image" class="thumbnail">';
                                                        }
                                                    } else {
                                                        echo '<img src="' . htmlspecialchars($row['submitted_value']) . '" alt="Flag Image" class="thumbnail">';
                                                    }
                                                } else {
                                                    echo htmlspecialchars($row['submitted_value'] ?? 'N/A');
                                                }
                                                ?>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Flag đúng:
                                            <?php echo htmlspecialchars($row['flag_value']); ?></label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Điểm tối đa:
                                            <?php echo htmlspecialchars($maxPoints); ?></label>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status<?php echo $row['id']; ?>" class="form-label">Trạng
                                            thái</label>
                                        <select class="form-select" id="status<?php echo $row['id']; ?>" name="status"
                                            onchange="toggleManualScore(this, <?php echo $row['id']; ?>)">
                                            <option value="pending"
                                                <?php echo $row['status'] === 'pending' ? 'selected' : ''; ?>>Chờ chấm
                                            </option>
                                            <option value="correct"
                                                <?php echo $row['status'] === 'correct' ? 'selected' : ''; ?>>Đúng (Full
                                                điểm)</option>
                                            <option value="incorrect"
                                                <?php echo $row['status'] === 'incorrect' ? 'selected' : ''; ?>>Sai (0
                                                điểm)</option>
                                            <option value="partial"
                                                <?php echo $row['status'] === 'partial' ? 'selected' : ''; ?>>Đúng 1
                                                phần (Tùy chỉnh điểm)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 manual-score-field"
                                        id="manual-score-field<?php echo $row['id']; ?>"
                                        style="display: <?php echo $row['status'] === 'partial' ? 'block' : 'none'; ?>;">
                                        <label for="points<?php echo $row['id']; ?>" class="form-label">Điểm tùy chỉnh
                                            (0 - <?php echo htmlspecialchars($maxPoints); ?>)</label>
                                        <input type="number" class="form-control" id="points<?php echo $row['id']; ?>"
                                            name="points" min="0" max="<?php echo htmlspecialchars($maxPoints); ?>"
                                            value="<?php echo $row['status'] === 'pending' ? 0 : ($row['points'] !== null ? $row['points'] : 0); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment<?php echo $row['id']; ?>" class="form-label">Nhận
                                            xét</label>
                                        <textarea class="form-control" id="comment<?php echo $row['id']; ?>"
                                            name="comment"
                                            rows="3"><?php echo htmlspecialchars($row['comment'] ?? ''); ?></textarea>
                                    </div>
                                    <button type="submit" name="score_submission" class="btn btn-primary">Lưu</button>
                                </form>
                            </div>
                        </div>
              
                        <?php
// app/Views/solved_flags/index.php (tiếp tục)
?>

                    </div>
                </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <tr>
        <td colspan="8" class="text-center">Không tìm thấy Flag đã giải nào.</td>
    </tr>
    <?php endif; ?>
    </tbody>
    </table>
    </div>

    <!-- Phân trang -->
    <?php if ($totalPages > 1): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <!-- Nút Previous -->
            <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link"
                    href="<?php echo BASE_URL; ?>/solved_flags?<?php echo $filterUserId ? 'user_id=' . $filterUserId . '&' : ''; ?><?php echo $filterChallengeId ? 'challenge_id=' . $filterChallengeId . '&' : ''; ?>page=<?php echo $currentPage - 1; ?>"
                    aria-label="Previous">
                    <span aria-hidden="true">«</span>
                </a>
            </li>
            <!-- Các trang -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                <a class="page-link"
                    href="<?php echo BASE_URL; ?>/solved_flags?<?php echo $filterUserId ? 'user_id=' . $filterUserId . '&' : ''; ?><?php echo $filterChallengeId ? 'challenge_id=' . $filterChallengeId . '&' : ''; ?>page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
            <!-- Nút Next -->
            <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                <a class="page-link"
                    href="<?php echo BASE_URL; ?>/solved_flags?<?php echo $filterUserId ? 'user_id=' . $filterUserId . '&' : ''; ?><?php echo $filterChallengeId ? 'challenge_id=' . $filterChallengeId . '&' : ''; ?>page=<?php echo $currentPage + 1; ?>"
                    aria-label="Next">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</main>

<!-- Modal để hiển thị ảnh phóng to -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Hình ảnh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" class="modal-image" id="modalImage" alt="Hình ảnh">
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript để điền đường dẫn ảnh vào modal khi nhấn vào ảnh thu nhỏ
const imageModal = document.getElementById('imageModal');
imageModal.addEventListener('show.bs.modal', function(event) {
    const thumbnail = event.relatedTarget;
    const imagePath = thumbnail.getAttribute('data-image');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalLabel');
    modalImage.src = imagePath;
    modalTitle.textContent = thumbnail.alt;
});

// Xử lý xóa solved flag
document.querySelectorAll('.delete-icon').forEach(button => {
    button.addEventListener('click', async () => {
        const solvedFlagId = button.getAttribute('data-solved-flag-id');
        if (!solvedFlagId) {
            alert('ID không hợp lệ!');
            return;
        }

        if (!confirm('Bạn có chắc chắn muốn xóa kết quả này?')) {
            return;
        }

        try {
            const response = await fetch('<?php echo BASE_URL; ?>/delete_solved_flag.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    solved_flag_id: solvedFlagId
                })
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                // Xóa hàng khỏi bảng
                const row = button.closest('tr');
                row.remove();
                // Kiểm tra nếu bảng trống, hiển thị thông báo
                const tbody = document.querySelector('tbody');
                if (tbody.children.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="8" class="text-center">Không tìm thấy Flag đã giải nào.</td></tr>';
                }
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Đã xảy ra lỗi khi xóa kết quả!');
        }
    });
});

// JavaScript để hiển thị/ẩn trường điểm tùy chỉnh
function toggleManualScore(select, submissionId) {
    const manualScoreField = document.getElementById('manual-score-field' + submissionId);
    if (select.value === 'partial') {
        manualScoreField.style.display = 'block';
    } else {
        manualScoreField.style.display = 'none';
    }
}

// Khởi tạo tooltip của Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
body {
    font-family: 'Poppins', sans-serif;
    color: #2d3748;
    min-height: 100vh;
    margin: 0;
}

.container {
    max-width: 1320px;
}

h1 {
    font-size: 2.2rem;
    font-weight: 700;
    color: #2b6cb0;
    text-align: center;
    margin-bottom: 30px;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

/* Form lọc */
.filter-form {
    background: #ffffff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    align-items: flex-end;
}

.filter-form .form-group {
    flex: 1;
    min-width: 200px;
}

.filter-form .form-label {
    font-weight: 500;
    color: #4a5568;
}

.filter-form .form-select {
    border: 2px solid #90cdf4;
    border-radius: 10px;
    padding: 10px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.filter-form .form-select:focus {
    border-color: #2b6cb0;
    box-shadow: 0 0 8px rgba(43, 108, 176, 0.2);
}

/* Bảng */
.table-container {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.table thead {
    background: linear-gradient(135deg, #2b6cb0, #63b3ed);
    color: #ffffff;
}

.table th {
    font-weight: 600;
    padding: 15px;
    text-align: center;
    border-bottom: 2px solid #2b6cb0;
}

.table td {
    padding: 15px;
    vertical-align: middle;
    border-bottom: 1px solid #edf2f7;
    text-align: center;
}

/* Cột Kết Quả rộng hơn */
.table th:nth-child(4),
.table td:nth-child(4) {
    width: 25%;
}

.table tbody tr {
    transition: background 0.3s ease, transform 0.3s ease;
}

.table tbody tr:hover {
    background: #e6f7ff;
    transform: translateY(-2px);
}

/* Thumbnail ảnh */
.thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    cursor: pointer;
    margin-right: 5px;
    margin-bottom: 5px;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.thumbnail:hover {
    transform: scale(1.1);
}

.modal-image {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
}

/* Trạng thái */
.status-pending {
    color: #d69e2e;
    font-weight: 500;
    background: rgba(214, 158, 46, 0.1);
    padding: 6px 12px;
    border-radius: 20px;
    display: inline-block;
}

.status-correct {
    color: #38a169;
    font-weight: 500;
    background: rgba(56, 161, 105, 0.1);
    padding: 6px 12px;
    border-radius: 20px;
    display: inline-block;
}

.status-incorrect {
    color: #e53e3e;
    font-weight: 500;
    background: rgba(229, 62, 62, 0.1);
    padding: 6px 12px;
    border-radius: 20px;
    display: inline-block;
}

.status-partial {
    color: #319795;
    font-weight: 500;
    background: rgba(49, 151, 149, 0.1);
    padding: 6px 12px;
    border-radius: 20px;
    display: inline-block;
}

/* Icon hành động */
.action-icons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.action-icon {
    font-size: 1.2rem;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.3s ease;
    cursor: pointer;
}

.score-icon {
    color: #2b6cb0;
}

.score-icon:hover {
    background: #2b6cb0;
    color: #ffffff;
}

.delete-icon {
    color: #e53e3e;
}

.delete-icon:hover {
    background: #e53e3e;
    color: #ffffff;
}

/* Phân trang */
.pagination {
    justify-content: center;
    margin-top: 30px;
}

.page-item .page-link {
    border-radius: 8px;
    margin: 0 5px;
    color: #2b6cb0;
    border: 2px solid #90cdf4;
    font-weight: 500;
    transition: all 0.3s ease;
}

.page-item.active .page-link {
    background: #2b6cb0;
    color: #ffffff;
    border-color: #2b6cb0;
}

.page-item .page-link:hover {
    background: #63b3ed;
    color: #ffffff;
    border-color: #63b3ed;
}

.page-item.disabled .page-link {
    color: #a0aec0;
    border-color: #e2e8f0;
}

/* Responsive */
@media (max-width: 768px) {
    h1 {
        font-size: 1.6rem;
    }

    .filter-form {
        padding: 15px;
        flex-direction: column;
        gap: 15px;
    }

    .filter-form .form-group {
        min-width: 100%;
    }

    .filter-form .form-select {
        font-size: 0.9rem;
        padding: 8px;
    }

    .table th,
    .table td {
        padding: 10px;
        font-size: 0.9rem;
    }

    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: auto;
    }

    .thumbnail {
        width: 40px;
        height: 40px;
    }

    .action-icon {
        font-size: 1rem;
        padding: 6px;
    }
}
</style>