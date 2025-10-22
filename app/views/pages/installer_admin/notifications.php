<?php
// --- Notifications Page Data ---

// Sample notifications data
$allNotifications = [
    [
        'id' => 1,
        'type' => 'error',
        'icon' => 'fas fa-exclamation-circle',
        'title' => 'System Offline',
        'message' => 'Kamal Perera\'s system has been offline for 24 hours. Immediate action required.',
        'timestamp' => '2 minutes ago',
        'date' => '2025-10-22',
        'is_read' => false,
        'details' => 'System Location: Nuwara Eliya, Central Highlands | Last Known Output: 3.2 kW'
    ],
    [
        'id' => 2,
        'type' => 'warning',
        'icon' => 'fas fa-exclamation-triangle',
        'title' => 'Inverter Fault Detected',
        'message' => 'Suresh Kumar\'s system shows inverter fault warning. Performance may be affected.',
        'timestamp' => '15 minutes ago',
        'date' => '2025-10-22',
        'is_read' => false,
        'details' => 'System Capacity: 9 kWp | Affected Output: ~45% | Recommended Action: Schedule immediate maintenance'
    ],
    [
        'id' => 3,
        'type' => 'warning',
        'icon' => 'fas fa-chart-line',
        'title' => 'Performance Degradation',
        'message' => 'Nimali Silva\'s system performance degraded by 35% from expected.',
        'timestamp' => '1 hour ago',
        'date' => '2025-10-22',
        'is_read' => false,
        'details' => 'Expected Monthly: 1,125 kWh | Current: 540 kWh | Likely Cause: Panel soiling or fault'
    ],
    [
        'id' => 4,
        'type' => 'info',
        'icon' => 'fas fa-wrench',
        'title' => 'Maintenance Scheduled',
        'message' => 'Service for John Doe\'s system scheduled for tomorrow at 10:00 AM',
        'timestamp' => '3 hours ago',
        'date' => '2025-10-22',
        'is_read' => true,
        'details' => 'Service Type: Quarterly Maintenance | Assigned Agent: Anura Kumara | Duration: 2 hours'
    ],
    [
        'id' => 5,
        'type' => 'success',
        'icon' => 'fas fa-user-plus',
        'title' => 'New Customer Registered',
        'message' => 'Ramesh Kumar has registered a new 12kW solar system',
        'timestamp' => 'Today at 2:30 PM',
        'date' => '2025-10-22',
        'is_read' => true,
        'details' => 'System Capacity: 12 kWp | Location: Colombo | Installation Date: 2025-10-25'
    ],
    [
        'id' => 6,
        'type' => 'success',
        'icon' => 'fas fa-check-circle',
        'title' => 'Service Task Completed',
        'message' => 'Ravi Fernando\'s system maintenance completed successfully',
        'timestamp' => 'Yesterday at 4:15 PM',
        'date' => '2025-10-21',
        'is_read' => true,
        'details' => 'Service Type: Panel Cleaning | Performance Improvement: +8% | Agent: Bhanu Rajapaksha'
    ],
    [
        'id' => 7,
        'type' => 'info',
        'icon' => 'fas fa-tools',
        'title' => 'System Installation Started',
        'message' => 'Installation for Samanthi De Silva\'s new 8kW system has started',
        'timestamp' => 'Yesterday at 9:00 AM',
        'date' => '2025-10-21',
        'is_read' => true,
        'details' => 'Expected Completion: 2025-10-24 | Location: Kandy | Assigned Team: Installation Team A'
    ],
    [
        'id' => 8,
        'type' => 'warning',
        'icon' => 'fas fa-battery-quarter',
        'title' => 'Low Battery Storage',
        'message' => 'Battery storage system at 15% capacity. Consider scheduling charging.',
        'timestamp' => '2 days ago',
        'date' => '2025-10-20',
        'is_read' => true,
        'details' => 'Affected System: Fleet Battery Bank | Recommended Action: Schedule charging during low-demand hours'
    ],
    [
        'id' => 9,
        'type' => 'success',
        'icon' => 'fas fa-chart-bar',
        'title' => 'Monthly Report Generated',
        'message' => 'October performance report for all systems is now available',
        'timestamp' => '3 days ago',
        'date' => '2025-10-19',
        'is_read' => true,
        'details' => 'Report Period: Oct 1-22, 2025 | Total Generation: 6,390 kWh | Download Available'
    ]
];

function getStatusBadgeClass($type) {
    switch ($type) {
        case 'error': return 'badge-error';
        case 'warning': return 'badge-warning';
        case 'info': return 'badge-info';
        case 'success': return 'badge-success';
        default: return 'badge-secondary';
    }
}

function getStatusColor($type) {
    switch ($type) {
        case 'error': return '#ef4444';
        case 'warning': return '#f59e0b';
        case 'info': return '#3b82f6';
        case 'success': return '#22c55e';
        default: return '#6b7280';
    }
}

function getStatusBgColor($type) {
    switch ($type) {
        case 'error': return '#fee2e2';
        case 'warning': return '#fed7aa';
        case 'info': return '#dbeafe';
        case 'success': return '#d1fae5';
        default: return '#f3f4f6';
    }
}

// Count unread notifications
$unreadCount = 0;
foreach ($allNotifications as $notif) {
    if (!isset($notif['is_read']) || !$notif['is_read']) {
        $unreadCount++;
    }
}
?>

<link rel="stylesheet" href="<?php echo URLROOT?>/public/css/components.css">

<style>
    .notifications-container {
        background: #f9fafb;
        min-height: 100vh;
        padding: 2rem;
    }

    .notification-filters {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        background: white;
        color: #212121;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .filter-btn:hover {
        border-color: #d1d5db;
        background: #f9fafb;
    }

    .filter-btn.active {
        background: #fe9630;
        color: white;
        border-color: #fe9630;
    }

    .notification-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .notification-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border-left: 4px solid #e5e7eb;
    }

    .notification-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .notification-card.error {
        border-left-color: #ef4444;
    }

    .notification-card.warning {
        border-left-color: #f59e0b;
    }

    .notification-card.info {
        border-left-color: #3b82f6;
    }

    .notification-card.success {
        border-left-color: #22c55e;
    }

    .notification-card.unread {
        background: rgba(254, 150, 48, 0.02);
    }

    .notification-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .notification-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.5rem;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #212121;
        margin: 0 0 0.5rem 0;
    }

    .notification-message {
        color: #6b7280;
        font-size: 0.95rem;
        margin: 0;
    }

    .notification-meta {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-top: 0.75rem;
        font-size: 0.875rem;
    }

    .notification-time {
        color: #9ca3af;
    }

    .notification-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .notification-badge.unread {
        background: #fe9630;
        color: white;
    }

    .notification-details {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 0.375rem;
        margin-top: 1rem;
        font-size: 0.875rem;
        color: #6b7280;
        border-left: 3px solid #fe9630;
    }

    .notification-details-label {
        font-weight: 600;
        color: #212121;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-box {
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #fe9630;
        margin: 0;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state-text {
        color: #9ca3af;
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .notification-header {
            gap: 0.75rem;
        }

        .notification-meta {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }

        .stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="notifications-container">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'All Notifications',
        'description' => 'View and manage all your notifications',
        'show_back' => true,
        'back_url' => URLROOT . '/installeradmin/dashboard',
        'back_label' => 'Back to Dashboard'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Statistics Cards -->
    <div class="stats-row">
        <div class="stat-box">
            <p class="stat-number"><?php echo count($allNotifications); ?></p>
            <p class="stat-label">Total Notifications</p>
        </div>
        <div class="stat-box">
            <p class="stat-number" style="color: #ef4444;"><?php echo $unreadCount; ?></p>
            <p class="stat-label">Unread</p>
        </div>
        <div class="stat-box">
            <p class="stat-number" style="color: #ef4444;">4</p>
            <p class="stat-label">Urgent Alerts</p>
        </div>
        <div class="stat-box">
            <p class="stat-number" style="color: #22c55e;">3</p>
            <p class="stat-label">Completed Tasks</p>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="notification-filters">
        <button class="filter-btn active" data-filter="all">All Notifications</button>
        <button class="filter-btn" data-filter="error">Critical</button>
        <button class="filter-btn" data-filter="warning">Warnings</button>
        <button class="filter-btn" data-filter="info">Info</button>
        <button class="filter-btn" data-filter="success">Success</button>
        <button class="filter-btn" data-filter="unread">Unread Only</button>
    </div>

    <!-- Notifications List -->
    <div class="notification-list" id="notifications-list">
        <?php foreach ($allNotifications as $notif): ?>
        <div class="notification-card <?php echo $notif['type']; ?> <?php echo (!isset($notif['is_read']) || !$notif['is_read']) ? 'unread' : ''; ?>" data-type="<?php echo htmlspecialchars($notif['type']); ?>" data-read="<?php echo (isset($notif['is_read']) && $notif['is_read']) ? '1' : '0'; ?>">
            <div class="notification-header">
                <div class="notification-icon" style="background-color: <?php echo getStatusBgColor($notif['type']); ?>;">
                    <i class="<?php echo htmlspecialchars($notif['icon']); ?>" style="color: <?php echo getStatusColor($notif['type']); ?>;"></i>
                </div>
                <div class="notification-content">
                    <h3 class="notification-title"><?php echo htmlspecialchars($notif['title']); ?></h3>
                    <p class="notification-message"><?php echo htmlspecialchars($notif['message']); ?></p>
                    <div class="notification-meta">
                        <span class="notification-time">
                            <i class="fas fa-clock mr-2"></i><?php echo htmlspecialchars($notif['timestamp']); ?>
                        </span>
                        <?php if (!isset($notif['is_read']) || !$notif['is_read']): ?>
                        <span class="notification-badge unread">Unread</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="notification-details">
                <div class="notification-details-label">Details:</div>
                <?php echo htmlspecialchars($notif['details']); ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const notificationCards = document.querySelectorAll('.notification-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;

            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Filter notifications
            notificationCards.forEach(card => {
                let show = false;

                if (filter === 'all') {
                    show = true;
                } else if (filter === 'unread') {
                    show = card.dataset.read === '0';
                } else {
                    show = card.dataset.type === filter;
                }

                card.style.display = show ? '' : 'none';
            });
        });
    });

    // Mark as read on click
    notificationCards.forEach(card => {
        card.addEventListener('click', function() {
            if (this.classList.contains('unread')) {
                this.classList.remove('unread');
                const badge = this.querySelector('.notification-badge.unread');
                if (badge) {
                    badge.style.display = 'none';
                }
            }
        });
    });
});
</script>
