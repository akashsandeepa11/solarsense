<?php
// --- PHP Setup for Data ---
$tasks        = $data['tasks']         ?? [];
$agents       = $data['agents']        ?? [];
$customers    = $data['customers']     ?? [];
$serviceTypes = $data['service_types'] ?? [];

// Calculate Statistics
$totalTasks    = count($tasks);
$assignedTasks = count(array_filter($tasks, fn($t) => !empty($t->agent_id)));
$unassigned    = $totalTasks - $assignedTasks;
$pending       = count(array_filter($tasks, fn($t) => ($t->status ?? '') === 'Pending'));

$summary_cards = [
    ['label' => 'Total Tasks', 'value' => $totalTasks, 'icon' => 'fas fa-tasks', 'color' => 'primary'],
    ['label' => 'Assigned', 'value' => $assignedTasks, 'icon' => 'fas fa-user-check', 'color' => 'success'],
    ['label' => 'Unassigned', 'value' => $unassigned, 'icon' => 'fas fa-clock', 'color' => 'warning'],
    ['label' => 'Pending Requests', 'value' => $pending, 'icon' => 'fas fa-hourglass-half', 'color' => 'error'],
];

// Helper for status styling
function getMaintenanceStatusClass($status) {
    return $status === 'Completed' ? 'bg-success' : ($status === 'Pending' ? 'bg-warning' : 'bg-secondary');
}
?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/components.css">
<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/pages/installer/dashboard.css">

<style>
    /* Synchronized Status Dots */
    .status-dot { display: inline-block; width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; }
    .status-dot.bg-success { background-color: #22c55e !important; }
    .status-dot.bg-warning { background-color: #f59e0b !important; }
    .status-dot.bg-secondary { background-color: #9ca3af !important; }

    /* Layout Helpers */
    .agent-details { display: flex; flex-direction: column; gap: 0.25rem; min-width: 0; }
    .font-semibold { font-weight: 600; }
</style>

<div class="container-fluid p-8">
    <?php
    $config = [
        'title' => 'Maintenance Management',
        'description' => 'Create, assign and track solar service requests.',
        'buttons' => [
            [
                'label' => 'Create New Task',
                'url' => '#',
                'icon' => 'fas fa-plus',
                'class' => 'btn-primary btn-md',
                'onclick' => 'onclick="showAddModal()"'
            ]
        ]
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <?php
    $config = [
        'search' => [
            'id' => 'searchInput',
            'name' => 'search',
            'label' => 'Search Tasks',
            'placeholder' => 'Search by customer or type...'
        ],
        'filters' => [
            [
                'id' => 'statusFilter',
                'name' => 'status',
                'label' => 'Status',
                'options' => [
                    ['value' => '', 'label' => 'All Status'],
                    ['value' => 'Pending', 'label' => 'Pending'],
                    ['value' => 'Completed', 'label' => 'Completed']
                ]
            ],
            [
                'id' => 'assignFilter',
                'name' => 'assigned',
                'label' => 'Assignment',
                'options' => [
                    ['value' => '', 'label' => 'All Assignment'],
                    ['value' => 'yes', 'label' => 'Assigned'],
                    ['value' => 'no', 'label' => 'Unassigned']
                ]
            ]
        ],
        'form_action' => URLROOT . '/operationmanager/maintenance',
        'form_method' => 'GET',
        'auto_submit' => true,
        'reset_on_clear' => true
    ];
    include __DIR__ . '/../../inc/components/filter_bar.php';
    ?>

    <?php
    $config = ['stats' => $summary_cards, 'columns' => 6];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <?php
    $config = [
        'headers' => [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'type', 'label' => 'Service Type'],
            ['key' => 'customer', 'label' => 'Customer'],
            ['key' => 'description', 'label' => 'Description'],
            ['key' => 'date', 'label' => 'Date'],
            ['key' => 'agent', 'label' => 'Assigned Agent'],
            ['key' => 'status', 'label' => 'Status']
        ],
        'rows' => $tasks,
        'columns' => [
            [
                'key' => 'id',
                'render' => function($row) { return '<span class="font-semibold">#' . $row->task_id . '</span>'; }
            ],
            [
                'key' => 'customer',
                'render' => function($row) {
                    return '<div class="agent-details">
                                <div class="agent-name font-semibold">' . htmlspecialchars($row->customer_name) . '</div>
                                <div class="agent-role text-secondary text-xs">' . htmlspecialchars($row->customer_address) . '</div>
                            </div>';
                }
            ],
            [
                'key' => 'agent',
                'render' => function($row) {
                    if ($row->agent_name) {
                        return '<span class="text-success"><i class="fas fa-user-check mr-1"></i>' . htmlspecialchars($row->agent_name) . '</span>';
                    }
                    return '<span class="text-secondary"><i class="fas fa-user-slash mr-1"></i>Unassigned</span>';
                }
            ],
            [
                'key' => 'status',
                'render' => function($row) {
                    return '<div class="d-flex align-center">
                                <span class="status-dot ' . getMaintenanceStatusClass($row->status) . ' mr-2"></span>
                                <span class="badge ' . ($row->status === 'Completed' ? 'badge-success' : 'badge-warning') . '">' . $row->status . '</span>
                            </div>';
                }
            ]
        ],
        'actions' => [
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
        ],
        'empty_message' => 'No maintenance tasks found.'
    ];
    include __DIR__ . '/../../inc/components/data_table.php';
    ?>
</div>

<script>
    const BASE = '<?php echo URLROOT; ?>';
    const $ = id => document.getElementById(id);

    function showAddModal()    { $('addModal').classList.add('show'); }
    function closeAddModal()   { $('addModal').classList.remove('show'); }
    function closeAssignModal(){ $('assignModal').classList.remove('show'); }
    function closeDeleteModal(){ $('deleteModal').classList.remove('show'); }

    function openAssignModal(taskId, currentAgentId, type, customer) {
        $('assignTaskInfo').innerText = `Task: "${type}" for ${customer}`;
        $('assignForm').action = `${BASE}/operationmanager/maintenance/assign/${taskId}`;
        $('assignAgentSelect').value = currentAgentId === 'null' ? '' : currentAgentId;
        $('assignModal').classList.add('show');
    }

    function openDeleteModal(taskId, type, customer) {
        $('deleteMessage').innerText = `Delete "${type}" task for ${customer}? This cannot be undone.`;
        $('deleteForm').action = `${BASE}/operationmanager/maintenance/delete/${taskId}`;
        $('deleteModal').classList.add('show');
    }
</script>