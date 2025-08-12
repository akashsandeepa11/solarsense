<!-- Linked styles -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/sidebar.css">

<aside class="sidebar d-flex flex-column p-3 " id="sidebar">
    <!-- Logo -->
    <div class="text-center py-3 mb-3">
        <a href="#" class="d-flex align-center justify-center text-decoration-none hover:no-underline">
            <img src="<?php echo URLROOT ?>/public/img/logo.png" width="40" height="40"  alt="Logo" class="rounded-lg">
            <span class="sidebar-logo ml-2">SolarSense</span>
        </a>
    </div>

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
        $is_active = ($current_path === $item['url'] || rtrim($current_path, '/') === rtrim($item['url'], '/'));
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
        <a href="<?php echo URLROOT?>/auth/login" class="sidebar-nav-link text-error text-decoration-none hover:no-underline">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <span>Logout Account</span>
        </a>
    </div>
</aside>

