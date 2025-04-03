<style>
/* Container chính của form */
.edit-guide-form {
    margin: 30px auto;
    padding: 30px;
    background: linear-gradient(135deg, #ffffff, #f5f7fa);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Sử dụng CSS Grid để chia thành 3 cột */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr; /* 3 cột có kích thước bằng nhau */
    gap: 25px;
}

/* Đảm bảo các trường chiếm toàn bộ cột */
.form-grid .mb-3 {
    margin-bottom: 0 !important;
}

/* Trường mô tả và nút bấm chiếm cả 3 cột */
.full-width {
    grid-column: span 3;
}

/* Tinh chỉnh label */
.form-label {
    font-size: 0.95rem;
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 8px;
    display: block;
}

/* Tinh chỉnh input và textarea */
.form-control {
    border: 1px solid #dfe6e9;
    border-radius: 8px;
    padding: 10px 15px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background-color: #fff;
}

.form-control:focus {
    border-color: #052c65;
    box-shadow: 0 0 8px rgba(9, 132, 227, 0.2);
    outline: none;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

/* Tinh chỉnh file input */
input[type="file"] {
    padding: 5px;
    font-size: 0.9rem;
}

/* Hiển thị hình ảnh hiện tại */
.current-file,
.current-image {
    margin-bottom: 10px;
    font-size: 0.85rem;
    color: #636e72;
}

.current-file a {
    color: #052c65;
    text-decoration: none;
    transition: color 0.3s ease;
}

.current-file a:hover {
    color: #0652dd;
    text-decoration: underline;
}

.current-image img {
    max-width: 120px;
    max-height: 120px;
    width: auto;
    height: auto;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #dfe6e9;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

/* Checkbox công khai */
.form-check {
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-check-label {
    font-size: 0.9rem;
    color: #2d3436;
}

.form-check-input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

/* Nút bấm */
.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn {
    padding: 12px 25px;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 8px;
    border: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(90deg, #052c65, #74b9ff);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(90deg, #0652dd, #0984e3);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(9, 132, 227, 0.3);
}

.btn-secondary {
    background: linear-gradient(90deg, #b2bec3, #dfe6e9);
    color: #2d3436;
}

.btn-secondary:hover {
    background: linear-gradient(90deg, #95a5a6, #b2bec3);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Responsive: Chuyển về 1 cột trên màn hình nhỏ */
@media (max-width: 768px) {
    .edit-guide-form {
        padding: 20px;
        margin: 20px;
    }

    .form-grid {
        grid-template-columns: 1fr; /* 1 cột trên màn hình nhỏ */
    }

    .full-width {
        grid-column: span 1;
    }

    .form-actions {
        justify-content: center;
    }

    .btn {
        padding: 10px 20px;
        font-size: 0.85rem;
    }

    .current-image img {
        max-width: 100px;
        max-height: 100px;
    }
}

/* Responsive: Điều chỉnh cho màn hình trung bình (tablet) */
@media (min-width: 769px) and (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr 1fr; /* 2 cột trên tablet */
    }

    .full-width {
        grid-column: span 2;
    }
}
</style>

<!-- Form chỉnh sửa -->
<div class="edit-guide-form">
    <form method="POST" action="<?php echo BASE_URL; ?>/guides/edit/<?php echo $guide['id']; ?>" enctype="multipart/form-data">
        <div class="form-grid">
            <!-- Tiêu đề -->
            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($guide['title']); ?>" placeholder="Nhập tiêu đề..." required>
            </div>

            <!-- File PDF -->
            <div class="mb-3">
                <label for="pdf_path" class="form-label">File PDF</label>
                <?php if ($guide['pdf_path']): ?>
                    <p class="current-file">File hiện tại: <a href="<?php echo BASE_URL . '/uploads/guides/' . $guide['id'] . '/' . $guide['pdf_path']; ?>" target="_blank">Xem PDF</a></p>
                <?php endif; ?>
                <input type="file" class="form-control" id="pdf_path" name="pdf_path" accept="application/pdf">
            </div>


            
            <!-- Checkbox công khai -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_public" name="is_public" <?php echo $guide['is_public'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="is_public">Công khai</label>
            </div>
            <!-- Bìa trước -->
            <div class="mb-3">
                <label for="front_cover" class="form-label">Bìa trước</label>
                <?php if ($guide['front_cover']): ?>
                    <p class="current-image">Hình ảnh hiện tại: <img src="<?php echo BASE_URL . '/uploads/guides/' . $guide['id'] . '/' . $guide['front_cover']; ?>" alt="Front Cover"></p>
                <?php endif; ?>
                <input type="file" class="form-control" id="front_cover" name="front_cover" accept="image/*">
            </div>

            <!-- Bìa sau -->
            <div class="mb-3">
                <label for="back_cover" class="form-label">Bìa sau</label>
                <?php if ($guide['back_cover']): ?>
                    <p class="current-image">Hình ảnh hiện tại: <img src="<?php echo BASE_URL . '/uploads/guides/' . $guide['id'] . '/' . $guide['back_cover']; ?>" alt="Back Cover"></p>
                <?php endif; ?>
                <input type="file" class="form-control" id="back_cover" name="back_cover" accept="image/*">
            </div>


            <!-- Thumbnail -->
            <div class="mb-3">
                <label for="cover_image" class="form-label">Thumbnail</label>
                <?php if ($guide['cover_image']): ?>
                    <p class="current-image">Hình ảnh hiện tại: <img src="<?php echo BASE_URL . '/uploads/guides/' . $guide['id'] . '/' . $guide['cover_image']; ?>" alt="Thumbnail"></p>
                <?php endif; ?>
                <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
            </div>

            <!-- Mô tả (chiếm cả 3 cột) -->
            <div class="mb-3 full-width">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Nhập mô tả..." required><?php echo htmlspecialchars($guide['description']); ?></textarea>
            </div>

            <!-- Nút bấm (chiếm cả 3 cột) -->
            <div class="form-actions full-width">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="<?php echo BASE_URL; ?>/guides" class="btn btn-secondary">Hủy</a>
            </div>
        </div>
    </form>
</div>