<?php
// --- PHP Setup for Data ---
$tasks = $data['tasks'] ?? [];
$agents = $data['agents'] ?? [];
$customers = $data['customers'] ?? [];
$serviceTypes = $data['service_types'] ?? [];

// Calculate Statistics
$totalTasks = count($tasks);
$assignedTasks = count(array_filter($tasks, fn($t) => !empty($t->agent_id)));
$unassigned = $totalTasks - $assignedTasks;
$pending = count(array_filter($tasks, fn($t) => ($t->status ?? '') === 'Pending'));

$summary_cards = [
    ['label' => 'Total Tasks', 'value' => $totalTasks, 'icon' => 'fas fa-tasks', 'color' => 'primary'],
    ['label' => 'Assigned', 'value' => $assignedTasks, 'icon' => 'fas fa-user-check', 'color' => 'success'],
    ['label' => 'Unassigned', 'value' => $unassigned, 'icon' => 'fas fa-clock', 'color' => 'warning'],
    ['label' => 'Pending Requests', 'value' => $pending, 'icon' => 'fas fa-hourglass-half', 'color' => 'error'],
];

// Helper for status styling
function getMaintenanceStatusClass($status)
{
    return $status === 'Completed' ? 'bg-success' : ($status === 'Pending' ? 'bg-warning' : ($status === 'In Progress' ? 'bg-info' : 'bg-secondary'));
}
?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/components.css">
<link rel="stylesheet" href="<?php echo URLROOT ?>/css/pages/installer_admin/managers.css">

<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/pages/installer/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/pages/operation_manager/maintenance.css">

<style>
    /* Synchronized Status Dots */
    .status-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .status-dot.bg-success {
        background-color: #22c55e !important;
    }

    .status-dot.bg-warning {
        background-color: #f59e0b !important;
    }

    .status-dot.bg-secondary {
        background-color: #9ca3af !important;
    }

    /* Layout Helpers */
    .agent-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        min-width: 0;
    }

    .font-semibold {
        font-weight: 600;
    }

    /* table {
        table-layout: fixed;
        width: 100%;
    } */


    td:nth-child(1) {
        width: 5%;
    }

    td:nth-child(2) {
        width: 10%;
    }

    td:nth-child(3) {
        width: 20%;
    }

    td:nth-child(4) {
        width: 30%;
    }

    td:nth-child(5) {
        width: 15%;
    }

    td:nth-child(6) {
        width: 10%;
    }

    td:nth-child(7) {
        width: 5%;
    }
</style>

<div class="container-fluid p-8">
    <?php
    $config = [
        'title'       => 'Maintenance Management',
        'description' => 'Create, assign and track solar service requests.',
        'buttons'     => [
            [
                'label'   => 'Create New Task',
                'url'     => 'javascript:showAddModal()',
                'icon'    => 'fas fa-plus',
                'class'   => 'btn-primary btn-md',
                'onclick' => 'onclick="showAddModal()"'
            ],
            [
                'label'   => 'Sort by Date',
                'url'     => 'javascript:void(0)',
                'icon'    => 'fas fa-sort',
                'class'   => 'btn-outline-secondary btn-md date-sort-btn',
                'onclick' => 'onclick="toggleDateSort(this)"'
            ],
            [
                'label'   => 'Download PDF',
                'icon'    => 'fas fa-file-pdf',
                'class'   => 'btn-outline-primary btn-md',
                'onclick' => 'onclick="SolarSenseReport.download({tableSelector:\'.data-table\',title:\'Maintenance Tasks Report\',subtitle:\'Service task assignments and status\',columns:[\'#\',\'Service Type\',\'Customer\',\'Description\',\'Date\',\'Agent\',\'Status\']},this)"'
            ]
        ]
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <div class="managers-tabs mb-6">
        <div class="tabs-container">
            <a href="<?php echo URLROOT; ?>/operationmanager/maintenance/tasks"
                class="tab-item <?php echo ($data['active_tab'] === 'tasks') ? 'active' : ''; ?>">
                <i class="fas fa-tools"></i><span>Tasks</span>
            </a>
            <a href="<?php echo URLROOT; ?>/operationmanager/maintenance/purchases/all"
                class="tab-item <?php echo ($data['active_tab'] === 'purchases') ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i><span>Purchases</span>
            </a>
            <a href="<?php echo URLROOT; ?>/operationmanager/maintenance/reports"
                class="tab-item <?php echo ($data['active_tab'] === 'reports') ? 'active' : ''; ?>">
                <i class="fas fa-file-contract"></i><span>Service Reports</span>
            </a>
        </div>
    </div>

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
                    ['value' => 'In Progress', 'label' => 'In Progress'],
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
                'render' => function ($row) {
                    return '<span class="font-semibold">#' . $row->task_id . '</span>';
                }
            ],

            [
                'key' => 'type',
                'render' => fn($row) => htmlspecialchars($row->service_type ?? '-')
            ],


            [
                'key' => 'customer',
                'render' => function ($row) {
                    return '<div class="agent-details">
                                <div class="agent-name font-semibold">' . htmlspecialchars($row->customer_name) . '</div>
                                <div class="agent-role text-secondary text-xs">' . htmlspecialchars($row->customer_address) . '</div>
                            </div>';
                }
            ],

            [
                'key' => 'description',
                'render' => fn($row) => htmlspecialchars($row->service_description ?? '-')
            ],

            [
                'key' => 'date',
                'render' => fn($row) => date('Y-m-d', strtotime($row->request_date))
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
                    $badgeClass = $row->status === 'Completed' ? 'badge-success' : ($row->status === 'Pending' ? 'badge-warning' : ($row->status === 'In Progress' ? 'badge-info' : 'badge-secondary'));
                    return '<div class="d-flex align-center" data-filter="status">
                                <span class="' . getMaintenanceStatusClass($row->status) . ' mr-2"></span>
                                <span class="badge ' . $badgeClass . '">' . $row->status . '</span>
                            </div>';
                }
            ]
        ],
        'actions' => [
            [
                'label' => 'Assign',
                'icon' => 'fas fa-user-plus',
                'class' => 'btn-sm btn-success',
                'condition' => function($row) {
                    $status = strtolower($row->status ?? '');
                    return empty($row->agent_id) && $status !== 'completed';
                },
                'onclick' => 'onclick="openAssignModal(&quot;{task_id}&quot;, &quot;{service_type}&quot;, &quot;{customer_name}&quot;, &quot;{agent_id}&quot;)"'
            ],
            // [
            //     'label' => 'Delete',
            //     'icon' => 'fas fa-trash',
            //     'class' => 'btn-icon-danger',
            //     'onclick' => 'onclick="openDeleteModal(&quot;{task_id}&quot;, &quot;{service_type}&quot;, &quot;{customer_name}&quot;)"'
            // ]
        ],
        'empty_message' => 'No maintenance tasks found.'
    ];
    include __DIR__ . '/../../inc/components/data_table.php';
    ?>
</div>

<!-- Add Task Modal -->
<div id="addModal" class="modal" hidden>
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">Create New Task</h3>

        <form id="addForm" method="POST" action="<?= URLROOT ?>/operationmanager/maintenance/create">

            <input type="text" name="title" placeholder="Task Title" class="form-control mb-3" required>
            <input type="text" name="customer_name" placeholder="Customer Name" class="form-control mb-3" required>
            <input type="text" name="customer_address" placeholder="Address" class="form-control mb-3" required>
            <input type="text" name="panel_id" placeholder="Panel ID" class="form-control mb-3" required>

            <select name="priority" class="form-control mb-3">
                <option value="High">High</option>
                <option value="Medium" selected>Medium</option>
                <option value="Low">Low</option>
            </select>

            <div class="modal-buttons">
                <button class="btn btn-primary btn-sm">Create</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeAddModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Assign Modal -->
<div id="assignModal" class="modal" hidden>
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">Assign Task</h3>

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

<!-- Delete Modal -->
<div id="deleteModal" class="modal" hidden>
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">Delete Task</h3>

        <form id="deleteForm" method="POST">
            <p id="deleteMessage" class="text-secondary mb-6"></p>

            <div class="modal-buttons">
                <button class="btn btn-danger btn-sm">Delete</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeDeleteModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    const BASE = "<?= URLROOT ?>";

    // ---------- OPEN MODALS ----------
    function showAddModal() {
        const addModal = document.getElementById("addModal");
        addModal.hidden = false;
        addModal.classList.add("show");
    }

    function closeAddModal() {
        const addModal = document.getElementById("addModal");
        addModal.classList.remove("show");
        addModal.hidden = true;
    }

    function openAssignModal(taskId, type, customer, currentAgentId = '') {
        document.getElementById("assignTaskInfo").innerText =
            `Task: "${type}" for ${customer}`;

        document.getElementById("assignForm").action =
            `${BASE}/operationmanager/maintenance/tasks/assign/${taskId}`;

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

    function openDeleteModal(taskId, type, customer) {
        document.getElementById("deleteMessage").innerText =
            `Delete "${type}" task for ${customer}? This cannot be undone.`;

        document.getElementById("deleteForm").action =
            `${BASE}/operationmanager/maintenance/delete/${taskId}`;

        const deleteModal = document.getElementById("deleteModal");
        deleteModal.hidden = false;
        deleteModal.classList.add("show");
    }

    function closeDeleteModal() {
        const deleteModal = document.getElementById("deleteModal");
        deleteModal.classList.remove("show");
        deleteModal.hidden = true;
    }

    // Close on outside click
    window.addEventListener("click", function (e) {
        if (e.target.id === "addModal") closeAddModal();
        if (e.target.id === "assignModal") closeAssignModal();
        if (e.target.id === "deleteModal") closeDeleteModal();
    });

    
    let sortAscending = false; 
    function toggleDateSort(btn) {
        sortAscending = !sortAscending;
        
       
        const icon = btn.querySelector('i');
        if (icon) {
            icon.className = sortAscending ? 'fas fa-sort-amount-up' : 'fas fa-sort-amount-down';
        }

        const table = document.querySelector('.data-table');
        if (!table) return;
        
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr:not(. )')); 

        rows.sort((a, b) => {
            // Date is in the 5th column (index 4)
            const dateA = new Date(a.children[4].innerText.trim());
            const dateB = new Date(b.children[4].innerText.trim());

            if (sortAscending) {
                return dateA - dateB;
            } else {
                return dateB - dateA;
            }
        });

        // Re-append rows in sorted order
        rows.forEach(row => tbody.appendChild(row));
    }
</script>

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>
