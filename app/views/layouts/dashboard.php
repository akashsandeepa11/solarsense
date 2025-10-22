<?php

    require APPROOT . '/config/nav_arrays.php';

    // This is a placeholder. You would replace this with your own session/role check.
    $user_role = $data['user']['role'] ?? ''; ;
    $navigation_links = [];

    switch ($user_role) {
        case ROLE_SUPER_ADMIN:
            $navigation_links = $super_admin_nav;
            break;
        case ROLE_INSTALLER_ADMIN:
            $navigation_links = $installer_admin_nav;
            break;
        case ROLE_OPERATION_MANAGER:
            $navigation_links = $operation_manager_nav;
            break;
        case ROLE_INVENTORY_MANAGER:
            $navigation_links = $inventory_manager_nav;
            break;
        case ROLE_SERVICE_AGENT:
            $navigation_links = $service_agent_nav;
            break;
        case ROLE_HOMEOWNER:
            $navigation_links = $homeowner_nav;
            break;
        default:
            // Default to an empty array to prevent errors if the role is unknown
            $navigation_links = []; 
            break;
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

