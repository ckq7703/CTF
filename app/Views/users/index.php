<button class="add-item-btn" onclick="window.location.href='<?php echo BASE_URL; ?>/users/create'">
    <i class="fas fa-plus"></i> Thêm Người dùng
</button>
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Đăng nhập</th>
                <th>Vai trò</th>
                <th>Điểm số</th>
                <th>Thời gian tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) > 0): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td>
                            <span class="role-<?php echo $user['role']; ?>">
                                <?php echo ($user['role'] == 'admin' ? 'Admin' : ($user['role'] == 'leader' ? 'Trưởng Nhóm' : 'Người Dùng')); ?>
                            </span>
                        </td>
                        <td><?php echo $user['score'] ?? 0; ?></td>
                        <td><?php echo $user['created_at']; ?></td>
                        <td>
                            <div class="action-icons">
                                <i class="fas fa-edit action-icon edit-icon" title="Sửa" onclick="window.location.href='<?php echo BASE_URL; ?>/users/edit/<?php echo $user['id']; ?>'"></i>
                                <a href="<?php echo BASE_URL; ?>/users/delete/<?php echo $user['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa người dùng này không?');">
                                    <i class="fas fa-trash action-icon delete-icon" title="Xóa"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Không có người dùng nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Phân trang -->
<div class="pagination">
    <?php if ($totalPages > 1): ?>
        <!-- Trang trước -->
        <?php if ($currentPage > 1): ?>
            <a href="<?php echo BASE_URL; ?>/users?page=<?php echo $currentPage - 1; ?>" class="page-link">Trang trước</a>
        <?php endif; ?>

        <!-- Số trang -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="<?php echo BASE_URL; ?>/users?page=<?php echo $i; ?>" class="page-link <?php echo $i == $currentPage ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <!-- Trang sau -->
        <?php if ($currentPage < $totalPages): ?>
            <a href="<?php echo BASE_URL; ?>/users?page=<?php echo $currentPage + 1; ?>" class="page-link">Trang sau</a>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.page-link {
    padding: 8px 12px;
    margin: 0 5px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
    color: #052c65;
}

.page-link:hover {
    background-color: #f0f0f0;
}

.page-link.active {
    background-color: #052c65;
    color: white;
    border-color: #052c65;
}
</style>