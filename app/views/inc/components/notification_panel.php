<?php
/**
 * Notification Panel Component
 * 
 * Dropdown notification panel with notifications list
 * 
 * @param array $config Configuration array:
 *   - notifications (array): Array of notification objects with:
 *       - id (int): Notification ID
 *       - type (string): Type (error, warning, info, success)
 *       - icon (string): Font Awesome icon class
 *       - title (string): Notification title
 *       - message (string): Notification description
 *       - timestamp (string): Time display (e.g., "2 min ago")
 *       - is_read (bool): Whether notification is read
 *   - badge_count (int): Number to display on badge
 *   - view_all_url (string): URL to view all notifications
 */

if (!isset($config)) {
    $config = [];
}

$notifications = isset($config['notifications']) ? $config['notifications'] : [];
$badgeCount = isset($config['badge_count']) ? $config['badge_count'] : 0;
$viewAllUrl = isset($config['view_all_url']) ? $config['view_all_url'] : '#';

function getNotificationBgColor($type) {
    switch ($type) {
        case 'error': return '#fee2e2';
        case 'warning': return '#fed7aa';
        case 'info': return '#dbeafe';
        case 'success': return '#d1fae5';
        default: return '#f3f4f6';
    }
}

function getNotificationIconColor($type) {
    switch ($type) {
        case 'error': return '#ef4444';
        case 'warning': return '#f59e0b';
        case 'info': return '#3b82f6';
        case 'success': return '#22c55e';
        default: return '#6b7280';
    }
}

function getNotificationBadgeColor($type) {
    switch ($type) {
        case 'error': return '#ef4444';
        case 'warning': return '#f59e0b';
        case 'info': return '#3b82f6';
        case 'success': return '#22c55e';
        default: return '#6b7280';
    }
}
?>

<!-- Notification Button -->
<div class="notification-container" style="position: relative;">
    <button class="btn border-0 navbar-icon-btn mr-3" id="notification-btn" style="position: relative;">
        <i class="fas fa-regular fa-bell"></i>
        <?php if ($badgeCount > 0): ?>
            <span class="notification-badge" id="notification-badge" style="position: absolute; top: 0; right: 0; background-color: #ef4444; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600;">
                <?php echo $badgeCount > 9 ? '9+' : $badgeCount; ?>
            </span>
        <?php endif; ?>
    </button>

    <!-- Notification Dropdown Panel -->
    <div class="notification-panel" id="notification-panel" style="
        position: absolute;
        top: 100%;
        right: 0;
        width: 380px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        margin-top: 0.5rem;
        z-index: 1000;
        display: none;
        max-height: 500px;
        overflow-y: auto;
    ">
        <!-- Panel Header -->
        <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: white; z-index: 10;">
            <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #212121;">Notifications</h3>
            <button id="clear-notifications-btn" class="btn border-0" style="background: none; color: #6b7280; cursor: pointer; font-size: 0.875rem;" title="Clear all">
                <i class="fas fa-trash"></i>
            </button>
        </div>

        <!-- Notifications List -->
        <div id="notifications-list">
            <?php if (empty($notifications)): ?>
                <div style="padding: 2rem; text-align: center; color: #9ca3af;">
                    <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                    No notifications
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notif): ?>
                    <div class="notification-item" data-id="<?php echo isset($notif['id']) ? htmlspecialchars($notif['id']) : ''; ?>" style="
                        padding: 1rem;
                        border-bottom: 1px solid #f3f4f6;
                        cursor: pointer;
                        transition: background-color 0.2s;
                        display: flex;
                        gap: 0.75rem;
                        <?php echo (isset($notif['is_read']) && $notif['is_read']) ? 'opacity: 0.7;' : ''; ?>
                    ">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background-color: <?php echo getNotificationBgColor($notif['type']); ?>; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="<?php echo htmlspecialchars($notif['icon']); ?>" style="color: <?php echo getNotificationIconColor($notif['type']); ?>; font-size: 1.25rem;"></i>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 600; color: #212121; font-size: 0.95rem;"><?php echo htmlspecialchars($notif['title']); ?></div>
                            <div style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; word-wrap: break-word;"><?php echo htmlspecialchars($notif['message']); ?></div>
                            <div style="color: #9ca3af; font-size: 0.75rem; margin-top: 0.5rem;"><?php echo htmlspecialchars($notif['timestamp']); ?></div>
                        </div>
                        <?php if (!isset($notif['is_read']) || !$notif['is_read']): ?>
                            <div style="width: 8px; height: 8px; border-radius: 50%; background-color: <?php echo getNotificationBadgeColor($notif['type']); ?>; margin-top: 0.5rem; flex-shrink: 0;"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Panel Footer -->
        <div style="padding: 1rem; border-top: 1px solid #e5e7eb; text-align: center; position: sticky; bottom: 0; background: white; z-index: 10;">
            <a href="<?php echo htmlspecialchars($viewAllUrl); ?>" style="color: #fe9630; text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                View All Notifications →
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationBtn = document.getElementById('notification-btn');
    const notificationPanel = document.getElementById('notification-panel');
    const clearBtn = document.getElementById('clear-notifications-btn');
    const notificationsList = document.getElementById('notifications-list');
    const notificationBadge = document.getElementById('notification-badge');

    if (!notificationBtn || !notificationPanel) return;

    // Toggle notification panel on button click
    notificationBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationPanel.style.display = notificationPanel.style.display === 'none' ? 'block' : 'none';
    });

    // Close panel when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.notification-container')) {
            notificationPanel.style.display = 'none';
        }
    });

    // Prevent panel from closing when clicking inside it
    notificationPanel.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Add hover effect to notification items
    const notificationItems = document.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f9fafb';
        });
        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'transparent';
        });
        item.addEventListener('click', function() {
            // Mark as read by fading the item and removing badge dot
            this.style.opacity = '0.7';
            const badge = this.querySelector('[style*="background-color"]');
            if (badge && badge.style.width === '8px') {
                badge.style.display = 'none';
            }
        });
    });

    // Clear all notifications
    if (clearBtn) {
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (confirm('Clear all notifications?')) {
                notificationsList.innerHTML = '<div style="padding: 2rem; text-align: center; color: #9ca3af;"><i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>No notifications</div>';
                if (notificationBadge) {
                    notificationBadge.style.display = 'none';
                }
            }
        });
    }
});
</script>
