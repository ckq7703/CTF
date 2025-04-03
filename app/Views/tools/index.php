<button class="add-item-btn" onclick="window.location.href='<?php echo BASE_URL; ?>/tools/create'">
    <i class="fas fa-plus"></i> Thêm Công cụ
</button>
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Công cụ</th>
                <th>Mô tả</th>
                <th>Biểu tượng</th>
                <th>Công khai</th>
                <th>Danh mục</th>
                <th>Thời gian tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($tools) > 0): ?>
                <?php foreach ($tools as $tool): ?>
                    <tr>
                        <td><?php echo $tool['id']; ?></td>
                        <td><?php echo htmlspecialchars($tool['name']); ?></td>
                        <td><?php echo htmlspecialchars($tool['description']); ?></td>
                        <td>
                            <?php if ($tool['icon']): ?>
                                <img src="<?php echo BASE_URL . '/' . $tool['icon']; ?>" alt="Icon" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><?php echo $tool['public'] ? 'Có' : 'Không'; ?></td>
                        <td><?php echo htmlspecialchars($tool['category_name'] ?? 'N/A'); ?></td>
                        <td><?php echo $tool['created_at']; ?></td>
                        <td>
                            <div class="action-icons">
                                <i class="fas fa-edit action-icon edit-icon" title="Sửa" onclick="window.location.href='<?php echo BASE_URL; ?>/tools/edit/<?php echo $tool['id']; ?>'"></i>
                                <a href="<?php echo BASE_URL; ?>/tools/delete/<?php echo $tool['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa công cụ này không?');">
                                    <i class="fas fa-trash action-icon delete-icon" title="Xóa"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">Không có công cụ nào.</td>
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
            <a href="<?php echo BASE_URL; ?>/tools?page=<?php echo $currentPage - 1; ?>" class="page-link">Trang trước</a>
        <?php endif; ?>

        <!-- Số trang -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="<?php echo BASE_URL; ?>/tools?page=<?php echo $i; ?>" class="page-link <?php echo $i == $currentPage ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <!-- Trang sau -->
        <?php if ($currentPage < $totalPages): ?>
            <a href="<?php echo BASE_URL; ?>/tools?page=<?php echo $currentPage + 1; ?>" class="page-link">Trang sau</a>
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