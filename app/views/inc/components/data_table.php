<?php
/**
 * Flexible Data Table Component
 * Supports both associative arrays and stdClass objects for row data.
 */

if (!isset($config)) {
    $config = [];
}

$headers       = isset($config['headers']) ? $config['headers'] : [];
$rows          = isset($config['rows']) ? $config['rows'] : [];
$customColumns = isset($config['columns']) ? $config['columns'] : [];
$actions       = isset($config['actions']) ? $config['actions'] : [];
$rowClass      = isset($config['rowClass']) ? $config['rowClass'] : 'data-table-row';
$emptyMessage  = isset($config['empty_message']) ? $config['empty_message'] : 'No data available';

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
                            <th class="text-center">Actions</th>
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
                                            // Pass the original row (object or array) to the custom renderer
                                            echo $columnRenders[$key]($row);
                                        } else {
                                            // Handle both object property and array key access
                                            $val = is_object($row) ? ($row->$key ?? '') : ($row[$key] ?? '');
                                            echo htmlspecialchars($val);
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>

                                <?php if (!empty($actions)): ?>
                                    <td>
                                        <div class="actions-menu d-flex gap-2 justify-center">
                                            <?php foreach ($actions as $action): ?>
                                                <?php
                                                // Determine if an action should be shown based on conditions
                                                if (isset($action['condition']) && is_callable($action['condition'])) {
                                                    if (!$action['condition']($row)) continue;
                                                }

                                                $actionUrl = isset($action['url']) ? $action['url'] : '';
                                                $actionOnclick = isset($action['onclick']) ? $action['onclick'] : '';
                                                
                                                // Convert row to array for dynamic placeholder replacement
                                                $rowData = is_object($row) ? get_object_vars($row) : $row;
                                                
                                                foreach ($rowData as $k => $v) {
                                                    if (is_scalar($v)) { // Only replace strings/numbers
                                                        $placeholder = '{' . $k . '}';
                                                        if ($actionUrl) $actionUrl = str_replace($placeholder, $v, $actionUrl);
                                                        if ($actionOnclick) $actionOnclick = str_replace($placeholder, $v, $actionOnclick);
                                                    }
                                                }

                                                $actionClass = isset($action['class']) ? $action['class'] : '';
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