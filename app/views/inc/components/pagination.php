<?php
/**
 * Pagination Component
 * 
 * Flexible pagination component with page info and navigation
 * 
 * @param array $config Configuration array:
 *   - current_page (int): Current page number
 *   - total_pages (int): Total number of pages
 *   - total_items (int): Total items count
 *   - per_page (int): Items per page
 *   - url_pattern (string): URL pattern with {page} placeholder
 *   - show_info (bool, optional): Show "Showing X to Y of Z" text - default: true
 */

if (!isset($config)) {
    $config = [];
}

$currentPage = isset($config['current_page']) ? (int)$config['current_page'] : 1;
$totalPages = isset($config['total_pages']) ? (int)$config['total_pages'] : 1;
$totalItems = isset($config['total_items']) ? (int)$config['total_items'] : 0;
$perPage = isset($config['per_page']) ? (int)$config['per_page'] : 10;
$urlPattern = isset($config['url_pattern']) ? $config['url_pattern'] : '';
$showInfo = isset($config['show_info']) ? $config['show_info'] : true;

// Calculate start and end items
$startItem = ($currentPage - 1) * $perPage + 1;
$endItem = min($currentPage * $perPage, $totalItems);
?>

<div class="pagination-section card-footer d-flex align-center justify-between">
    <?php if ($showInfo): ?>
        <div class="pagination-info text-secondary text-sm">
            Showing <?php echo htmlspecialchars($startItem); ?> to <?php echo htmlspecialchars($endItem); ?> of <?php echo htmlspecialchars($totalItems); ?> results
        </div>
    <?php endif; ?>

    <?php if ($totalPages > 1): ?>
        <div class="pagination d-flex gap-2">
            <!-- Previous Button -->
            <?php if ($currentPage > 1): ?>
                <a href="<?php echo htmlspecialchars(str_replace('{page}', $currentPage - 1, $urlPattern)); ?>" 
                   class="btn btn-sm btn-secondary">
                    <i class="fas fa-chevron-left"></i>
                </a>
            <?php else: ?>
                <button class="btn btn-sm btn-secondary" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php
            $startPageNum = max(1, $currentPage - 2);
            $endPageNum = min($totalPages, $currentPage + 2);
            
            if ($startPageNum > 1): ?>
                <a href="<?php echo htmlspecialchars(str_replace('{page}', 1, $urlPattern)); ?>" 
                   class="btn btn-sm btn-secondary">1</a>
                <?php if ($startPageNum > 2): ?>
                    <span class="btn btn-sm" disabled>...</span>
                <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $startPageNum; $i <= $endPageNum; $i++): ?>
                <?php if ($i === $currentPage): ?>
                    <button class="btn btn-sm btn-primary" disabled><?php echo htmlspecialchars($i); ?></button>
                <?php else: ?>
                    <a href="<?php echo htmlspecialchars(str_replace('{page}', $i, $urlPattern)); ?>" 
                       class="btn btn-sm btn-secondary"><?php echo htmlspecialchars($i); ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($endPageNum < $totalPages): ?>
                <?php if ($endPageNum < $totalPages - 1): ?>
                    <span class="btn btn-sm" disabled>...</span>
                <?php endif; ?>
                <a href="<?php echo htmlspecialchars(str_replace('{page}', $totalPages, $urlPattern)); ?>" 
                   class="btn btn-sm btn-secondary"><?php echo htmlspecialchars($totalPages); ?></a>
            <?php endif; ?>

            <!-- Next Button -->
            <?php if ($currentPage < $totalPages): ?>
                <a href="<?php echo htmlspecialchars(str_replace('{page}', $currentPage + 1, $urlPattern)); ?>" 
                   class="btn btn-sm btn-secondary">
                    <i class="fas fa-chevron-right"></i>
                </a>
            <?php else: ?>
                <button class="btn btn-sm btn-secondary" disabled>
                    <i class="fas fa-chevron-right"></i>
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
