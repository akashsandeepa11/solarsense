<?php
    // --- PHP Setup for Dummy Data ---

    // Get manager type from controller
    $managerType = isset($data['managerType']) ? $data['managerType'] : 'operation_managers';
    $managers = isset($data['managers']) ? $data['managers'] : [];

    // Summary Card Data for Operation Managers
    $operation_managers_stats = [
        ['label' => 'Total Operation Managers', 'value' => '12', 'icon' => 'fas fa-person-dots-from-line', 'color' => 'primary'],
        ['label' => 'Active Operations', 'value' => '8', 'icon' => 'fas fa-list-check', 'color' => 'success'],
        ['label' => 'Pending Tasks', 'value' => '5', 'icon' => 'fas fa-clipboard-list', 'color' => 'warning'],
        ['label' => 'Completed This Month', 'value' => '34', 'icon' => 'fas fa-check-circle', 'color' => 'success']
    ];

    // Summary Card Data for Inventory Managers
    $inventory_managers_stats = [
        ['label' => 'Total Inventory Managers', 'value' => '8', 'icon' => 'fas fa-person-dolly', 'color' => 'primary'],
        ['label' => 'Active Inventory', 'value' => '245', 'icon' => 'fas fa-boxes-stacked', 'color' => 'success'],
        ['label' => 'Low Stock Items', 'value' => '3', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'warning'],
        ['label' => 'Stock Requests', 'value' => '12', 'icon' => 'fas fa-truck-fast', 'color' => 'info']
    ];

    // Determine which stats to show
    $summary_cards = ($managerType === 'operation_managers') ? $operation_managers_stats : $inventory_managers_stats;

    // Function to determine the status dot color
    function getStatusClass($status) {
        switch (strtolower($status)) {
            case 'active':
                return 'bg-success';
            case 'inactive':
                return 'bg-secondary';
            case 'on leave':
                return 'bg-warning';
            case 'away':
                return 'bg-info';
            default:
                return 'bg-secondary';
        }
    }
    ?>

    <link rel="stylesheet" href="<?php echo URLROOT?>/css/pages/installer_admin/managers.css">
        <link rel="stylesheet" href="<?php echo URLROOT?>/css/components.css">



    <div class="container-fluid p-8">

        <!-- Page Header -->
        <?php
        $buttons = [];
        if($data['user']['role'] === ROLE_INSTALLER_ADMIN) {
            $buttons[] = [
                'label' => ($managerType === 'operation_managers') ? 'Add Operation Manager' : 'Add Inventory Manager',
                'url' => URLROOT . '/installeradmin/managers/' . $managerType . '/add',
                'icon' => 'fas fa-plus',
                'class' => 'btn-primary btn-md'
            ];
        }
        
        $config = [
            'title' => ($managerType === 'operation_managers') ? 'Operation Managers' : 'Inventory Managers',
            'description' => ($managerType === 'operation_managers') ? 'Manage your operation managers and their tasks.' : 'Manage your inventory managers and stock levels.',
            'buttons' => $buttons
        ];
        include __DIR__ . '/../../inc/components/page_header.php';
        ?>

        <!-- Manager Type Tabs -->
        <div class="managers-tabs mb-6">
            <div class="tabs-container">
                <a href="<?php echo URLROOT; ?>/installeradmin/managers/operation_managers" 
                   class="tab-item <?php echo ($managerType === 'operation_managers') ? 'active' : ''; ?>">
                    <i class="fas fa-person-dots-from-line"></i>
                    <span>Operation Managers</span>
                </a>
                <a href="<?php echo URLROOT; ?>/installeradmin/managers/inventory_managers" 
                   class="tab-item <?php echo ($managerType === 'inventory_managers') ? 'active' : ''; ?>">
                    <i class="fas fa-person-dolly"></i>
                    <span>Inventory Managers</span>
                </a>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <?php
        $search_placeholder = ($managerType === 'operation_managers') ? 
            'Search by name or district...' : 
            'Search by name or location...';

        $config = [
            'search' => [
                'id' => 'searchManagers',
                'name' => 'search',
                'label' => 'Search Managers',
                'placeholder' => $search_placeholder
            ],
            'filters' => [
                [
                    'id' => 'filterStatus',
                    'name' => 'status',
                    'label' => 'Status',
                    'options' => [
                        ['value' => '', 'label' => 'All Status'],
                        ['value' => 'active', 'label' => 'Active'],
                        ['value' => 'inactive', 'label' => 'Inactive'],
                        ['value' => 'on leave', 'label' => 'On Leave'],
                        ['value' => 'away', 'label' => 'Away']
                    ]
                ],
                ($managerType === 'operation_managers') ? [
                    'id' => 'filterSpecialization',
                    'name' => 'specialization',
                    'label' => 'Specialization',
                    'options' => [
                        ['value' => '', 'label' => 'All Specializations'],
                        ['value' => 'installation', 'label' => 'Installation'],
                        ['value' => 'maintenance', 'label' => 'Maintenance'],
                        ['value' => 'repair', 'label' => 'Repair']
                    ]
                ] : [
                    'id' => 'filterInventoryLevel',
                    'name' => 'inventory_level',
                    'label' => 'Inventory Level',
                    'options' => [
                        ['value' => '', 'label' => 'All Levels'],
                        ['value' => 'high', 'label' => 'High (>100 items)'],
                        ['value' => 'medium', 'label' => 'Medium (50-100 items)'],
                        ['value' => 'low', 'label' => 'Low (<50 items)']
                    ]
                ]
            ],
            'buttons' => [],
            'form_action' => URLROOT . '/installeradmin/managers/' . $managerType,
            'form_method' => 'GET',
            'auto_submit' => true,
            'reset_on_clear' => true
        ];
        include __DIR__ . '/../../inc/components/filter_bar.php';
        ?>

        <!-- Summary Cards -->
        <?php
        $config = [
            'stats' => $summary_cards,
            'columns' => 4
        ];
        include __DIR__ . '/../../inc/components/stat_card.php';
        ?>

        <!-- Managers Table -->
        <?php
        // Table headers differ based on manager type
        $headers = ($managerType === 'operation_managers') ? [
            ['key' => 'name', 'label' => 'Manager Name'],
            ['key' => 'specialization', 'label' => 'Specialization'],
            ['key' => 'district', 'label' => 'District'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'pending_tasks', 'label' => 'Pending Tasks'],
            ['key' => 'performance', 'label' => 'Performance Score']
        ] : [
            ['key' => 'name', 'label' => 'Manager Name'],
            ['key' => 'warehouse', 'label' => 'Warehouse Location'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'inventory_items', 'label' => 'Inventory Items'],
            ['key' => 'low_stock', 'label' => 'Low Stock Items'],
            ['key' => 'efficiency', 'label' => 'Efficiency Score']
        ];

        $config = [
            'headers' => $headers,
            'rows' => $managers,
            'columns' => [
                [
                    'key' => 'name',
                    'render' => function($row) {
                        return '<div class="d-flex align-center gap-3">
                                    <img src="' . getAvatarUrl($row['name'] ?? 'Manager') . '" alt="' . htmlspecialchars($row['name'] ?? 'Manager') . '">
                                    <div class="manager-details">
                                        <div class="manager-name font-semibold">' . htmlspecialchars($row['name'] ?? 'N/A') . '</div>
                                        <div class="manager-email text-secondary text-sm">' . htmlspecialchars($row['email'] ?? 'N/A') . '</div>
                                    </div>
                                </div>';
                    }
                ],
                [
                    'key' => 'status',
                    'render' => function($row) {
                        return '<div class="d-flex align-center">
                                    <span class="status-dot ' . getStatusClass($row['status'] ?? 'inactive') . ' mr-2"></span>
                                    ' . htmlspecialchars($row['status'] ?? 'N/A') . '
                                </div>';
                    }
                ],
                [
                    'key' => 'performance',
                    'render' => function($row) {
                        $performance = isset($row['performance']) ? $row['performance'] : '0';
                        return htmlspecialchars($performance) . '%';
                    }
                ],
                [
                    'key' => 'pending_tasks',
                    'render' => function($row) {
                        return '<span class="badge badge-primary">' . htmlspecialchars($row['pending_tasks'] ?? '0') . '</span>';
                    }
                ],
                [
                    'key' => 'efficiency',
                    'render' => function($row) {
                        $efficiency = isset($row['efficiency']) ? $row['efficiency'] : '0';
                        return htmlspecialchars($efficiency) . '%';
                    }
                ],
                [
                    'key' => 'low_stock',
                    'render' => function($row) {
                        $low_stock = isset($row['low_stock']) ? $row['low_stock'] : '0';
                        $class = $low_stock > 0 ? 'badge-warning' : 'badge-success';
                        return '<span class="badge ' . $class . '">' . htmlspecialchars($low_stock) . '</span>';
                    }
                ]
            ],
            'actions' => [],
            'empty_message' => 'No managers available'
        ];
        
        // Build actions array - View Details always available
        $config['actions'] = [
            [
                'label' => 'View Details',
                'icon' => 'fas fa-eye',
                'url' => URLROOT . '/installeradmin/managers/' . $managerType . '/{id}',
                'class' => 'btn-sm btn-info'
            ]
        ];
        
        // Add Edit and Remove actions only for Installer Admin
        if($data['user']['role'] === ROLE_INSTALLER_ADMIN) {
            $config['actions'][] = [
                'label' => 'Edit',
                'icon' => 'fas fa-edit',
                'url' => URLROOT . '/installeradmin/managers/' . $managerType . '/edit/{id}',
                'class' => 'btn-sm btn-primary'
            ];
            $config['actions'][] = [
                'label' => 'Remove',
                'icon' => 'fas fa-trash',
                'class' => 'btn-icon-danger',
                'onclick' => 'onclick="openDeleteModal(' . '{id}' . ')"'
            ];
        }
        
        include __DIR__ . '/../../inc/components/data_table.php';
        ?>

    <!-- Delete Confirmation Modal -->
    <?php
    $config = [
        'modal_id' => 'deleteManagerModal',
        'title' => 'Confirm Delete',
        'icon' => 'fas fa-exclamation-triangle',
        'icon_color' => 'text-warning',
        'heading' => 'Delete Manager?',
        'message' => 'Are you sure you want to delete this manager? This action cannot be undone. All associated records will be archived.',
        'confirm_text' => 'Delete Manager',
        'confirm_icon' => 'fas fa-check',
        'cancel_text' => 'Cancel',
        'cancel_icon' => 'fas fa-times',
        'confirm_action' => URLROOT . '/installeradmin/managers/' . $managerType . '/delete/',
        'confirm_method' => 'POST',
        'confirm_class' => 'btn-danger'
    ];
    include __DIR__ . '/../../inc/models/confirmation_modal.php';
    ?>

    <!-- Dynamic Delete Modal Handler -->
    <script>
    function openDeleteModal(managerId) {
        // Get the form inside the modal and update its action
        const modal = document.getElementById('deleteManagerModal');
        const form = modal.querySelector('form');
        if (form) {
            form.action = '<?php echo URLROOT; ?>/installeradmin/managers/<?php echo $managerType; ?>/delete/' + managerId;
        }
        // Show the modal
        showConfirmationModal('deleteManagerModal');
    }
    </script>
    </div>
