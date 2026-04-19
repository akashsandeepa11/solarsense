<?php
    $total_op_managers = isset($data['total_op_managers']) ? $data['total_op_managers'] : '0';
    $active_tasks = isset($data['active_tasks']) ? $data['active_tasks'] : '0';
    $pending_tasks = isset($data['pending_tasks']) ? $data['pending_tasks'] : '0';
    $completed_tasks = isset($data['completed_tasks']) ? $data['completed_tasks'] : '0';
    
    $total_inv_managers = isset($data['total_inv_managers']) ? $data['total_inv_managers'] : '0';
    $active_inv = isset($data['active_inv']) ? $data['active_inv'] : '0';
    $low_stock_items = isset($data['low_stock_items']) ? $data['low_stock_items'] : '0';

    // Get manager type from controller
    $managerType = isset($data['managerType']) ? $data['managerType'] : 'operation_managers';
    $managers = isset($data['managers']) ? $data['managers'] : [];

    // Summary Card Data for Operation Managers
    $operation_managers_stats = [
        ['label' => 'Total Operation Managers', 'value' => $total_op_managers, 'icon' => 'fas fa-person-dots-from-line', 'color' => 'primary'],
        ['label' => 'Active Operations', 'value' => $active_tasks, 'icon' => 'fas fa-list-check', 'color' => 'success'],
        ['label' => 'Pending Tasks', 'value' => $pending_tasks, 'icon' => 'fas fa-clipboard-list', 'color' => 'warning'],
        ['label' => 'Completed This Month', 'value' => $completed_tasks, 'icon' => 'fas fa-check-circle', 'color' => 'success']
    ];

    // Summary Card Data for Inventory Managers
    $inventory_managers_stats = [
        ['label' => 'Total Inventory Managers', 'value' => $total_inv_managers, 'icon' => 'fas fa-person-dolly', 'color' => 'primary'],
        ['label' => 'Active Inventory', 'value' => $active_inv, 'icon' => 'fas fa-boxes-stacked', 'color' => 'success'],
        ['label' => 'Low Stock Items', 'value' => $low_stock_items, 'icon' => 'fas fa-exclamation-triangle', 'color' => 'warning'],
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
                'url'   => URLROOT . '/installeradmin/managers/' . $managerType . '/add',
                'icon'  => 'fas fa-plus',
                'class' => 'btn-primary btn-md'
            ];
        }
        $pdfTitle   = ($managerType === 'operation_managers') ? 'Operation Managers Report' : 'Inventory Managers Report';
        $pdfColumns = ($managerType === 'operation_managers')
            ? "['Manager Name','Specialization','District','Status','Pending Tasks']"
            : "['Manager Name','Warehouse','Status','Items','Low Stock','Efficiency']";
        $buttons[] = [
            'label'   => 'Download PDF',
            'icon'    => 'fas fa-file-pdf',
            'class'   => 'btn-outline-primary btn-md',
            'onclick' => "onclick=\"SolarSenseReport.download({tableSelector:'.data-table',title:'" . $pdfTitle . "',subtitle:'Manager list and status overview',columns:" . $pdfColumns . "},this)\""
        ];

        $config = [
            'title'       => ($managerType === 'operation_managers') ? 'Operation Managers' : 'Inventory Managers',
            'description' => ($managerType === 'operation_managers') ? 'Manage your operation managers and their tasks.' : 'Manage your inventory managers and stock levels.',
            'buttons'     => $buttons
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
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Manager Name</th>
                                <?php if ($managerType === 'operation_managers'): ?>
                                <th>Specialization</th>
                                <th>District</th>
                                <th>Status</th>
                                <th>Pending Tasks</th>
                                <?php else: ?>
                                <th>Warehouse Location</th>
                                <th>Status</th>
                                <th>Inventory Items</th>
                                <th>Low Stock Items</th>
                                <th>Efficiency Score</th>
                                <?php endif; ?>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($managers)): ?>
                                <tr><td colspan="<?php echo $managerType === 'operation_managers' ? 6 : 7; ?>" class="text-center p-6 text-secondary">No managers available</td></tr>
                            <?php else: ?>
                                <?php foreach ($managers as $row): ?>
                                    <?php
                                    $rid    = $row['id'] ?? '';
                                    $name   = $row['name'] ?? 'N/A';
                                    $email  = $row['email'] ?? 'N/A';
                                    $status = $row['status'] ?? 'inactive';
                                    ?>
                                    <tr class="data-table-row">
                                        <td>
                                            <div class="d-flex align-center gap-3">
                                                <img src="<?php echo getAvatarUrl($name); ?>" alt="<?php echo htmlspecialchars($name); ?>">
                                                <div class="manager-details">
                                                    <div class="manager-name font-semibold"><?php echo htmlspecialchars($name); ?></div>
                                                    <div class="manager-email text-secondary text-sm"><?php echo htmlspecialchars($email); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <?php if ($managerType === 'operation_managers'): ?>
                                        <td><?php echo htmlspecialchars($row['specialization'] ?? '—'); ?></td>
                                        <td><?php echo htmlspecialchars($row['district'] ?? '—'); ?></td>
                                        <td>
                                            <div class="d-flex align-center">
                                                <span class="status-dot <?php echo getStatusClass($status); ?> mr-2"></span>
                                                <?php echo htmlspecialchars($status); ?>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-primary"><?php echo htmlspecialchars($row['pending_tasks'] ?? '0'); ?></span></td>
                                        <?php else: ?>
                                        <td><?php echo htmlspecialchars($row['warehouse'] ?? '—'); ?></td>
                                        <td>
                                            <div class="d-flex align-center">
                                                <span class="status-dot <?php echo getStatusClass($status); ?> mr-2"></span>
                                                <?php echo htmlspecialchars($status); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['inventory_items'] ?? '0'); ?></td>
                                        <td>
                                            <?php $ls = $row['low_stock'] ?? '0'; ?>
                                            <span class="badge <?php echo $ls > 0 ? 'badge-warning' : 'badge-success'; ?>"><?php echo htmlspecialchars($ls); ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['efficiency'] ?? '0'); ?>%</td>
                                        <?php endif; ?>
                                        <td>
                                            <div class="actions-menu d-flex gap-2 justify-center">
                                                <a href="<?php echo URLROOT; ?>/installeradmin/managers/<?php echo $managerType; ?>/<?php echo $rid; ?>" class="btn-icon btn-sm btn-info" title="View Details"><i class="fas fa-eye"></i></a>
                                                <?php if ($data['user']['role'] === ROLE_INSTALLER_ADMIN): ?>
                                                <a href="<?php echo URLROOT; ?>/installeradmin/managers/<?php echo $managerType; ?>/edit/<?php echo $rid; ?>" class="btn-icon btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                                <button class="btn-icon btn-icon-danger" title="Remove" onclick="openDeleteModal(<?php echo (int)$rid; ?>)"><i class="fas fa-trash"></i></button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

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

    <script>
    function openDeleteModal(managerId) {
        const modal = document.getElementById('deleteManagerModal');
        const form = modal.querySelector('form');
        if (form) {
            form.action = '<?php echo URLROOT; ?>/installeradmin/managers/<?php echo $managerType; ?>/delete/' + managerId;
        }
        showConfirmationModal('deleteManagerModal');
    }
    </script>

    <?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>
    </div>
