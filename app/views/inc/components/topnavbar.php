<!-- Linked styles -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar.css">
<!-- Ensure no default body margin leaves space above the navbar -->
<style>
    html, body { margin: 0; padding: 0; }
</style>

<header id="main-topnav" class="navbar d-flex align-items-center justify-between p-3"
    style="position:sticky; top:0; left:0; right:0; z-index:100; background:var(--page-bg, #fff);">
    <!-- Left Side -->
    <div class="d-flex align-center">
        <button class="btn border-0 mr-3" id="sidebar-toggle-btn">
            <i class="fas fa-solid fa-bars"></i>
        </button>
    </div>

    <!-- Right Side -->
    <div class="d-flex align-items-center mr-8">
        <!-- Notification Component -->
        <?php
        $config = [
            'notifications' => [
                [
                    'id' => 1,
                    'type' => 'error',
                    'icon' => 'fas fa-exclamation-circle',
                    'title' => 'System Offline',
                    'message' => 'Kamal Perera\'s system has been offline for 24 hours',
                    'timestamp' => '2 minutes ago',
                    'is_read' => false
                ],
                [
                    'id' => 2,
                    'type' => 'warning',
                    'icon' => 'fas fa-exclamation-triangle',
                    'title' => 'Inverter Fault Detected',
                    'message' => 'Suresh Kumar\'s system shows inverter fault warning',
                    'timestamp' => '15 minutes ago',
                    'is_read' => false
                ],
                [
                    'id' => 3,
                    'type' => 'warning',
                    'icon' => 'fas fa-chart-line',
                    'title' => 'Performance Degradation',
                    'message' => 'Nimali Silva\'s system performance degraded by 35%',
                    'timestamp' => '1 hour ago',
                    'is_read' => false
                ],
                [
                    'id' => 4,
                    'type' => 'info',
                    'icon' => 'fas fa-wrench',
                    'title' => 'Maintenance Scheduled',
                    'message' => 'Service for John Doe\'s system scheduled for tomorrow at 10:00 AM',
                    'timestamp' => '3 hours ago',
                    'is_read' => true
                ],
                [
                    'id' => 5,
                    'type' => 'success',
                    'icon' => 'fas fa-user-plus',
                    'title' => 'New Customer Registered',
                    'message' => 'Ramesh Kumar has registered a new 12kW solar system',
                    'timestamp' => 'Today at 2:30 PM',
                    'is_read' => true
                ]
            ],
            'badge_count' => 3,
            'view_all_url' => URLROOT . '/'.$data['user']['role'].'/notifications'
        ];
        include __DIR__ . '/notification_panel.php';
        ?>

        <div class="d-flex align-items-center">
            <img src="https://i.pravatar.cc/40?u=akash" alt="User Avatar" class="navbar-user-avatar">
            <div class="ml-3">
                <div class="font-semibold">Akash Sandeepa</div>
                <div class="text-sm" style="color: #6c757d;">SOLAR OWNER</div>
            </div>
        </div>
    </div>
</header>
<script>
// Sidebar toggle
const sidebarToggle = document.getElementById('sidebar-toggle-btn');
if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.classList.toggle('active');
        }
    });
}

// Ensure page content isn't hidden behind the sticky navbar
;(function(){
    const nav = document.getElementById('main-topnav');
    if (!nav) return;
    
    // Run on load and on resize
    if (document.readyState === 'complete' || document.readyState === 'interactive') adjustBodyPadding();
    window.addEventListener('load', adjustBodyPadding);
    window.addEventListener('resize', adjustBodyPadding);
})();
</script>
