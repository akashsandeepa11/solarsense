<?php
/**
 * Data Table Component
 * 
 * Flexible table component for displaying tabular data with customizable columns and rows
 * 
 * @param array $config Configuration array:
 *   - headers (array): Column header definitions with 'key' and 'label'
 *   - rows (array): Array of row data arrays
 *   - columns (array, optional): Custom column renderers:
 *       - key (string): Column key
 *       - render (callable): Function to render cell content
 *   - actions (array, optional): Action buttons for each row:
 *       - label (string): Button text
 *       - icon (string): Font Awesome icon
 *       - url (string, optional): Link URL (use {id} placeholder)
 *       - onclick (string, optional): JavaScript onclick handler
 *       - class (string, optional): CSS classes
 *   - rowClass (string, optional): CSS class for table rows
 *   - empty_message (string, optional): Message when no data
 */

if (!isset($config)) {
    $config = [];
}

$headers = isset($config['headers']) ? $config['headers'] : [];
$rows = isset($config['rows']) ? $config['rows'] : [];
$customColumns = isset($config['columns']) ? $config['columns'] : [];
$actions = isset($config['actions']) ? $config['actions'] : [];
$rowClass = isset($config['rowClass']) ? $config['rowClass'] : 'data-table-row';
$emptyMessage = isset($config['empty_message']) ? $config['empty_message'] : 'No data available';

// Build custom column renderer map
$columnRenders = [];
foreach ($customColumns as $col) {
    $columnRenders[$col['key']] = isset($col['render']) ? $col['render'] : null;
}
?>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <?php foreach ($headers as $header): ?>
                            <th><?php echo htmlspecialchars($header['label']); ?></th>
                        <?php endforeach; ?>
                        <?php if (!empty($actions)): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="<?php echo count($headers) + (empty($actions) ? 0 : 1); ?>" class="text-center p-6 text-secondary">
                                <?php echo htmlspecialchars($emptyMessage); ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr class="<?php echo htmlspecialchars($rowClass); ?>">
                                <?php foreach ($headers as $header): ?>
                                    <td>
                                        <?php
                                        $key = $header['key'];
                                        if (isset($columnRenders[$key])) {
                                            echo $columnRenders[$key]($row);
                                        } else {
                                            echo htmlspecialchars($row[$key] ?? '');
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (!empty($actions)): ?>
                                    <td>
                                        <div class="actions-menu d-flex gap-2 justify-center">
                                            <?php foreach ($actions as $action): ?>
                                                <?php
                                                $actionUrl = '';
                                                if (isset($action['url']) && isset($row['id'])) {
                                                    $actionUrl = str_replace('{id}', $row['id'], $action['url']);
                                                }
                                                $actionClass = isset($action['class']) ? $action['class'] : '';
                                                $actionOnclick = isset($action['onclick']) ? $action['onclick'] : '';
                                                ?>
                                                <?php if ($actionUrl): ?>
                                                    <a href="<?php echo htmlspecialchars($actionUrl); ?>" 
                                                       class="btn-icon <?php echo htmlspecialchars($actionClass); ?>" 
                                                       title="<?php echo htmlspecialchars($action['label']); ?>">
                                                        <i class="<?php echo htmlspecialchars($action['icon']); ?>"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn-icon <?php echo htmlspecialchars($actionClass); ?>" 
                                                            title="<?php echo htmlspecialchars($action['label']); ?>"
                                                            <?php echo $actionOnclick; ?>>
                                                        <i class="<?php echo htmlspecialchars($action['icon']); ?>"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
