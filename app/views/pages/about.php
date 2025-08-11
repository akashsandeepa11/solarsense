<?php
    // --- Navigation Arrays ---
    // In a real application, this would likely be in a helper file.
    // You would then determine which array to use based on the logged-in user's role.

    // --- Navigation Array for Homeowner (with icons for sub-items) ---
    $homeowner_nav = [
        'MAIN' => [
            [
                'title' => 'Dashboard',
                'url' => '#', // URL is '#' for dropdown parent
                'icon' => 'fa-solid fa-chart-pie',
                // 'sub_items' => [
                //     ['title' => 'Earnings', 'url' => '/homeowner/earnings', 'icon' => 'fa-solid fa-dollar-sign'],
                //     ['title' => 'Fault History', 'url' => '/homeowner/faults', 'icon' => 'fa-solid fa-triangle-exclamation'],
                //     ['title' => 'Performance', 'url' => '/homeowner/performance', 'icon' => 'fa-solid fa-chart-line'],
                // ]
            ],
            ['title' => 'Service', 'url' => '/homeowner/service', 'icon' => 'fa-solid fa-wrench'],
            ['title' => 'Shop', 'url' => '/homeowner/shop', 'icon' => 'fa-solid fa-store'],
            ['title' => 'Profile', 'url' => '/homeowner/profile', 'icon' => 'fa-solid fa-user'],
        ],
        'SETTINGS' => [
            ['title' => 'Settings', 'url' => '/homeowner/settings', 'icon' => 'fa-solid fa-gear'],
        ],
    ];
    // ... other nav arrays ($installer_nav, etc.) would go here

    // --- Logic to select the correct navigation array ---
    // This is a placeholder. You would replace this with your own session/role check.
    $user_role = 'homeowner'; // Example role
    $navigation_links = [];

    switch ($user_role) {
        case 'homeowner':
            $navigation_links = $homeowner_nav;
            break;
        // case 'installer':
        //     $navigation_links = $installer_nav;
        //     break;
        // Add other roles here
        default:
            $navigation_links = $homeowner_nav; // Default navigation
    }

    require APPROOT.'/views/inc/header.php';
    ?>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* Custom styles to complement your library for this specific layout */
        body {
            background-color: #f5f5f5; /* bg-background */
        }

        .dashboard-layout {
            display: flex;
        }

        .sidebar {
            width: 260px;
            background-color: #ffffff; /* bg-surface */
            height: 100vh;
            position: sticky;
            top: 0;
            border-right: 1px solid #dee2e6;
            transition: width 0.2s ease-in-out;
        }

        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.2s ease-in-out;
        }

        .navbar {
            background-color: #ffffff; /* bg-surface */
            border-bottom: 1px solid #dee2e6;
        }

        /* Styles for the collapsed sidebar */
        .sidebar.collapsed {
            width: 88px; /* Adjust width for icon-only view */
        }

        .sidebar.collapsed .sidebar-logo,
        .sidebar.collapsed .sidebar-nav-link span,
        .sidebar.collapsed .sidebar-heading,
        .sidebar.collapsed .sidebar-dropdown-toggle::after,
        .sidebar.collapsed .sidebar-sub-nav {
            display: none;
        }

        .sidebar.collapsed .sidebar-nav-link,
        .sidebar.collapsed .sidebar-dropdown-toggle,
        .sidebar.collapsed .sidebar-logo-link {
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-nav-link i,
        .sidebar.collapsed .sidebar-dropdown-toggle i {
            margin-right: 0;
        }
        
        .sidebar.collapsed details[open] .sidebar-sub-nav {
            display: none; /* Hide sub-nav even if details is open */
        }

    </style>

    <div class="dashboard-layout">
        
        <!-- 1. Sidebar Component -->
        <?php 
        // Pass the selected navigation array to the sidebar component
        require APPROOT . '/views/inc/components/sidebar.php'; 
        ?>

        <div class="main-content">
            
            <!-- 2. Navbar Component -->
            <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

            <!-- 3. Page Content -->
            <main class="container-fluid p-4">
                <h1>Dashboard Content</h1>
                <p>Your main page content goes here.</p>
                <!-- Example Card -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Welcome!</h5>
                        <p class="card-text">This is an example of how the main content area would look, using components from your CSS library.</p>
                    </div>
                </div>
            </main>
        </div>

    </div>

    <script>
        // JavaScript to handle the sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebar-toggle-btn');

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                });
            }
        });
    </script>

    <?php require APPROOT.'/views/inc/footer.php'; ?>

