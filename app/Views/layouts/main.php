<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Diễn Tập An Ninh Mạng</title>
    <link rel="icon" type="image/png" href="https://img.icons8.com/?size=100&id=hBe7sU29wGub&format=png&color=000000">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/SUBMIT/public/assets/css/style.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/sidebar.php'; ?>

    <!-- Header -->
    <?php include __DIR__ . '/header.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Main Header -->
        <div class="main-header">
            <h4 id="main-title"><?php echo htmlspecialchars($mainTitle); ?></h4>
            <div>
                <!-- <button class="btn btn-outline-secondary me-2">
                    <i class="fas fa-hashtag"></i>
                    <span>Join with an ID</span>
                </button>
                <button class="btn btn-outline-secondary me-2">
                    <i class="fas fa-video"></i>
                    <span>Meet now</span>
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>New meeting</span>
                </button> -->
            </div>
        </div>

        <!-- Main Body -->
        <div class="main-body" id="main-body">
            <?php echo $content; // Nội dung động sẽ được chèn ở đây ?>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="/SUBMIT/public/assets/js/script.js"></script>
</body>
</html>
