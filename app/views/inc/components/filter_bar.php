<?php
/**
 * Filter Bar Component
 * 
 * Flexible, fully functional filter bar with search, dropdowns, and action buttons
 * Supports real-time filtering, form submission, and custom callbacks
 * 
 * @param array $config Configuration array:
 *   - search (array, optional): Search config:
 *       - id (string): Input ID
 *       - name (string): Input name for form submission
 *       - placeholder (string): Placeholder text
 *       - label (string, optional): Label text
 *   - filters (array, optional): Array of filter dropdowns:
 *       - id (string): Select ID
 *       - name (string): Input name for form submission
 *       - label (string): Label text
 *       - options (array): Option arrays with 'value' and 'label' keys
 *   - buttons (array, optional): Array of action buttons:
 *       - label (string): Button label
 *       - icon (string, optional): Font Awesome icon
 *       - class (string, optional): CSS classes
 *       - id (string, optional): Element ID
 *       - type (string, optional): Button type (submit, reset, button)
 *   - form_action (string, optional): Form action URL
 *   - form_method (string, optional): Form method (GET, POST)
 *   - auto_submit (bool, optional): Auto-submit on filter change (default: false)
 *   - reset_on_clear (bool, optional): Show reset button (default: true)
 *   - result_count (bool, optional): Show result count (default: false)
 *   - result_count_id (string, optional): ID for result count span (default: 'resultCount')
 */

if (!isset($config)) {
    $config = [];
}

$search = $config['search'] ?? null;
$filters = $config['filters'] ?? [];
$buttons = $config['buttons'] ?? [];
$formAction = $config['form_action'] ?? '#';
$formMethod = $config['form_method'] ?? 'GET';
$autoSubmit = $config['auto_submit'] ?? false;
$resetOnClear = $config['reset_on_clear'] ?? true;
$resultCount = $config['result_count'] ?? false;
$resultCountId = $config['result_count_id'] ?? 'resultCount';
$hasContent = $search || !empty($filters);
$formId = 'filter-form-' . uniqid();
?>

<style>
    /* Filter Bar Component */
    .filter-bar {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-bar .row {
        display: flex !important;
        flex-wrap: wrap;
        margin-left: -0.5rem !important;
        margin-right: -0.5rem !important;
        gap: 0 !important;
        align-items: flex-end;
    }

    .filter-bar [class*="col-"] {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .filter-bar .form-group {
        margin-bottom: 0;
        width: 100%;
    }

    .filter-bar .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #212121;
        margin-bottom: 0.5rem;
        display: block;
    }

    .filter-bar .form-control {
        width: 100%;
        padding: 0.625rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.95rem;
        color: #212121;
        background-color: #ffffff;
        transition: all 0.2s ease-in-out;
    }

    .filter-bar .form-control:focus {
        outline: none;
        border-color: #fe9630;
        box-shadow: 0 0 0 3px rgba(254, 150, 48, 0.1);
    }

    .filter-bar .form-control::placeholder {
        color: #9ca3af;
    }

    /* Search Input with Icon */
    .search-input-wrapper {
        position: relative;
    }

    .search-input-wrapper i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        z-index: 1;
        font-size: 0.9rem;
    }

    .search-input-wrapper .form-control {
        padding-left: 36px;
    }

    /* Filter Buttons */
    .filter-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-bar .btn {
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .filter-bar .btn-secondary {
        background-color: #f3f4f6;
        color: #212121;
        border: 1px solid #d1d5db;
    }

    .filter-bar .btn-secondary:hover {
        background-color: #e5e7eb;
        border-color: #9ca3af;
    }

    .filter-bar .btn-primary {
        background-color: #fe9630;
        color: #ffffff;
    }

    .filter-bar .btn-primary:hover {
        background-color: #f08c1c;
    }

    .filter-bar .btn-danger {
        background-color: #ef4444;
        color: #ffffff;
    }

    .filter-bar .btn-danger:hover {
        background-color: #dc2626;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .filter-bar {
            padding: 1rem;
        }

        .filter-bar .row {
            flex-direction: column;
        }

        .filter-bar [class^="col-"] {
            width: 100%;
            margin-bottom: 1rem;
        }

        .filter-actions {
            width: 100%;
        }

        .filter-actions .btn {
            flex: 1;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .filter-bar {
            padding: 0.75rem;
        }

        .filter-bar .form-label {
            font-size: 0.8rem;
        }

        .filter-bar .form-control {
            font-size: 0.9rem;
            padding: 0.5rem;
        }

        .filter-bar .btn {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
    }
</style>

<div class="filter-bar">
    <form id="<?php echo $formId; ?>" action="<?php echo htmlspecialchars($formAction); ?>" method="<?php echo strtoupper($formMethod); ?>" style="width: 100%;">
        <div class="row">
            <?php if ($search): ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <?php if (!empty($search['label'])): ?>
                            <label class="form-label"><?php echo htmlspecialchars($search['label']); ?></label>
                        <?php endif; ?>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="<?php echo htmlspecialchars($search['id'] ?? 'search'); ?>"
                                name="<?php echo htmlspecialchars($search['name'] ?? 'search'); ?>"
                                placeholder="<?php echo htmlspecialchars($search['placeholder'] ?? 'Search...'); ?>"
                            >
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php foreach ($filters as $filter): ?>
                <div class="col-md-<?php echo !empty($filters) && count($filters) > 1 ? '2' : '3'; ?>">
                    <div class="form-group">
                        <label class="form-label"><?php echo htmlspecialchars($filter['label'] ?? 'Filter'); ?></label>
                        <select 
                            class="form-control" 
                            id="<?php echo htmlspecialchars($filter['id'] ?? ''); ?>"
                            name="<?php echo htmlspecialchars($filter['name'] ?? ''); ?>"
                        >
                            <?php foreach ($filter['options'] as $option): ?>
                                <option value="<?php echo htmlspecialchars($option['value']); ?>">
                                    <?php echo htmlspecialchars($option['label']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if ($resultCount): ?>
                <div class="col-md-<?php echo count($filters) > 0 ? '2' : '3'; ?>" style="display: flex; flex-direction: column; justify-content: flex-end;">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex align-items-center" style="height: 38px;">
                            <span class="text-secondary text-sm">
                                <i class="fas fa-info-circle mr-1"></i>
                                <span id="<?php echo htmlspecialchars($resultCountId); ?>">0</span> results found
                            </span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ((!empty($buttons) && !$autoSubmit) || $resetOnClear): ?>
                <div class="col-md-<?php echo count($filters) > 0 ? '2' : '3'; ?>" style="display: flex; flex-direction: column; justify-content: flex-end;">
                    <div class="filter-actions">
                        <?php if (!$autoSubmit): ?>
                            <?php foreach ($buttons as $button): ?>
                                <button 
                                    type="<?php echo htmlspecialchars($button['type'] ?? 'submit'); ?>" 
                                    class="btn <?php echo htmlspecialchars($button['class'] ?? 'btn-secondary'); ?>" 
                                    id="<?php echo htmlspecialchars($button['id'] ?? ''); ?>"
                                >
                                    <?php if (!empty($button['icon'])): ?>
                                        <i class="<?php echo htmlspecialchars($button['icon']); ?>"></i>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($button['label'] ?? 'Action'); ?>
                                </button>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <?php if ($resetOnClear): ?>
                            <button type="reset" class="btn btn-secondary" title="Clear all filters">
                                <i class="fas fa-times"></i>
                                Clear
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php if ($autoSubmit): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('<?php echo $formId; ?>');
        if (form) {
            let filterTimeout;
            
            // Get all filter inputs (search, selects)
            const filterInputs = form.querySelectorAll('input[type="text"], select');
            
            filterInputs.forEach(input => {
                if (input.type === 'text') {
                    // Search input - trigger filtering with debounce
                    input.addEventListener('input', function() {
                        clearTimeout(filterTimeout);
                        filterTimeout = setTimeout(function() {
                            triggerClientSideFiltering();
                        }, 300); // 300ms delay
                    });
                    
                    // Allow Enter key to filter immediately
                    input.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            clearTimeout(filterTimeout);
                            triggerClientSideFiltering();
                        }
                    });
                } else if (input.type === 'select-one' || input.tagName === 'SELECT') {
                    // Dropdown filters - trigger immediately on change
                    input.addEventListener('change', function() {
                        triggerClientSideFiltering();
                    });
                }
            });
            
            // Handle form reset - Clear button
            const resetButton = form.querySelector('button[type="reset"]');
            if (resetButton) {
                resetButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Clear all form values
                    form.reset();
                    // Trigger filtering to show all rows again
                    setTimeout(function() {
                        triggerClientSideFiltering();
                    }, 50);
                });
            }
            
            // Function to perform client-side filtering
            function triggerClientSideFiltering() {
                // Get all filter values
                const searchInput = form.querySelector('input[type="text"]');
                const selectInputs = form.querySelectorAll('select');
                
                const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : '';
                const filterValues = {};
                
                selectInputs.forEach(select => {
                    const name = select.getAttribute('name');
                    const value = select.value.toLowerCase().trim();
                    if (name && value) {
                        filterValues[name] = value;
                    }
                });
                
                // Find the table and filter rows
                const tables = document.querySelectorAll('table tbody');
                tables.forEach(tbody => {
                    const rows = tbody.querySelectorAll('tr');
                    
                    rows.forEach(row => {
                        let showRow = true;
                        
                        // Check search filter
                        if (searchValue) {
                            const rowText = row.textContent.toLowerCase();
                            if (!rowText.includes(searchValue)) {
                                showRow = false;
                            }
                        }
                        
                        // Check dropdown filters
                        if (showRow) {
                            for (const [filterName, filterValue] of Object.entries(filterValues)) {
                                // Find the appropriate column to check based on filter name
                                let columnText = '';
                                
                                if (filterName === 'status') {
                                    const statusCell = row.querySelector('[data-filter="status"]') || 
                                                      row.cells[5]; // Fallback to status column position
                                    columnText = statusCell ? statusCell.textContent.toLowerCase().trim() : '';
                                } else if (filterName === 'workload') {
                                    const workloadCell = row.querySelector('[data-filter="workload"]');
                                    columnText = workloadCell ? workloadCell.textContent.toLowerCase().trim() : '';
                                } else if (filterName === 'health') {
                                    const healthCell = row.querySelector('[data-filter="health"]');
                                    columnText = healthCell ? healthCell.textContent.toLowerCase().trim() : '';
                                } else if (filterName === 'size') {
                                    const sizeCell = row.querySelector('[data-filter="size"]');
                                    columnText = sizeCell ? sizeCell.textContent.toLowerCase().trim() : '';
                                }
                                
                                if (columnText && !columnText.includes(filterValue)) {
                                    showRow = false;
                                    break;
                                }
                            }
                        }
                        
                        // Show or hide the row
                        row.style.display = showRow ? '' : 'none';
                    });
                });
            }
        }
    });
</script>
<?php endif; ?>
