<?php
/**
 * Notification Panel Component
 *
 * Dropdown notification panel — icon and colours are derived from `type`
 * using the NOTIFICATION_* constants defined in constants.php.
 *
 * @param array $config
 *   - notifications (array): Each entry has: id, type, title, message, timestamp
 *   - view_all_url  (string): URL to the full notifications page
 */

if (!isset($config)) {
    $config = [];
}

$notifications = $config['notifications'] ?? [];
$viewAllUrl    = $config['view_all_url']  ?? '#';

/**
 * Resolve the Font Awesome icon class for a notification type.
 * Uses the NOTIFICATION_ICONS constant; falls back to a bell icon.
 */
function notifIcon(string $type): string {
    return NOTIFICATION_ICONS[$type] ?? 'fas fa-bell';
}

/**
 * Resolve the background colour for the icon circle.
 */
function notifBg(string $type): string {
    return NOTIFICATION_BG_COLORS[$type] ?? '#f3f4f6';
}

/**
 * Resolve the icon foreground colour.
 */
function notifIconColor(string $type): string {
    return NOTIFICATION_ICON_COLORS[$type] ?? '#6b7280';
}

/**
 * Resolve the unread dot colour.
 */
function notifBadgeColor(string $type): string {
    return NOTIFICATION_BADGE_COLORS[$type] ?? '#6b7280';
}
?>

<!-- Notification Button -->
<div class="notification-container" style="position: relative;">
    <button class="btn border-0 navbar-icon-btn mr-3" id="notification-btn" style="position: relative;">
        <i class="fas fa-regular fa-bell"></i>
        <?php if (!empty($notifications)): ?>
        <span id="notif-badge" style="
            position: absolute; top: 2px; right: 2px;
            background: #ef4444; color: #fff;
            font-size: 0.65rem; font-weight: 700;
            min-width: 18px; height: 18px;
            border-radius: 9999px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 4px; line-height: 1;
            pointer-events: none;
            box-shadow: 0 0 0 2px #fff;
        "><?php echo count($notifications) > 99 ? '99+' : count($notifications); ?></span>
        <?php endif; ?>
    </button>

    <!-- Notification Dropdown Panel -->
    <div class="notification-panel" id="notification-panel" style="
        position: absolute; top: 100%; right: 0;
        width: 380px; background: white;
        border: 1px solid #e5e7eb; border-radius: 0.75rem;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        margin-top: 0.5rem; z-index: 1000;
        display: none; max-height: 500px; overflow-y: auto;
    ">
        <!-- Panel Header -->
        <div style="
            padding: 1rem; border-bottom: 1px solid #e5e7eb;
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; background: white; z-index: 10;
        ">
            <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #212121;">
                Notifications
            </h3>
            <button id="clear-notifications-btn" class="btn border-0"
                    style="background: none; color: #6b7280; cursor: pointer; font-size: 0.875rem;"
                    title="Clear all">
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
                <?php foreach ($notifications as $notif):
                    $type = $notif['type'] ?? 'info';
                    // Icon comes from the model (already set) or fall back to constant
                    $icon = !empty($notif['icon']) ? $notif['icon'] : notifIcon($type);
                ?>
                    <div class="notification-item"
                         data-id="<?php echo (int) ($notif['id'] ?? 0); ?>"
                         data-url="<?php echo URLROOT; ?>/homeowner/markNotificationRead"
                         style="
                            padding: 1rem; border-bottom: 1px solid #f3f4f6;
                            cursor: pointer; transition: background-color 0.2s;
                            display: flex; gap: 0.75rem;
                         ">
                        <!-- Icon circle -->
                        <div style="
                            width: 40px; height: 40px; border-radius: 50%;
                            background-color: <?php echo notifBg($type); ?>;
                            display: flex; align-items: center; justify-content: center;
                            flex-shrink: 0;
                        ">
                            <i class="<?php echo htmlspecialchars($icon); ?>"
                               style="color: <?php echo notifIconColor($type); ?>; font-size: 1.25rem;"></i>
                        </div>

                        <!-- Content -->
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 600; color: #212121; font-size: 0.95rem;">
                                <?php echo htmlspecialchars($notif['title'] ?? ''); ?>
                            </div>
                            <div style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; word-wrap: break-word;">
                                <?php echo htmlspecialchars($notif['message'] ?? ''); ?>
                            </div>
                            <div style="color: #9ca3af; font-size: 0.75rem; margin-top: 0.5rem;">
                                <?php echo htmlspecialchars($notif['timestamp'] ?? ''); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Panel Footer: View All link -->
        <?php if (!empty($notifications)): ?>
        <div style="
            padding: 0.75rem 1rem; border-top: 1px solid #e5e7eb;
            text-align: center; position: sticky; bottom: 0;
            background: white; z-index: 10;
        ">
            <a href="<?php echo htmlspecialchars($viewAllUrl); ?>"
               style="color: #fe9630; text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                View All Notifications →
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    const notificationBtn   = document.getElementById('notification-btn');
    const notificationPanel = document.getElementById('notification-panel');
    const clearBtn          = document.getElementById('clear-notifications-btn');
    const notificationsList = document.getElementById('notifications-list');

    if (!notificationBtn || !notificationPanel) return;

    // ── Toggle panel ──────────────────────────────────────────────────────────
    notificationBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        const isHidden = notificationPanel.style.display === 'none' || notificationPanel.style.display === '';
        notificationPanel.style.display = isHidden ? 'block' : 'none';

        // Hide the badge once the user opens the panel
        const badge = document.getElementById('notif-badge');
        if (badge && isHidden) badge.style.display = 'none';
    });

    // Close panel when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.notification-container')) {
            notificationPanel.style.display = 'none';
        }
    });

    // Prevent panel from closing when clicking inside it
    notificationPanel.addEventListener('click', function (e) {
        e.stopPropagation();
    });

    // ── Hover effect ─────────────────────────────────────────────────────────
    notificationsList.addEventListener('mouseover', function (e) {
        const item = e.target.closest('.notification-item');
        if (item) item.style.backgroundColor = '#f9fafb';
    });

    notificationsList.addEventListener('mouseout', function (e) {
        const item = e.target.closest('.notification-item');
        if (item) item.style.backgroundColor = 'transparent';
    });

    // ── Clear all notifications ───────────────────────────────────────────────
    if (clearBtn) {
        clearBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (!confirm('Clear all notifications?')) return;

            const sampleItem = notificationsList.querySelector('.notification-item');
            const markUrl    = sampleItem ? sampleItem.dataset.url : '';
            const clearUrl   = markUrl
                ? markUrl.replace('markNotificationRead', 'clearNotifications')
                : window.location.origin + '/homeowner/clearNotifications';

            fetch(clearUrl, { method: 'POST' })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        notificationsList.innerHTML =
                            '<div style="padding: 2rem; text-align: center; color: #9ca3af;">' +
                            '<i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>' +
                            'No notifications</div>';
                        // Also hide badge after clearing
                        const badge = document.getElementById('notif-badge');
                        if (badge) badge.style.display = 'none';
                    }
                })
                .catch(() => {
                    notificationsList.innerHTML =
                        '<div style="padding: 2rem; text-align: center; color: #9ca3af;">' +
                        '<i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>' +
                        'No notifications</div>';
                });
        });
    }
})();
</script>
