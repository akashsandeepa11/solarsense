<?php
// Real data from controller
$tasks        = $data['tasks']         ?? [];
$agents       = $data['agents']        ?? [];
$customers    = $data['customers']     ?? [];
$serviceTypes = $data['service_types'] ?? [];

// Stats — no priority since service_req has no priority column
$totalTasks    = count($tasks);
$assignedTasks = count(array_filter($tasks, fn($t) => !empty($t->agent_id)));
$unassigned    = $totalTasks - $assignedTasks;
$pending       = count(array_filter($tasks, fn($t) => ($t->status ?? '') === 'Pending'));

// Build JSON for JS table rendering
$tasksJson = json_encode(array_map(fn($t) => [
    'id'          => $t->task_id,
    'type'        => $t->service_type        ?? 'Service Request',
    'description' => $t->service_description ?? '—',
    'customer'    => $t->customer_name       ?? '—',
    'address'     => $t->customer_address    ?? '—',
    'date'        => $t->request_date        ?? '',
    'status'      => $t->status              ?? 'Pending',
    'agent_id'    => $t->agent_id            ?? null,
    'agent'       => $t->agent_name          ?? '',
    'assigned'    => !empty($t->agent_id),
], $tasks));

$agentsJson = json_encode(array_map(fn($a) => [
    'id'   => $a->id,
    'name' => $a->full_name,
], $agents));
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/operation_manager/maintenance.css">

<div class="content-area">
    <!-- Page Header -->
    <?php
    $config = [
        'title'       => 'Maintenance Task Management',
        'description' => 'Create, assign and track solar service requests',
    ];
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <?php
    // Use shared stat_card component for consistent styling with fleet/dashboard
    ?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/pages/installer/dashboard.css">

    <?php
    $summary_cards = [
        ['label' => 'Total Tasks', 'value' => $totalTasks, 'icon' => 'fas fa-tasks', 'color' => 'primary', 'id' => 'statTotal'],
        ['label' => 'Assigned', 'value' => $assignedTasks, 'icon' => 'fas fa-user-check', 'color' => 'success', 'id' => 'statAssigned'],
        ['label' => 'Unassigned', 'value' => $unassigned, 'icon' => 'fas fa-clock', 'color' => 'warning', 'id' => 'statUnassigned'],
        ['label' => 'Pending', 'value' => $pending, 'icon' => 'fas fa-hourglass-half', 'color' => 'error', 'id' => 'statPending'],
    ];
    $config = ['stats' => $summary_cards, 'columns' => 6];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <!-- Filter Bar -->
    <div class="card shadow-lg rounded-xl mb-4">
        <div class="card-body">
            <div class="toolbar">
                <input type="text" id="searchInput" placeholder="Search by type or customer..." class="form-control">
                <select id="statusFilter" class="form-control">
                    <option value="all">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                </select>
                <select id="assignFilter" class="form-control">
                    <option value="all">All Assignment</option>
                    <option value="assigned">Assigned</option>
                    <option value="unassigned">Unassigned</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="card shadow-lg rounded-xl">
        <div class="card-body">
            <div class="table-header mb-4">
                <h3 class="text-2xl font-semibold">Service Requests</h3>
                <button class="btn btn-primary rounded-lg" onclick="showAddModal()">
                    <i class="fas fa-plus mr-2"></i>Create New Task
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-sm font-semibold text-secondary">#</th>
                            <th class="text-sm font-semibold text-secondary">Service Type</th>
                            <th class="text-sm font-semibold text-secondary">Customer</th>
                            <th class="text-sm font-semibold text-secondary">Description</th>
                            <th class="text-sm font-semibold text-secondary">Date</th>
                            <th class="text-sm font-semibold text-secondary">Agent</th>
                            <th class="text-sm font-semibold text-secondary">Status</th>
                            <th class="text-sm font-semibold text-secondary">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ===== Create Task Modal ===== -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">
            <i class="fas fa-plus-circle mr-2 text-primary"></i>Create New Task
        </h3>
        <form method="POST" action="<?php echo URLROOT; ?>/operationmanager/maintenance/create">

            <!-- Customer -->
            <div class="mb-4">
                <label class="form-label">Customer <span class="text-danger">*</span></label>
                <select name="homeowner_id" class="form-control" required>
                    <option value="">— Select Customer —</option>
                    <?php foreach ($customers as $c): ?>
                    <option value="<?php echo htmlspecialchars($c['id']); ?>">
                        <?php echo htmlspecialchars($c['name']); ?>
                        <?php if (!empty($c['location'])): ?> — <?php echo htmlspecialchars($c['location']); ?><?php endif; ?>
                    </option>
                    <?php endforeach; ?>
                    <?php if (empty($customers)): ?>
                    <option disabled>No customers found</option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Service Type -->
            <div class="mb-4">
                <label class="form-label">Service Type <span class="text-danger">*</span></label>
                <select name="service_type_id" class="form-control" required>
                    <option value="">— Select Type —</option>
                    <?php foreach ($serviceTypes as $st): ?>
                    <option value="<?php echo htmlspecialchars($st->service_type_id); ?>">
                        <?php echo htmlspecialchars($st->type_name); ?>
                    </option>
                    <?php endforeach; ?>
                    <?php if (empty($serviceTypes)): ?>
                    <option disabled>No service types defined</option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea name="service_description" class="form-control" rows="3"
                          placeholder="Describe the issue or task..."></textarea>
            </div>

            <div class="modal-buttons">
                <button type="submit" class="btn btn-primary btn-sm rounded-lg">
                    <i class="fas fa-check mr-2"></i>Create Task
                </button>
                <button type="button" class="btn btn-secondary btn-sm rounded-lg" onclick="closeAddModal()">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== Assign Agent Modal ===== -->
<div id="assignModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">
            <i class="fas fa-user-plus mr-2 text-success"></i>Assign Agent
        </h3>
        <p id="assignTaskInfo" class="text-secondary mb-4"></p>
        <form method="POST" id="assignForm" action="">
            <div class="mb-4">
                <label class="form-label">Select Service Agent <span class="text-danger">*</span></label>
                <select name="agent_id" id="assignAgentSelect" class="form-control" required>
                    <option value="">— Select Agent —</option>
                    <?php foreach ($agents as $a): ?>
                    <option value="<?php echo htmlspecialchars($a->id); ?>">
                        <?php echo htmlspecialchars($a->full_name); ?>
                        <?php if (!empty($a->agent_status)): ?> (<?php echo htmlspecialchars($a->agent_status); ?>)<?php endif; ?>
                    </option>
                    <?php endforeach; ?>
                    <?php if (empty($agents)): ?>
                    <option disabled>No agents available</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="modal-buttons">
                <button type="submit" class="btn btn-primary btn-sm rounded-lg">
                    <i class="fas fa-check mr-2"></i>Assign
                </button>
                <button type="button" class="btn btn-secondary btn-sm rounded-lg" onclick="closeAssignModal()">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== Delete Confirm Modal ===== -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">
            <i class="fas fa-exclamation-triangle mr-2 text-danger"></i>Delete Task
        </h3>
        <p id="deleteMessage" class="text-secondary mb-6">Are you sure you want to delete this task?</p>
        <form method="POST" id="deleteForm" action="">
            <div class="modal-buttons">
                <button type="submit" class="btn btn-danger btn-sm rounded-lg">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
                <button type="button" class="btn btn-secondary btn-sm rounded-lg" onclick="closeDeleteModal()">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const BASE  = '<?php echo URLROOT; ?>';
let tasks   = <?php echo $tasksJson; ?>;
let agents  = <?php echo $agentsJson; ?>;

// ─── Stats ───────────────────────────────────────────────────────
function updateStats(list) {
    const assigned   = list.filter(t => t.assigned).length;
    const unassigned = list.length - assigned;
    const pending    = list.filter(t => t.status === 'Pending').length;
    document.getElementById('statTotal').innerText      = list.length;
    document.getElementById('statAssigned').innerText   = assigned;
    document.getElementById('statUnassigned').innerText = unassigned;
    document.getElementById('statPending').innerText    = pending;
}

// ─── Table ───────────────────────────────────────────────────────
function renderTable(list) {
    const tbody = document.getElementById('tableBody');
    if (!list.length) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-secondary py-6">
            <i class="fas fa-inbox fa-2x mb-2"></i><br>No tasks found.
        </td></tr>`;
        return;
    }

    tbody.innerHTML = list.map(t => {
        const statusBadge = t.status === 'Completed'
            ? `<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>Completed</span>`
            : t.status === 'Pending'
            ? `<span class="badge badge-warning"><i class="fas fa-clock mr-1"></i>Pending</span>`
            : `<span class="badge badge-primary"><i class="fas fa-spinner mr-1"></i>${t.status}</span>`;

        const agentCell = t.agent
            ? `<span class="text-success"><i class="fas fa-user mr-1"></i>${t.agent}</span>`
            : `<span class="text-secondary"><i class="fas fa-user-slash mr-1"></i>Unassigned</span>`;

        const desc = t.description.length > 40 ? t.description.slice(0, 40) + '…' : (t.description || '—');
        const date = t.date ? new Date(t.date).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'}) : '—';

        return `<tr>
            <td class="text-sm font-semibold">#${t.id}</td>
            <td class="text-sm">${t.type}</td>
            <td class="text-sm">${t.customer}</td>
            <td class="text-sm text-secondary" title="${t.description}">${desc}</td>
            <td class="text-sm">${date}</td>
            <td class="text-sm">${agentCell}</td>
            <td class="text-sm">${statusBadge}</td>
            <td class="text-sm">
                <button class="btn btn-primary btn-sm rounded-lg bg-success mb-1"
                        onclick="openAssignModal(${t.id}, '${t.agent_id||''}', '${t.type}', '${t.customer}')">
                    <i class="fas fa-user-plus mr-1"></i>Assign
                </button>
                <button class="btn btn-primary btn-sm rounded-lg bg-error"
                        onclick="openDeleteModal(${t.id}, '${t.type}', '${t.customer}')">
                    <i class="fas fa-trash mr-1"></i>Delete
                </button>
            </td>
        </tr>`;
    }).join('');
}

// ─── Filter ───────────────────────────────────────────────────────
function filterAndRender() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    const assign = document.getElementById('assignFilter').value;

    const filtered = tasks.filter(t => {
        const matchSearch = t.type.toLowerCase().includes(search) ||
                            t.customer.toLowerCase().includes(search);
        const matchStatus = status === 'all' || t.status === status;
        const matchAssign = assign === 'all' ||
                            (assign === 'assigned'   &&  t.assigned) ||
                            (assign === 'unassigned' && !t.assigned);
        return matchSearch && matchStatus && matchAssign;
    });

    renderTable(filtered);
    updateStats(filtered);
}

document.getElementById('searchInput').addEventListener('input',   filterAndRender);
document.getElementById('statusFilter').addEventListener('change', filterAndRender);
document.getElementById('assignFilter').addEventListener('change', filterAndRender);
filterAndRender(); // initial render

// ─── Modal helpers ────────────────────────────────────────────────
const $ = id => document.getElementById(id);

function showAddModal()    { $('addModal').classList.add('show'); }
function closeAddModal()   { $('addModal').classList.remove('show'); }
function closeAssignModal(){ $('assignModal').classList.remove('show'); }
function closeDeleteModal(){ $('deleteModal').classList.remove('show'); }

function openAssignModal(taskId, currentAgentId, type, customer) {
    $('assignTaskInfo').innerText = `Task: "${type}" for ${customer}`;
    $('assignForm').action = `${BASE}/operationmanager/maintenance/assign/${taskId}`;
    $('assignAgentSelect').value = currentAgentId || '';
    $('assignModal').classList.add('show');
}

function openDeleteModal(taskId, type, customer) {
    $('deleteMessage').innerText = `Delete "${type}" task for ${customer}? This cannot be undone.`;
    $('deleteForm').action = `${BASE}/operationmanager/maintenance/delete/${taskId}`;
    $('deleteModal').classList.add('show');
}

// Close on outside click
window.addEventListener('click', e => {
    if (e.target === $('addModal'))    closeAddModal();
    if (e.target === $('assignModal')) closeAssignModal();
    if (e.target === $('deleteModal')) closeDeleteModal();
});
</script>
