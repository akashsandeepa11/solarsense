<style>
    /* Custom styles for the sidebar component */
    .sidebar-nav-link, .sidebar-dropdown-toggle {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem; /* 12px 24px */
        color: var(--color-text, #212121);
        text-decoration: none;
        border-radius: 0.75rem; /* 12px -> rounded-xl */
        margin: 0.25rem ;
        transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
        position: relative;
    }
    .sidebar-nav-link:hover, .sidebar-dropdown-toggle:hover {
        background-color: #f5f5f5; /* bg-background */
    }
    .sidebar-nav-link.active, .sidebar-dropdown-toggle.active {
        background-color: #f5f5f5; /* bg-background */
        font-weight: 600; /* font-semibold */
    }
    .sidebar-nav-link i, .sidebar-dropdown-toggle i {
        width: 24px;
        margin-right: 1rem;
        text-align: center;
    }

    .sidebar-nav-link.active, 
    .sidebar-dropdown-toggle.active {
        background-color: var(--color-primary, #fe9630); /* Primary bg */
        color: var(--color-surface, #fff); /* Text color */
        font-weight: 600; /* Keep bold */
    }
    
    .sidebar-nav-link.active i,
    .sidebar-dropdown-toggle.active i {
        color: var(--color-surface, #fff);
    }

    .sidebar-heading {
        font-size: 0.75rem; /* text-xs */
        font-weight: 600; /* font-semibold */
        text-transform: uppercase;
        color: #6c757d; /* A neutral gray */
        padding: 1rem 1.5rem 0.5rem;
    }
    .sidebar-logo {
        color: var(--color-primary, #fe9630);
        font-weight: 700; /* font-bold */
        font-size: 1.5rem; /* text-2xl */
    }
    .sidebar-sub-nav {
        padding-left: 1rem; /* Indent sub-links */
    }
    /* Dropdown styles */
    .sidebar-dropdown-toggle {
        cursor: pointer;
    }
    .sidebar-dropdown-toggle::-webkit-details-marker {
        display: none; /* Hide default arrow */
    }
    .sidebar-dropdown-toggle::after {
        content: '\f078'; /* Font Awesome down arrow */
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 1.5rem;
        transition: transform 0.2s ease;
    }   
    details[open] > .sidebar-dropdown-toggle::after {
       transform: rotate(180deg);
    }

    /* Sidebar dropdown smooth transition */
    .sidebar-sub-nav {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(.4,0,.2,1), padding 0.3s cubic-bezier(.4,0,.2,1);
        padding-top: 0;
        padding-bottom: 0;
    }
    details[open] > .sidebar-sub-nav {
        max-height: 500px; /* Large enough for your content */
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .sidebar-nav-link:focus {
        outline: none;
        box-shadow: none;
        border: none;
    }
</style>

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

