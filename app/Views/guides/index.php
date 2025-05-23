<button class="add-item-btn" onclick="window.location.href='<?php echo BASE_URL; ?>/guides/create'">
    <i class="fas fa-plus"></i> Thêm Sổ tay
</button>
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Mô tả</th>
                <th>File PDF</th>
                <th>Thumbnail</th>
                <th>Bìa trước</th>
                <th>Bìa sau</th>
                <th>Công khai</th>
                <th>Thời gian tạo</th>
                <th>Thời gian cập nhật</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($guides) > 0): ?>
                <?php foreach ($guides as $guide): ?>
                    <tr>
                        <td><?php echo $guide['id']; ?></td>
                        <td><?php echo htmlspecialchars($guide['title']); ?></td>
                        <td><?php echo htmlspecialchars($guide['description']); ?></td>
                        <td>
                            <?php if ($guide['pdf_path']): ?>
                                <a href="<?php echo BASE_URL . '/uploads/guides/' . $guide['id'] . '/' . $guide['pdf_path']; ?>" target="_blank">Xem PDF</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($guide['cover_image']): ?>
                                <img src="<?php echo BASE_URL . '/uploads/guides/' . $guide['id'] . '/' . $guide['cover_image']; ?>" alt="Thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($guide['front_cover']): ?>
                                <img src="<?php echo BASE_URL . '/uploads/guides/' . $guide['id'] . '/' . $guide['front_cover']; ?>" alt="Front Cover" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($guide['back_cover']): ?>
                                <img src="<?php echo BASE_URL . '/uploads/guides/' . $guide['id'] . '/' . $guide['back_cover']; ?>" alt="Back Cover" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><?php echo $guide['is_public'] ? 'Có' : 'Không'; ?></td>
                        <td><?php echo $guide['created_at']; ?></td>
                        <td><?php echo $guide['updated_at']; ?></td>
                        <td>
                            <div class="action-icons">
                                <i class="fas fa-edit action-icon edit-icon" title="Sửa" onclick="window.location.href='<?php echo BASE_URL; ?>/guides/edit/<?php echo $guide['id']; ?>'"></i>
                                <a href="<?php echo BASE_URL; ?>/guides/delete/<?php echo $guide['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa sổ tay này không?');">
                                    <i class="fas fa-trash action-icon delete-icon" title="Xóa"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11" class="text-center">Không có sổ tay nào.</td>
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
            <a href="<?php echo BASE_URL; ?>/guides?page=<?php echo $currentPage - 1; ?>" class="page-link">Trang trước</a>
        <?php endif; ?>

        <!-- Số trang -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="<?php echo BASE_URL; ?>/guides?page=<?php echo $i; ?>" class="page-link <?php echo $i == $currentPage ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <!-- Trang sau -->
        <?php if ($currentPage < $totalPages): ?>
            <a href="<?php echo BASE_URL; ?>/guides?page=<?php echo $currentPage + 1; ?>" class="page-link">Trang sau</a>
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