
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            perspective: 1000px; /* Tạo không gian 3D */
        }

        /* Video background */
        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        /* Lớp phủ mờ */
        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Lớp phủ mờ để tăng độ tương phản */
            z-index: -1;
        }

        /* Container chính */
        .login-container {
            background: transparent; /* Bỏ nền trong suốt cho container chính */
            border-radius: 15px;
            width: 100%;
            max-width: 900px;
            display: flex;
            flex-direction: row;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 20px rgba(5, 44, 101, 0.3); /* Bóng 3D */
            transform: rotateY(0deg); /* Khởi tạo hiệu ứng 3D */
            transition: transform 0.5s ease, box-shadow 0.5s ease;
            z-index: 1;
            animation: float 6s ease-in-out infinite; /* Hiệu ứng lơ lửng */
        }

        /* Hiệu ứng lơ lửng */
        @keyframes float {
            0% { transform: translateY(0px) rotateY(0deg); }
            50% { transform: translateY(-20px) rotateY(5deg); }
            100% { transform: translateY(0px) rotateY(0deg); }
        }

        /* Hiệu ứng hover cho container */
        .login-container:hover {
            transform: rotateY(10deg);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), 0 0 30px rgba(5, 44, 101, 0.5);
        }

        /* Bên trái: Video */
        .login-image {
            flex: 1;
            position: relative;
            overflow: hidden;
            border-top-left-radius: 15px;
            border-bottom-left-radius: 15px;
        }

        .login-image video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Bên phải: Form đăng nhập */
        .login-form {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #fff; /* Nền trắng */
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        /* Logo */
        .login-form .logo {
            max-width: 200px;
            margin: 0 auto 90px;
            display: block;        }

        /* Tiêu đề */
        .login-form h2 {
            color: #052c65; /* Xanh đậm */
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
            font-weight: 600;
        }

        /* Form label */
        .form-label {
            color: #052c65; /* Xanh đậm */
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 5px;
        }

        /* Input field */
        .form-control {
            background: #f8f9fa; /* Nền xám nhạt */
            border: 1px solid #d1d5db;
            border-radius: 8px;
            color: #052c65; /* Chữ xanh */
            padding: 12px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: #fff;
            border-color: #052c65; /* Viền xanh */
            box-shadow: 0 0 15px rgba(5, 44, 101, 0.3); /* Ánh sáng xanh */
            color: #052c65;
            outline: none;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        /* Nút đăng nhập */
        .btn-primary {
            background: #052c65; /* Màu xanh cố định */
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #fff; /* Chữ trắng */
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(5, 44, 101, 0.3); /* Ánh sáng xanh */
        }

        .btn-primary:hover {
            background: #083a7a; /* Xanh đậm hơn khi hover */
            box-shadow: 0 0 25px rgba(5, 44, 101, 0.5);
            transform: translateY(-2px);
        }

        /* Thông báo lỗi */
        .text-danger {
            font-size: 0.9rem;
            margin-bottom: 15px;
            color: #ff6b6b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 90%;
            }

            .login-image {
        	display: none;
   	}
            .login-form {
                padding: 30px;
               border-top-left-radius: 15px;
                border-bottom-left-radius: 15px;
            }

            .login-form h2 {
                font-size: 1.5rem;
            }

            .login-form .logo {
                max-width: 120px;
            }
        }
    </style>
</head>
<body>
    <!-- Video background -->
    <video class="video-background" autoplay muted loop>
        <source src="/SUBMIT/public/assets/video/bg-video.mp4" type="video/mp4">
        Trình duyệt của bạn không hỗ trợ video.
    </video>

    <!-- Lớp phủ mờ -->
    <div class="video-overlay"></div>

    <div class="login-container">
        <!-- Bên trái: Video -->
        <div class="login-image">
            <video autoplay muted loop>
                <source src="/SUBMIT/public/assets/video/login-video.mp4" type="video/mp4">
                Trình duyệt của bạn không hỗ trợ video.
            </video>
        </div>

        <!-- Bên phải: Form đăng nhập -->
        <div class="login-form" >
            <img src="/SUBMIT/public/assets/images/logo-smartpro.png" alt="SmartPro Logo" class="logo">
            <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
            <h2>Đăng Nhập</h2>
            <form method="POST" action="<?php echo BASE_URL; ?>/login">
                <div class="mb-3">
                    <label for="username" class="form-label">Tên Đăng Nhập</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mật Khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
