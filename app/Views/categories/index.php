<button class="add-item-btn" onclick="window.location.href='<?php echo BASE_URL; ?>/categories/create'">
    <i class="fas fa-plus"></i> Thêm Danh mục
</button>
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Danh mục</th>
                <th>Thời gian tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($categories) > 0): ?>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $category['id']; ?></td>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo $category['created_at']; ?></td>
                        <td>
                            <div class="action-icons">
                                <i class="fas fa-edit action-icon edit-icon" title="Sửa" onclick="window.location.href='<?php echo BASE_URL; ?>/categories/edit/<?php echo $category['id']; ?>'"></i>
                                <a href="<?php echo BASE_URL; ?>/categories/delete/<?php echo $category['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa danh mục này không?');">
                                    <i class="fas fa-trash action-icon delete-icon" title="Xóa"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Không có danh mục nào.</td>
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
            <a href="<?php echo BASE_URL; ?>/categories?page=<?php echo $currentPage - 1; ?>" class="page-link">Trang trước</a>
        <?php endif; ?>

        <!-- Số trang -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="<?php echo BASE_URL; ?>/categories?page=<?php echo $i; ?>" class="page-link <?php echo $i == $currentPage ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <!-- Trang sau -->
        <?php if ($currentPage < $totalPages): ?>
            <a href="<?php echo BASE_URL; ?>/categories?page=<?php echo $currentPage + 1; ?>" class="page-link">Trang sau</a>
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