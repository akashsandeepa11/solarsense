<?php
/**
 * Purchases Page — wired to real orders + order_item tables
 */

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
        'order_id' => '#' . ($o->order_id ?? ''),
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
        'title'       => 'Purchase Orders',
        'description' => 'All customer orders from your store',
        'buttons'     => [
            [
                'label'   => 'Download PDF',
                'icon'    => 'fas fa-file-pdf',
                'class'   => 'btn-outline-primary btn-md',
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
                    <span>Maintenance Tasks</span>
                </a>

                <a href="<?php echo URLROOT; ?>/operationmanager/maintenance/purchases/all"
                    class="tab-item <?php echo ($data['active_tab'] === 'purchases') ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Purchase Orders</span>
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
            <form method="GET" action="<?php echo URLROOT; ?>/inventorymanager/purchases"
                class="d-flex flex-wrap gap-4 align-center">
                <input type="text" id="searchInput" placeholder="Search by order #..." class="form-control"
                    style="max-width:220px;">
                <select name="statusFilter" id="statusFilter" class="form-control" style="max-width:180px;"
                    onchange="this.form.submit()">
                    <option value="all" <?php echo $statusFilter === 'all' ? 'selected' : ''; ?>>All Status</option>
                    <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="completed" <?php echo $statusFilter === 'completed' ? 'selected' : ''; ?>>Completed
                    </option>
                    <option value="cancelled" <?php echo $statusFilter === 'cancelled' ? 'selected' : ''; ?>>Cancelled
                    </option>
                </select>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <?php
    $config = [
        'headers' => [
            ['key' => 'order_id', 'label' => 'Order #'],
            ['key' => 'item_count', 'label' => 'Items'],
            ['key' => 'total', 'label' => 'Total'],
            ['key' => 'date', 'label' => 'Date'],
            ['key' => 'status', 'label' => 'Status'],
        ],
        'rows' => $orderRows,
        'columns' => [
            [
                'key' => 'order_id',
                'render' => function ($row) {
                    return '<span class="font-medium text-primary">' . htmlspecialchars($row['order_id']) . '</span>';
                }
            ],
            [
                'key' => 'item_count',
                'render' => function ($row) {
                    return $row['item_count'] . ' item' . ($row['item_count'] != 1 ? 's' : '');
                }
            ],
            [
                'key' => 'total',
                'render' => function ($row) {
                    return '<span class="font-semibold">' . htmlspecialchars($row['total']) . '</span>';
                }
            ],
            [
                'key' => 'status',
                'render' => function ($row) {
                    $map = [
                        'pending' => ['bg-warning', 'Pending'],
                        'completed' => ['bg-success', 'Completed'],
                        'cancelled' => ['bg-error', 'Cancelled'],
                    ];
                    [$cls, $lbl] = $map[$row['status']] ?? ['bg-secondary', ucfirst($row['status'])];
                    return '<span class="badge ' . $cls . ' text-surface px-3 py-1 rounded-full text-xs">' . $lbl . '</span>';
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
                'onclick' => 'onclick="openAssignModal(\'{task_id}\', \'{agent_id}\', \'{service_type}\', \'{customer_name}\')"'
            ],
            [
                'label' => 'Delete',
                'icon' => 'fas fa-trash',
                'class' => 'btn-icon-danger',
                'onclick' => 'onclick="openDeleteModal(\'{task_id}\', \'{service_type}\', \'{customer_name}\')"'
            ]
        ];
    }

    include __DIR__ . '/../../inc/components/data_table.php';
    ?>
</div>

<script>
    // Client-side search by order #
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            const text = row.querySelector('td')?.textContent?.toLowerCase() ?? '';
            row.style.display = text.includes(q) ? '' : 'none';
        });
    });

    function closeAssignModal() { $('assignModal').classList.remove('show'); }
    function closeDeleteModal() { $('deleteModal').classList.remove('show'); }

    function openAssignModal(taskId, currentAgentId, type, customer) {
        $('assignTaskInfo').innerText = `Task: "${type}" for ${customer}`;
        $('assignForm').action = `${BASE}/operationmanager/purchases/assign/${taskId}`;
        $('assignAgentSelect').value = currentAgentId === 'null' ? '' : currentAgentId;
        $('assignModal').classList.add('show');
    }

    function openDeleteModal(taskId, type, customer) {
        $('deleteMessage').innerText = `Delete "${type}" task for ${customer}? This cannot be undone.`;
        $('deleteForm').action = `${BASE}/operationmanager/purchases/delete/${taskId}`;
        $('deleteModal').classList.add('show');
    }

</script>

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>