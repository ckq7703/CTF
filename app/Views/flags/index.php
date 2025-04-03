<button class="add-item-btn" onclick="window.location.href='<?php echo BASE_URL; ?>/flags/create'">
    <i class="fas fa-plus"></i> Thêm Flag
</button>
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Thử thách</th>
                <th>Tên Flag</th>
                <th>Giá trị Flag</th>
                <th>Mô tả</th>
                <th>Là hình ảnh</th>
                <th>Hình ảnh</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($flags) > 0): ?>
                <?php foreach ($flags as $flag): ?>
                    <tr>
                        <td><?php echo $flag['id']; ?></td>
                        <td><?php echo htmlspecialchars($flag['challenge_title']); ?></td>
                        <td><?php echo htmlspecialchars($flag['flag_name']); ?></td>
                        <td><?php echo htmlspecialchars($flag['flag_value']); ?></td>
                        <td><?php echo htmlspecialchars($flag['description'] ?? 'N/A'); ?></td>
                        <td><?php echo $flag['is_image'] ? 'Có' : 'Không'; ?></td>
                        <td>
                            <?php if ($flag['is_image'] && $flag['image_path']): ?>
                                <img src="<?php echo BASE_URL . '/' . $flag['image_path']; ?>" alt="Flag Image" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-icons">
                                <i class="fas fa-edit action-icon edit-icon" title="Sửa" onclick="window.location.href='<?php echo BASE_URL; ?>/flags/edit/<?php echo $flag['id']; ?>'"></i>
                                <a href="<?php echo BASE_URL; ?>/flags/delete/<?php echo $flag['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa flag này không?');">
                                    <i class="fas fa-trash action-icon delete-icon" title="Xóa"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Không có flag nào.</td>
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
            <a href="<?php echo BASE_URL; ?>/flags?page=<?php echo $currentPage - 1; ?>" class="page-link">Trang trước</a>
        <?php endif; ?>

        <!-- Số trang -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="<?php echo BASE_URL; ?>/flags?page=<?php echo $i; ?>" class="page-link <?php echo $i == $currentPage ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <!-- Trang sau -->
        <?php if ($currentPage < $totalPages): ?>
            <a href="<?php echo BASE_URL; ?>/flags?page=<?php echo $currentPage + 1; ?>" class="page-link">Trang sau</a>
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