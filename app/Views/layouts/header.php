<?php
// app/Views/includes/header.php
?>

<div class="header">
    <div class="search-bar">
        <input type="text" class="form-control" placeholder="Search">
    </div>

    <div class="user-section">
        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Hiển thị khi đã đăng nhập -->
            <div class="dropdown">
                <a class="user-icon dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li class="dropdown-item-text user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></li>
                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/logout">Đăng Xuất</a></li>
                </ul>
            </div>
        <?php else: ?>
            <!-- Hiển thị khi chưa đăng nhập -->
            <a href="<?php echo BASE_URL; ?>/login" class="btn btn-outline-light btn-sm">Đăng Nhập</a>
        <?php endif; ?>
    </div>
</div>

<style>
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 30px;
    }

    .user-section {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-icon {

        color: #052c65;
        font-size: 1.5rem;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .user-icon:hover {
        color:rgb(18, 75, 231);
    }

    .dropdown-menu {
        background-color: #fff;
        border: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        min-width: 150px;
    }

    .dropdown-item-text.user-name {
        font-weight: 500;
        color: #2c3e50;
        padding: 8px 15px;
        font-size: 0.95rem;
    }

    .dropdown-item {
        font-size: 0.9rem;
        color: #2c3e50;
        padding: 8px 15px;
        transition: background-color 0.3s ease;
    }

    .dropdown-item:hover {
        background-color: #f5f7fa;
        color: #052c65;

    }

    .btn-outline-light {
        border-color: white;
        color: white;
        font-size: 0.9rem;
        padding: 5px 15px;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .btn-outline-light:hover {
        background-color: white;
        color: #052c65;
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            gap: 15px;
            padding: 15px;
        }

        .user-section {
            justify-content: center;
            gap: 10px;
        }

        .user-icon {
            font-size: 1.3rem;
        }

        .dropdown-menu {
            min-width: 120px;
        }

        .dropdown-item-text.user-name,
        .dropdown-item {
            font-size: 0.85rem;
            padding: 6px 12px;
        }

        .btn-outline-light {
            font-size: 0.85rem;
            padding: 4px 12px;
        }
    }
</style>