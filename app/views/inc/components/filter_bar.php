<?php
/**
 * Filter Bar Component
 * 
 * Flexible filter bar with search, dropdowns, and action buttons
 * 
 * @param array $config Configuration array:
 *   - search (array, optional): Search config:
 *       - id (string): Input ID
 *       - placeholder (string): Placeholder text
 *   - filters (array, optional): Array of filter dropdowns:
 *       - id (string): Select ID
 *       - label (string): Label text
 *       - options (array): Option arrays with 'value' and 'label' keys
 *   - buttons (array, optional): Array of action buttons:
 *       - label (string): Button label
 *       - icon (string, optional): Font Awesome icon
 *       - class (string, optional): CSS classes
 *       - id (string, optional): Element ID
 */

if (!isset($config)) {
    $config = [];
}

$search = $config['search'] ?? null;
$filters = $config['filters'] ?? [];
$buttons = $config['buttons'] ?? [];
$hasContent = $search || !empty($filters);
$itemCount = ($search ? 1 : 0) + count($filters) + (empty($buttons) ? 0 : 1);
?>

<div class="filter-bar">
    <div class="row">
        <?php if ($search): ?>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label"><?php echo htmlspecialchars($search['label'] ?? 'Search'); ?></label>
                    <div style="position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; z-index: 1;"></i>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="<?php echo htmlspecialchars($search['id'] ?? 'search'); ?>"
                            placeholder="<?php echo htmlspecialchars($search['placeholder'] ?? 'Search...'); ?>"
                            style="padding-left: 36px;"
                        >
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php foreach ($filters as $filter): ?>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label"><?php echo htmlspecialchars($filter['label'] ?? 'Filter'); ?></label>
                    <select class="form-control" id="<?php echo htmlspecialchars($filter['id'] ?? ''); ?>">
                        <?php foreach ($filter['options'] as $option): ?>
                            <option value="<?php echo htmlspecialchars($option['value']); ?>">
                                <?php echo htmlspecialchars($option['label']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (!empty($buttons)): ?>
            <div class="col-md-2" style="display: flex; flex-direction: column; justify-content: flex-end;">
                <div style="display: flex; gap: 0.5rem;">
                    <?php foreach ($buttons as $button): ?>
                        <button class="btn btn-secondary" id="<?php echo htmlspecialchars($button['id'] ?? ''); ?>">
                            <?php if (!empty($button['icon'])): ?>
                                <i class="<?php echo htmlspecialchars($button['icon']); ?> mr-2"></i>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($button['label'] ?? 'Action'); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
