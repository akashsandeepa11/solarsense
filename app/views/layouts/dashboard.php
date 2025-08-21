<?php
    // --- Navigation Arrays ---
    // In a real application, this would likely be in a helper file.
    // You would then determine which array to use based on the logged-in user's role.

    // --- Navigation Array for Homeowner (with icons for sub-items) ---
    $homeowner_nav = [
        'MAIN' => [
            [
                'title' => 'Dashboard',
                'url' => '/homeowner/dashboard',
                'icon' => 'fas fa-chart-pie',
                // 'sub_items' => [
                //     ['title' => 'Earnings', 'url' => '/homeowner/earnings'],
                //     ['title' => 'Fault History', 'url' => '/homeowner/faults'],
                //     ['title' => 'Performance', 'url' => '/homeowner/performance'],
                // ]
            ],
            ['title' => 'Service', 'url' => '/homeowner/service', 'icon' => 'fas fa-wrench'],
            ['title' => 'Shop', 'url' => '/homeowner/shop', 'icon' => 'fas fa-store'],
            ['title' => 'Profile', 'url' => '/homeowner/profile', 'icon' => 'fas fa-user'],
        ],
        'SETTINGS' => [
            ['title' => 'Settings', 'url' => '/homeowner/settings', 'icon' => 'fas fa-cog'],
        ],
    ];

    // --- Navigation Array for Installer ---
    $installer_nav = [
        'MAIN' => [
            ['title' => 'Dashboard', 'url' => '/installer/dashboard', 'icon' => 'fas fa-chart-pie'],
            ['title' => 'Fleet Dashboard', 'url' => '/installer/fleet_dashboard', 'icon' => 'fas fa-tachometer-alt'],
            ['title' => 'Add Customer', 'url' => '/installer/add_customer', 'icon' => 'fas fa-user-plus'],
            ['title' => 'Maintenance', 'url' => '/installer/maintenance', 'icon' => 'fas fa-wrench'],
            ['title' => 'Service Agents', 'url' => '/installer/agents', 'icon' => 'fas fa-users-cog'],
        ],
        'SETTINGS' => [
            ['title' => 'Company Profile', 'url' => '/installer/company_profile', 'icon' => 'fas fa-building'],
            ['title' => 'Settings', 'url' => '/installer/settings', 'icon' => 'fas fa-cog'],
        ],
    ];

    // --- Navigation Array for Service Agent ---
    $service_agent_nav = [
        'MAIN' => [
            ['title' => 'Assigned Tasks', 'url' => '/service_agent/tasks', 'icon' => 'fas fa-tasks'],
            ['title' => 'Task History', 'url' => '/service_agent/history', 'icon' => 'fas fa-history'],
            ['title' => 'Profile', 'url' => '/service_agent/profile', 'icon' => 'fas fa-user'],
        ],
        'SETTINGS' => [
            ['title' => 'Setting', 'url' => '/service_agent/settings', 'icon' => 'fas fa-user-cog'],
        ],
    ];

    // --- Navigation Array for CEB Agent ---
    $ceb_agent_nav = [
        'MAIN' => [
            ['title' => 'Dashboard', 'url' => '/ceb_agent/dashboard', 'icon' => 'fas fa-bolt'],
            ['title' => 'Power Cut Schedule', 'url' => '/ceb_agent/powercuts', 'icon' => 'fas fa-calendar-alt'],
            ['title' => 'Grid Insights', 'url' => '/ceb_agent/insights', 'icon' => 'fas fa-chart-line'],
        ],
        'SETTINGS' => [
            ['title' => 'Profile', 'url' => '/ceb_agent/profile', 'icon' => 'fas fa-user-shield'],
        ],
    ];

    // --- Navigation Array for Admin ---
    $admin_nav = [
        'MAIN' => [
            ['title' => 'Dashboard', 'url' => '/admin/dashboard', 'icon' => 'fas fa-tachometer-alt'],
            ['title' => 'User Management', 'url' => '/admin/users', 'icon' => 'fas fa-users'],
            ['title' => 'Platform Reports', 'url' => '/admin/reports', 'icon' => 'fas fa-file-alt'],
        ],
        'SETTINGS' => [
            ['title' => 'System Configuration', 'url' => '/admin/settings', 'icon' => 'fas fa-cogs'],
        ],
    ];
    // --- Logic to select the correct navigation array ---
    // This is a placeholder. You would replace this with your own session/role check.
    $user_role = $data['user']['role']; 
    $navigation_links = [];

    switch ($user_role) {
        case ROLE_SERVICE_AGENT:
            $navigation_links = $service_agent_nav;
            break;
        case ROLE_ADMIN:
            $navigation_links = $admin_nav;
            break;
        case ROLE_CEB_AGENT:
            $navigation_links = $ceb_agent_nav;
            break;
        case ROLE_INSTALLER:
            $navigation_links = $installer_nav;
            break;
        default:
            $navigation_links = $homeowner_nav; 
    }

    require APPROOT.'/views/inc/header.php';
    ?>

    <!-- Linked styles -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/layouts/dashboard.css">


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
            <main class="p-5">
                <?php echo $content;?>
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

