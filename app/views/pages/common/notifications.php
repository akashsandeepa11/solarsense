<?php
/**
 * Full Notifications Page
 *
 * Receives from the controller:
 *   $data['notifications']  — array from M_Notification::get_all_notifications()
 */

$allNotifications = $data['notifications'] ?? [];

// Count by type for the stats row
$errorCount   = 0;
$successCount = 0;
foreach ($allNotifications as $n) {
    if (($n['type'] ?? '') === 'error'   || ($n['type'] ?? '') === 'warning') $errorCount++;
    if (($n['type'] ?? '') === 'success')                                     $successCount++;
}
?>

<link rel="stylesheet" href="<?php echo URLROOT?>/public/css/components.css">

<style>
    .notifications-container {
        background: #f9fafb;
        min-height: 100vh;
        padding: 2rem;
    }

    /* ── Filter bar ── */
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
    .filter-btn:hover  { border-color: #d1d5db; background: #f9fafb; }
    .filter-btn.active { background: #fe9630; color: white; border-color: #fe9630; }

    /* ── Cards ── */
    .notification-list { display: flex; flex-direction: column; gap: 1rem; }

    .notification-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .notification-card:hover      { box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); transform: translateY(-2px); }

    .notification-card.unread     { background: rgba(254,150,48,.02); }

    .notification-header  { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 0.5rem; }
    .notification-icon    { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.5rem; }
    .notification-content { flex: 1; }
    .notification-title   { font-size: 1.125rem; font-weight: 700; color: #212121; margin: 0 0 0.5rem 0; }
    .notification-message { color: #6b7280; font-size: 0.95rem; margin: 0; }
    .notification-meta    { display: flex; gap: 1rem; align-items: center; margin-top: 0.75rem; font-size: 0.875rem; }
    .notification-time    { color: #9ca3af; }
    .notification-badge-label {
        display: inline-block; padding: 0.25rem 0.75rem;
        border-radius: 9999px; font-size: 0.75rem; font-weight: 600;
        background: #fe9630; color: white;
    }

    /* ── Stats row ── */
    .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
    .stat-box  { background: white; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; text-align: center; }
    .stat-number { font-size: 2rem; font-weight: 700; color: #fe9630; margin: 0; }
    .stat-label  { font-size: 0.875rem; color: #6b7280; margin-top: 0.5rem; }

    /* ── Empty state ── */
    .empty-state      { text-align: center; padding: 3rem 2rem; background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; }
    .empty-state-icon { font-size: 3rem; color: #d1d5db; margin-bottom: 1rem; }
    .empty-state-text { color: #9ca3af; font-size: 1rem; }

    /* ── Action bar ── */
    .notif-actions { display: flex; gap: 0.75rem; margin-bottom: 1.5rem; justify-content: flex-end; }
    .notif-action-btn {
        padding: 0.5rem 1rem; border-radius: 0.375rem; border: 1px solid #e5e7eb;
        background: white; cursor: pointer; font-size: 0.875rem; font-weight: 500;
        color: #374151; transition: all 0.2s;
    }
    .notif-action-btn:hover { background: #f3f4f6; }
    .notif-action-btn.danger { color: #ef4444; border-color: #fca5a5; }
    .notif-action-btn.danger:hover { background: #fee2e2; }

    @media (max-width: 768px) {
        .notification-meta { flex-direction: column; gap: 0.5rem; align-items: flex-start; }
        .stats-row         { grid-template-columns: 1fr; }
    }
</style>

<div class="notifications-container">

    <!-- Page Header -->
    <?php
    $config = [
        'title'       => 'All Notifications',
        'description' => 'View and manage all your notifications',
        'show_back'   => true,
        'back_url'    => isset($_SERVER['HTTP_REFERER'])
                            ? $_SERVER['HTTP_REFERER']
                            : URLROOT . '/' . $data['user']['role'] . '/dashboard',
        'back_label'  => 'Back to Dashboard',
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
            <p class="stat-number" style="color: #f59e0b;"><?php echo $errorCount; ?></p>
            <p class="stat-label">Alerts &amp; Warnings</p>
        </div>
        <div class="stat-box">
            <p class="stat-number" style="color: #22c55e;"><?php echo $successCount; ?></p>
            <p class="stat-label">Resolved</p>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="notif-actions">
        <button class="notif-action-btn danger" id="clear-all-btn">
            <i class="fas fa-trash mr-1"></i> Clear All
        </button>
    </div>

    <!-- Filter Buttons -->
    <div class="notification-filters">
        <button class="filter-btn active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="error">Critical</button>
        <button class="filter-btn" data-filter="warning">Warnings</button>
        <button class="filter-btn" data-filter="info">Info</button>
        <button class="filter-btn" data-filter="success">Success</button>
    </div>

    <!-- Notifications List -->
    <div class="notification-list" id="notifications-list">
        <?php if (empty($allNotifications)): ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                <p class="empty-state-text">You have no notifications.</p>
            </div>
        <?php else: ?>
            <?php foreach ($allNotifications as $notif):
                $type    = $notif['type'] ?? 'info';
                $icon    = !empty($notif['icon']) ? $notif['icon'] : (NOTIFICATION_ICONS[$type] ?? 'fas fa-bell');
                $bgColor = NOTIFICATION_BG_COLORS[$type]   ?? '#f3f4f6';
                $icColor = NOTIFICATION_ICON_COLORS[$type] ?? '#6b7280';
            ?>
            <div class="notification-card <?php echo htmlspecialchars($type); ?>"
                 data-id="<?php echo (int) ($notif['id'] ?? 0); ?>"
                 data-type="<?php echo htmlspecialchars($type); ?>">

                <div class="notification-header">
                    <div class="notification-icon" style="background-color: <?php echo $bgColor; ?>;">
                        <i class="<?php echo htmlspecialchars($icon); ?>"
                           style="color: <?php echo $icColor; ?>;"></i>
                    </div>
                    <div class="notification-content">
                        <h3 class="notification-title"><?php echo htmlspecialchars($notif['title'] ?? ''); ?></h3>
                        <p class="notification-message"><?php echo htmlspecialchars($notif['message'] ?? ''); ?></p>
                        <div class="notification-meta">
                            <span class="notification-time">
                                <i class="fas fa-clock mr-1"></i>
                                <?php echo htmlspecialchars($notif['timestamp'] ?? ''); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    const baseUrl    = '<?php echo URLROOT . '/' . strtolower($data['user']['role'] ?? 'homeowner'); ?>';
    const filterBtns = document.querySelectorAll('.filter-btn');
    const list       = document.getElementById('notifications-list');

    // ── Filter ────────────────────────────────────────────────────────────────
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;

            list.querySelectorAll('.notification-card').forEach(card => {
                let show = false;
                if      (filter === 'all') show = true;
                else                      show = card.dataset.type === filter;
                card.style.display = show ? '' : 'none';
            });
        });
    });

    // ── Clear All ─────────────────────────────────────────────────────────────
    const clearAllBtn = document.getElementById('clear-all-btn');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function () {
            if (!confirm('Clear all notifications? This cannot be undone.')) return;

            fetch(baseUrl + '/clearNotifications', { method: 'POST' })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        list.innerHTML =
                            '<div class="empty-state">' +
                            '<div class="empty-state-icon"><i class="fas fa-inbox"></i></div>' +
                            '<p class="empty-state-text">You have no notifications.</p>' +
                            '</div>';
                    }
                });
        });
    }
})();
</script>
