<?php
// Generic summary card used by multiple roles.
// Expects $card array with keys: title|label, value, icon (optional), change|hint (optional)
?>
<div class="col-lg-3 col-md-6 mb-4">
    <div class="card shadow-md rounded-xl h-100 p-3 text-center">
        <?php if (!empty($card['icon'])): ?><i class="<?php echo htmlspecialchars($card['icon']); ?> text-3xl text-secondary mb-3"></i><?php endif; ?>
        <div class="text-2xl font-bold"><?php echo htmlspecialchars($card['value']); ?></div>
        <div class="text-muted text-sm mb-1"><?php echo htmlspecialchars($card['title'] ?? $card['label'] ?? ''); ?></div>
        <?php if (!empty($card['change'])): ?>
            <div class="text-sm text-secondary">Change: <?php echo htmlspecialchars($card['change']); ?></div>
        <?php elseif (!empty($card['hint'])): ?>
            <div class="text-xs text-secondary"><?php echo htmlspecialchars($card['hint']); ?></div>
        <?php endif; ?>
    </div>
</div>
