<?php
// Danh mục tĩnh (có thể thay bằng dữ liệu từ database)
$menuItems = [
    [
        'name' => 'Trang chủ',
        'route' => '',
        'icon' => 'fas fa-home'
    ],
    [
        'name' => 'Challenges',
        'route' => 'challenges',
        'icon' => 'fas fa-puzzle-piece'
    ],
    [
        'name' => 'Flags',
        'route' => 'flags',
        'icon' => 'fas fa-flag'
    ],
    [
        'name' => 'Tools',
        'route' => 'tools',
        'icon' => 'fas fa-tools'
    ],
    [
        'name' => 'Guides',
        'route' => 'guides',
        'icon' => 'fas fa-book'
    ],
    [
        'name' => 'Categories',
        'route' => 'categories',
        'icon' => 'fas fa-tags'
    ],
    [
        'name' => 'Leaderboard',
        'route' => 'leaderboard',
        'icon' => 'fas fa-tags'
    ],
    [
        'name' => 'Solved_flags',
        'route' => 'solved_flags',
        'icon' => 'fas fa-tags'
    ],

    [
        'name' => 'Users',
        'route' => 'users',
        'icon' => 'fas fa-users'
    ]
    
];

// Xác định route hiện tại để đánh dấu active
$currentRoute = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$currentRoute = preg_replace('#/(?i)SUBMIT/public#', '', $currentRoute);
?>

<div class="sidebar">
    <?php foreach ($menuItems as $item): ?>
        <div class="sidebar-item <?php echo $currentRoute === $item['route'] ? 'active' : ''; ?>" onclick="navigateTo('<?php echo $item['route']; ?>')">
            <i class="<?php echo $item['icon']; ?>"></i>
            <span><?php echo htmlspecialchars($item['name']); ?></span>
        </div>
    <?php endforeach; ?>
</div>