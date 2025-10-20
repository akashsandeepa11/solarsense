<?php
/**
 * Stat Card Component
 * 
 * Displays a single statistic card with icon, label, and value
 * 
 * @param array $config Configuration array:
 *   - label (string): Card label/title
 *   - value (string|int): Stat value to display
 *   - icon (string): Font Awesome icon class
 *   - color (string, optional): Color class (primary, success, warning, error, accent) - default: 'primary'
 *   - trend (array, optional): Trend info:
 *       - direction (string): 'up' or 'down'
 *       - percentage (string|int): Trend percentage
 */

if (!isset($config)) {
    $config = [];
}

$label = $config['label'] ?? 'Stat';
$value = $config['value'] ?? '0';
$icon = $config['icon'] ?? 'fas fa-chart-bar';
$color = $config['color'] ?? 'primary';
$trend = $config['trend'] ?? null;
?>

<div class="stat-card">
    <div class="d-flex align-center gap-4" style="align-items: center;">
        <div class="stat-icon bg-<?php echo htmlspecialchars($color); ?>">
            <i class="<?php echo htmlspecialchars($icon); ?>"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label"><?php echo htmlspecialchars($label); ?></span>
            <span class="stat-value"><?php echo htmlspecialchars($value); ?></span>
            <?php if ($trend): ?>
                <div class="stat-trend text-sm">
                    <i class="fas fa-arrow-<?php echo $trend['direction'] === 'up' ? 'up' : 'down'; ?> mr-1"></i>
                    <span class="text-<?php echo $trend['direction'] === 'up' ? 'success' : 'error'; ?>">
                        <?php echo htmlspecialchars($trend['percentage']); ?>%
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
