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
    <div class="container-fluid p-8">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th>Assigned Agent</th>
                                <th>Status</th>
                                <?php if ($data['user']['role'] === ROLE_OPERATION_MANAGER): ?><th class="text-center">Actions</th><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr><td colspan="<?php echo $data['user']['role'] === ROLE_OPERATION_MANAGER ? 8 : 7; ?>" class="text-center p-6 text-secondary">No orders found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($orders as $row): ?>
                                    <?php
                                    $status = strtolower($row->status ?? 'pending');
                                    $map = ['pending'=>['bg-warning','Pending'],'completed'=>['bg-success','Completed'],'cancelled'=>['bg-error','Cancelled']];
                                    [$cls, $lbl] = $map[$status] ?? ['bg-secondary', ucfirst($status)];
                                    ?>
                                    <tr class="data-table-row">
                                        <td><span class="font-semibold">#<?php echo htmlspecialchars($row->order_id); ?></span></td>
                                        <td><span class="customer-name"><?php echo htmlspecialchars($row->customer_name); ?></span></td>
                                        <td><?php $c = $row->item_count ?? 0; echo $c . ' item' . ($c != 1 ? 's' : ''); ?></td>
                                        <td><span class="font-semibold">LKR <?php echo number_format($row->total_amount ?? 0, 2); ?></span></td>
                                        <td><?php echo htmlspecialchars($row->date ?? '—'); ?></td>
                                        <td>
                                            <?php if (!empty($row->agent_id)): ?>
                                                <div data-filter="assigned" data-filter-value="yes">
                                                    <span class="text-success font-semibold"><?php echo htmlspecialchars($row->agent_name); ?></span><br>
                                                    <small class="text-success">Assigned</small>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-warning" data-filter="assigned" data-filter-value="no">Unassigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="badge <?php echo $cls; ?> text-surface px-3 py-1 rounded-full text-xs"><?php echo $lbl; ?></span></td>
                                        <?php if ($data['user']['role'] === ROLE_OPERATION_MANAGER): ?>
                                        <td>
                                            <div class="actions-menu d-flex gap-2 justify-center">
                                                <button class="btn-icon btn-sm btn-success" title="Assign"
                                                        onclick="openAssignModal('<?php echo $row->order_id; ?>', '<?php echo htmlspecialchars($row->customer_name, ENT_QUOTES); ?>', '<?php echo $row->agent_id; ?>')">
                                                    <i class="fas fa-user-plus"></i>
                                                </button>
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
        // Client-side search by order #
        document.getElementById('searchInput').addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                const text = row.querySelector('td')?.textContent?.toLowerCase() ?? '';
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });

        function closeDeleteModal() { $('deleteModal').classList.remove('show'); }

        function openAssignModal(orderId, customer, currentAgentId = '') {
            document.getElementById("assignTaskInfo").innerText =
                `Purchase ID: "${orderId}" for ${customer}`;

            document.getElementById("assignForm").action =
                `${BASE}/operationmanager/maintenance/purchases/assign/${orderId}`;

            document.getElementById("assignAgentSelect").value =
                currentAgentId || "";

            const assignModal = document.getElementById("assignModal");
            assignModal.hidden = false;
            assignModal.classList.add("show");
        }

        function closeAssignModal() {
            const assignModal = document.getElementById("assignModal");
            assignModal.classList.remove("show");
            assignModal.hidden = true;
        }

    </script>

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>
