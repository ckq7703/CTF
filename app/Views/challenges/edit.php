<style>
/* Container chính của form */
.edit-challenge-form {
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

/* Trường mô tả, hướng dẫn và nút bấm chiếm cả 3 cột */
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
    border-color: #0984e3;
    box-shadow: 0 0 8px rgba(9, 132, 227, 0.2);
    outline: none;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
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

/* Tinh chỉnh TinyMCE */
.tox-tinymce {
    border: 1px solid #dfe6e9 !important;
    border-radius: 8px !important;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

/* Responsive: Chuyển về 1 cột trên màn hình nhỏ */
@media (max-width: 768px) {
    .edit-challenge-form {
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

<!-- Form chỉnh sửa -->
<div class="edit-challenge-form">
    <form method="POST" action="<?php echo BASE_URL; ?>/challenges/edit/<?php echo $challenge['id']; ?>">
        <div class="form-grid">
            <!-- Tiêu đề -->
            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($challenge['title']); ?>" placeholder="Nhập tiêu đề..." required>
            </div>

            <!-- Điểm -->
            <div class="mb-3">
                <label for="points" class="form-label">Điểm</label>
                <input type="number" class="form-control" id="points" name="points" value="<?php echo $challenge['points'] ?? 100; ?>" placeholder="Nhập số điểm...">
            </div>

            <!-- Checkbox công khai -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_public" name="is_public" <?php echo $challenge['is_public'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="is_public">Công khai</label>
            </div>

            <!-- Mô tả (chiếm cả 3 cột) -->
            <div class="mb-3 full-width">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Nhập mô tả..." required><?php echo htmlspecialchars($challenge['description']); ?></textarea>
            </div>

            <!-- Hướng dẫn (HTML) (chiếm cả 3 cột) -->
            <div class="mb-3 full-width">
                <label for="guide" class="form-label">Hướng dẫn (HTML)</label>
                <textarea class="form-control" id="guide" name="guide" rows="5" placeholder="Nhập hướng dẫn (HTML)..."><?php echo htmlspecialchars($challenge['guide']); ?></textarea>
            </div>

            <!-- Nút bấm (chiếm cả 3 cột) -->
            <div class="form-actions full-width">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="<?php echo BASE_URL; ?>/challenges" class="btn btn-secondary">Hủy</a>
            </div>
        </div>
    </form>
</div>

<!-- Tích hợp TinyMCE -->
<script src="https://cdn.tiny.cloud/1/vjyyrlku8lto2c71wwt6krq1at35z6pw30r84avapkjd4g6b/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    // Khởi tạo TinyMCE cho textarea
    tinymce.init({
        selector: 'textarea#guide',
        height: 300,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | \
                  alignleft aligncenter alignright alignjustify | \
                  bullist numlist outdent indent | removeformat | link image | code | help',
        content_style: 'body { font-family:Poppins,sans-serif; font-size:14px }',
        menubar: true,
        statusbar: true,
        branding: false
    });
</script>