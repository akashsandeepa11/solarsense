<?php
/**
 * Stats Grid Component
 * 
 * Displays multiple statistic cards in a responsive grid
 * 
 * USAGE:
 * $config = [
 *     'stats' => [
 *         ['label' => 'Total Users', 'value' => '1,234', 'icon' => 'fas fa-users', 'color' => 'primary', 'trend' => ['direction' => 'up', 'percentage' => 12]],
 *         ['label' => 'Active', 'value' => '95', 'icon' => 'fas fa-check-circle', 'color' => 'success'],
 *     ],
 *     'columns' => 6
 * ];
 * include 'stat_card.php';
 * 
 * @param array $config Configuration array:
 *   - stats (array): Array of stat card configurations, each containing:
 *       - label (string): Card label/title
 *       - value (string|int): Stat value to display
 *       - icon (string): Font Awesome icon class
 *       - color (string, optional): Color class (primary, success, warning, error, accent) - default: 'primary'
 *       - trend (array, optional): Trend info:
 *           - direction (string): 'up' or 'down'
 *           - percentage (string|int): Trend percentage
 *   - columns (int, optional): Number of columns - default: 4 (responsive)
 */

if (!isset($config)) {
    $config = [];
}

$stats = isset($config['stats']) ? $config['stats'] : [];
$columns = isset($config['columns']) ? (int)$config['columns'] : 4;
?>

<style>
    /* Stat Card Component */
    .stat-card {
        background: #ffffff;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease-in-out;
        width: 100%;
    }

    .stat-card .d-flex {
        display: flex !important;
        align-items: center !important;
        gap: 1rem;
    }

    .stat-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        color: #ffffff;
        font-size: 1.5rem;
        flex-shrink: 0;
        background-color: #fe9630; /* default primary color */
    }

    /* Background color variants for stat icons */
    .stat-icon.bg-primary {
        background-color: #fe9630 !important;
    }

    .stat-icon.bg-success {
        background-color: #22c55e !important;
    }

    .stat-icon.bg-warning {
        background-color: #f59e0b !important;
    }

    .stat-icon.bg-error {
        background-color: #ef4444 !important;
    }

    .stat-icon.bg-accent {
        background-color: #00bcd4 !important;
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        display: block;
        font-size: 1.875rem;
        font-weight: 700;
        color: #212121;
        line-height: 1.2;
        margin: 0;
    }

    .stat-trend {
        margin-top: 0.5rem;
        font-size: 0.75rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)) !important;
        gap: 1.5rem !important;
        margin-bottom: 1.5rem;
        width: 100%;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .stat-card {
            padding: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
    }

    @media (max-width: 576px) {
        .stat-card {
            padding: 0.75rem;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            font-size: 1rem;
        }

        .stat-value {
            font-size: 1.25rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
    }
</style>

<div class="stats-grid">
    <?php foreach ($stats as $stat): ?>
        <?php 
        $label = $stat['label'] ?? 'Stat';
        $value = $stat['value'] ?? '0';
        $icon = $stat['icon'] ?? 'fas fa-chart-bar';
        $color = $stat['color'] ?? 'primary';
        $trend = $stat['trend'] ?? null;
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
    <?php endforeach; ?>
</div>
