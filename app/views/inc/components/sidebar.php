<!-- Linked styles -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/sidebar.css">

<aside class="sidebar d-flex flex-column p-3 " id="sidebar">
    <!-- Logo -->
    <?php require APPROOT . '/views/inc/components/logo.php'; ?>

    <!-- Dynamic Navigation -->
    <nav class="flex-grow-1">
        <?php
// Get current path without query string
$current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the base directory (e.g. "/solarsense") so it matches $item['url']
$base_dir = parse_url(URLROOT, PHP_URL_PATH);
if ($base_dir && strpos($current_path, $base_dir) === 0) {
    $current_path = substr($current_path, strlen($base_dir));
}

// Now $current_path is like "/homeowner/profile"

foreach ($navigation_links as $section => $items): ?>
    <div class="sidebar-heading mt-2"><?php echo htmlspecialchars($section); ?></div>
    
    <?php foreach ($items as $item): ?>
        <?php
        // Check if current path starts with the item URL (this handles sub-pages)
        $item_url = rtrim($item['url'], '/');
        $current_url = rtrim($current_path, '/');
        $is_active = ($current_url === $item_url || strpos($current_url, $item_url . '/') === 0);
        ?>
        <a href="<?php echo URLROOT . $item['url']; ?>"
           class="sidebar-nav-link <?php echo $is_active ? 'active' : ''; ?> text-decoration-none hover:no-underline">
            <i class="<?php echo htmlspecialchars($item['icon']); ?>"></i>
            <span><?php echo htmlspecialchars($item['title']); ?></span>
        </a>
    <?php endforeach; ?>
<?php endforeach; ?>

    </nav>

    <!-- Footer Links -->
    <div>
        <a href="#" class="sidebar-nav-link text-decoration-none hover:no-underline">
            <i class="fa-solid fa-circle-question"></i>
            <span>Help</span>
        </a>
        <a href="<?php echo URLROOT?>/auth/logout" class="sidebar-nav-link text-error text-decoration-none hover:no-underline">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>

