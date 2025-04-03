<style>
/* Container chính của form */
.create-user-form {
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

/* Trường nút bấm chiếm cả 3 cột */
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

/* Tinh chỉnh input và select */
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
    .create-user-form {
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
<div class="create-user-form">
    <form method="POST" action="<?php echo BASE_URL; ?>/users/create">
        <div class="form-grid">
            <!-- Tên Đăng nhập -->
            <div class="mb-3">
                <label for="username" class="form-label">Tên Đăng nhập</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên đăng nhập..." required>
            </div>

            <!-- Mật khẩu -->
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu..." required>
            </div>

            <!-- Vai trò -->
            <div class="mb-3">
                <label for="role" class="form-label">Vai trò</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="user">Người Dùng</option>
                    <option value="leader">Trưởng Nhóm</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <!-- Điểm số -->
            <div class="mb-3">
                <label for="score" class="form-label">Điểm số</label>
                <input type="number" class="form-control" id="score" name="score" value="0" placeholder="Nhập điểm số...">
            </div>

            <!-- Cột trống để giữ bố cục 3 cột -->
            <div class="mb-3"></div>
            <div class="mb-3"></div>

            <!-- Nút bấm (chiếm cả 3 cột) -->
            <div class="form-actions full-width">
                <button type="submit" class="btn btn-primary">Thêm</button>
                <a href="<?php echo BASE_URL; ?>/users" class="btn btn-secondary">Hủy</a>
            </div>
        </div>
    </form>
</div>