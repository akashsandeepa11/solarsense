<?php
/**
 * Purchases Page — wired to real orders + order_item tables
 */
$agents = $data['agents'] ?? [];
$customers = $data['customers'] ?? [];


$orders = $data['orders'] ?? [];
$stats = $data['stats'] ?? null;
$statusFilter = $data['status_filter'] ?? 'all';

$totalOrders = $stats->total_orders ?? 0;
$pendingCount = $stats->pending_count ?? 0;
$completedCount = $stats->completed_count ?? 0;
$totalValue = $stats->total_value ?? 0;

$purchase_stats = [
    ['label' => 'Total Orders', 'value' => $totalOrders, 'icon' => 'fas fa-file-invoice', 'color' => 'primary'],
    ['label' => 'Pending', 'value' => $pendingCount, 'icon' => 'fas fa-clock', 'color' => 'warning'],
    ['label' => 'Completed', 'value' => $completedCount, 'icon' => 'fas fa-check-circle', 'color' => 'success'],
    ['label' => 'Total Value', 'value' => 'LKR ' . number_format($totalValue, 0), 'icon' => 'fas fa-coins', 'color' => 'accent'],
];

// Map DB rows to flat arrays for data_table
$orderRows = array_map(function ($o) {
    return [
        'id' => $o->order_id ?? 0,
        'order_id' => ($o->order_id ?? ''),
        'item_count' => $o->item_count ?? 0,
        'total' => 'LKR ' . number_format($o->total_amount ?? 0, 2),
        'date' => $o->date ?? '—',
        'status' => strtolower($o->status ?? 'pending'),
    ];
}, (array) $orders);
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components.css">
<link rel="stylesheet" href="<?php echo URLROOT ?>/css/pages/installer_admin/managers.css">


<div class="container-fluid p-8">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Purchase Orders',
        'description' => 'All customer orders from your store',
        'buttons' => [
            [
                'label' => 'Download PDF',
                'icon' => 'fas fa-file-pdf',
                'class' => 'btn-outline-primary btn-md',
                'onclick' => 'onclick="SolarSenseReport.download({tableSelector:\'.data-table\',title:\'Purchase Orders Report\',subtitle:\'All customer orders from the store\',columns:[\'Order #\',\'Items\',\'Total\',\'Date\',\'Status\']},this)"'
            ]
        ]
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <?php if ($data['user']['role'] === ROLE_OPERATION_MANAGER): ?>
        <div class="managers-tabs mb-6">
            <div class="tabs-container">
                <a href="<?php echo URLROOT; ?>/operationmanager/maintenance/tasks"
                    class="tab-item <?php echo ($data['active_tab'] === 'tasks') ? 'active' : ''; ?>">
                    <i class="fas fa-tools"></i>
                    <span>Tasks</span>
                </a>

                <a href="<?php echo URLROOT; ?>/operationmanager/maintenance/purchases/all"
                    class="tab-item <?php echo ($data['active_tab'] === 'purchases') ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Purchases</span>
                </a>
                <a href="<?php echo URLROOT; ?>/operationmanager/maintenance/reports"
                    class="tab-item <?php echo ($data['active_tab'] === 'reports') ? 'active' : ''; ?>">
                    <i class="fas fa-file-contract"></i><span>Service Reports</span>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stats -->
    <?php
    $config = ['stats' => $purchase_stats, 'columns' => 4];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <!-- Filter Bar -->
    <div class="card shadow-sm rounded-xl mb-4 mt-6">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-4 align-center">
                <input type="text" id="searchInput" placeholder="Search by order #..." class="form-control"
                    style="max-width:220px;">

                <select id="statusFilter" class="form-control" style="max-width:180px;">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="in-progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="container-fluid p-8">
        <?php
        $config = [
            'headers' => [
                ['key' => 'order_id', 'label' => 'Order #'],
                ['key' => 'customer_name', 'label' => 'Customer'],
                ['key' => 'item_count', 'label' => 'Items'],
                ['key' => 'total', 'label' => 'Total'],
                ['key' => 'date', 'label' => 'Date'],
                ['key' => 'agent', 'label' => 'Assigned Agent'],
                ['key' => 'status', 'label' => 'Status'],
            ],
            'rows' => $orders,
            'columns' => [
                [
                    'key' => 'order_id',
                    'render' => function ($row) {
                        return '<span class="font-semibold">#' . htmlspecialchars($row->order_id) . '</span>';
                    }
                ],
                [
                    'key' => 'customer_name',
                    'render' => function ($row) {
                        return '<span class="customer-name">' . htmlspecialchars($row->customer_name) . '</span>';
                    }
                ],
                [
                    'key' => 'item_count',
                    'render' => function ($row) {
                        $count = $row->item_count ?? 0;
                        return $count . ' item' . ($count != 1 ? 's' : '');
                    }
                ],
                [
                    'key' => 'total',
                    'render' => function ($row) {
                        return '<span class="font-semibold">LKR ' . number_format($row->total_amount ?? 0, 2) . '</span>';
                    }
                ],
                [
                    'key' => 'agent',
                    'render' => function ($row) {
                        if (!empty($row->agent_id)) {
                            return '<div data-filter="assigned" data-filter-value="yes">
                                    <span class="text-success font-semibold">'
                                . htmlspecialchars($row->agent_name) .
                                '</span><br>
                                    <small class="text-success">Assigned</small>
                                </div>';
                        } else {
                            return '<span class="text-warning" data-filter="assigned" data-filter-value="no">Unassigned</span>';
                        }
                    }
                ],
                [
                    'key' => 'status',
                    'render' => function ($row) {
                        $status = strtolower($row->status ?? 'pending');
                        $map = [
                            'pending' => ['bg-warning', 'Pending'],
                            'in-progress' => ['bg-info', 'In Progress'], // Handle in-progress status
                            'completed' => ['bg-success', 'Completed']
                        ];
                        [$cls, $lbl] = $map[$status] ?? ['bg-secondary', ucfirst($status)];

                        // ADDED: data-status attribute for JS filtering
                        return '<span class="badge ' . $cls . ' text-surface px-3 py-1 rounded-full text-xs" data-status="' . $status . '">'
                            . $lbl .
                            '</span>';
                    }
                ],
            ],
            'empty_message' => 'No orders found.',
        ];

        // ONLY ALLOW OPERATION MANAGER TO SEE ACTIONS
        if ($data['user']['role'] === ROLE_OPERATION_MANAGER) {
            $config['actions'] = [
                [
                    'label' => 'Assign',
                    'icon' => 'fas fa-user-plus',
                    'class' => 'btn-sm btn-success',
                    'onclick' => 'onclick="openAssignModal(\'{order_id}\', \'{customer_name}\', \'{agent_id}\')"'
                ],
                // [
                //     'label' => 'Delete',
                //     'icon' => 'fas fa-trash',
                //     'class' => 'btn-icon-danger',
                //     'onclick' => 'onclick="openDeleteModal(\'{task_id}\', \'{service_type}\', \'{customer_name}\')"'
                // ]
            ];
        }

        include __DIR__ . '/../../inc/components/data_table.php';
        ?>
    </div>

    <!-- Assign Modal -->
    <div id="assignModal" class="modal" hidden>
        <div class="modal-content">
            <h3 class="text-2xl font-semibold mb-4">Assign Purchase</h3>

            <form id="assignForm" method="POST">

                <p id="assignTaskInfo" class="mb-4 text-secondary"></p>

                <select name="agent_id" id="assignAgentSelect" class="form-control mb-3" required>
                    <option value="">Select Agent</option>
                    <?php foreach ($agents as $agent): ?>
                        <option value="<?= $agent->user_id ?>">
                            <?= htmlspecialchars($agent->full_name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="modal-buttons">
                    <button class="btn btn-success btn-sm">Assign</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="closeAssignModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const BASE = "<?= URLROOT ?>";

        const filterTable = () => {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            const statusQuery = document.getElementById('statusFilter').value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                // Check Order #
                const orderIdText = row.querySelector('td:first-child')?.textContent?.toLowerCase() ?? '';

                // Check Status
                const statusBadge = row.querySelector('[data-status]');
                const rowStatus = statusBadge ? statusBadge.getAttribute('data-status') : '';

                const matchesSearch = orderIdText.includes(searchQuery);
                const matchesStatus = (statusQuery === 'all' || rowStatus === statusQuery);

                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        };

        // Attach listeners
        document.getElementById('searchInput').addEventListener('input', filterTable);
        document.getElementById('statusFilter').addEventListener('change', filterTable);

        // Existing modal logic remains same
        function openAssignModal(orderId, customer, currentAgentId = '') {
            document.getElementById("assignTaskInfo").innerText = `Purchase ID: "${orderId}" for ${customer}`;
            document.getElementById("assignForm").action = `${BASE}/operationmanager/maintenance/purchases/assign/${orderId}`;
            document.getElementById("assignAgentSelect").value = currentAgentId || "";
            const modal = document.getElementById("assignModal");
            modal.hidden = false;
            modal.classList.add("show");
        }

        function closeAssignModal() {
            const modal = document.getElementById("assignModal");
            modal.classList.remove("show");
            modal.hidden = true;
        }
    </script>

    <?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>