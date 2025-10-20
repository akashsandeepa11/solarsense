<?php
/**
 * Stats Grid Component
 * 
 * Displays multiple stat cards in a responsive grid
 * 
 * @param array $config Configuration array:
 *   - stats (array): Array of stat configurations (same as stat_card component)
 *   - columns (int, optional): Number of columns - default: 4 (responsive)
 */

if (!isset($config)) {
    $config = [];
}

$stats = isset($config['stats']) ? $config['stats'] : [];
$columns = isset($config['columns']) ? (int)$config['columns'] : 4;
?>

<div class="stats-grid">
    <?php foreach ($stats as $stat): ?>
        <?php 
        $config = $stat;
        include __DIR__ . '/stat_card.php';
        ?>
    <?php endforeach; ?>
</div>
