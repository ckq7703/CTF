<style>
/* Container chính của form */
.create-flag-form {
    max-width: 1200px;
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
    grid-template-columns: 1fr 1fr 1fr;
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

/* Tinh chỉnh input, select và textarea */
.form-control {
    border: 1px solid #dfe6e9;
    border-radius: 8px;
    padding: 10px 15px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background-color: #fff;
}

.form-control:focus {
    border-color: #0984e3;
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

/* Checkbox "Là hình ảnh" */
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
    background: linear-gradient(90deg, #0984e3, #74b9ff);
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
    .create-flag-form {
        padding: 20px;
        margin: 20px;
    }

    .form-grid {
        grid-template-columns: 1fr;
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
}

/* Responsive: Điều chỉnh cho màn hình trung bình (tablet) */
@media (min-width: 769px) and (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr 1fr;
    }

    .full-width {
        grid-column: span 2;
    }
}
</style>

<!-- Form tạo mới -->
<div class="create-flag-form">
    <form method="POST" action="<?php echo BASE_URL; ?>/flags/create" enctype="multipart/form-data">
        <div class="form-grid">
            <!-- Thử thách -->
            <div class="mb-3">
                <label for="challenge_id" class="form-label">Thử thách</label>
                <select class="form-control" id="challenge_id" name="challenge_id" required>
                    <option value="">Chọn thử thách</option>
                    <?php foreach ($challenges as $challenge): ?>
                        <option value="<?php echo $challenge['id']; ?>">
                            <?php echo htmlspecialchars($challenge['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tên Flag -->
            <div class="mb-3">
                <label for="flag_name" class="form-label">Tên Flag</label>
                <input type="text" class="form-control" id="flag_name" name="flag_name" placeholder="Nhập tên flag..." required>
            </div>

            <!-- Giá trị Flag -->
            <div class="mb-3">
                <label for="flag_value" class="form-label">Giá trị Flag</label>
                <input type="text" class="form-control" id="flag_value" name="flag_value" placeholder="Nhập giá trị flag..." required>
            </div>

            <!-- Checkbox "Là hình ảnh" -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_image" name="is_image">
                <label class="form-check-label" for="is_image">Là hình ảnh</label>
            </div>

            <!-- Hình ảnh (ẩn/mở theo checkbox) -->
            <div class="mb-3" id="image_path_field" style="display: none;">
                <label for="image_path" class="form-label">Hình ảnh</label>
                <input type="file" class="form-control" id="image_path" name="image_path" accept="image/*">
            </div>

            <!-- Cột trống để giữ bố cục 3 cột -->
            <div class="mb-3"></div>

            <!-- Mô tả (chiếm cả 3 cột) -->
            <div class="mb-3 full-width">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Nhập mô tả..."></textarea>
            </div>

            <!-- Nút bấm (chiếm cả 3 cột) -->
            <div class="form-actions full-width">
                <button type="submit" class="btn btn-primary">Thêm</button>
                <a href="<?php echo BASE_URL; ?>/flags" class="btn btn-secondary">Hủy</a>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('is_image').addEventListener('change', function() {
        document.getElementById('image_path_field').style.display = this.checked ? 'block' : 'none';
    });
</script>