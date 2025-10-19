<?php
// Generic alert item used by multiple roles.
// Expects $alert with keys: date|time, description|issue, client (optional)
?>
<div class="alert-item d-flex align-center py-3">
    <i class="fas fa-exclamation-circle text-warning text-xl mr-4"></i>
    <div class="flex-1">
        <?php if (!empty($alert['client'])): ?><div class="font-semibold"><?php echo htmlspecialchars($alert['client']); ?></div><?php endif; ?>
        <div class="text-sm text-secondary"><?php echo htmlspecialchars($alert['description'] ?? $alert['issue'] ?? ''); ?></div>
    </div>
    <?php if (!empty($alert['time']) || !empty($alert['date'])): ?>
        <div class="text-xs text-muted ml-3"><?php echo htmlspecialchars($alert['time'] ?? $alert['date']); ?></div>
    <?php endif; ?>
</div>
