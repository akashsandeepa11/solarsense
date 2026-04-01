<?php
// Map real DB tasks to the format expected by the JS
// NOTE: Controller::view() does NOT call extract($data), so use $data['tasks']
$rawTasks = $data['tasks'] ?? [];
$mappedTasks = [];
foreach ($rawTasks as $t) {
    // DB status: 'Pending' | 'In Progress' | 'Completed'
    // JS status keys: 'pending' | 'in-progress' | 'completed'
    $dbStatus = $t->status ?? 'Pending';
    $jsStatus = match(strtolower(trim($dbStatus))) {
        'in progress', 'in-progress' => 'in-progress',
        'completed'                   => 'completed',
        default                       => 'pending',
    };
    $mappedTasks[] = [
        'id'       => $t->task_id,
        'title'    => $t->title    ?? 'Untitled Task',
        'customer' => $t->customer ?? 'Unknown Customer',
        'date'     => $t->date     ? date('Y-m-d', strtotime($t->date)) : 'N/A',
        'notes'    => $t->notes    ?? '',
        'address'  => $t->address  ?? 'N/A',
        'contact_number' => $t->contact_number ?? 'N/A',
        'status'   => $jsStatus,
    ];
}



// var_dump($mappedTasks);
?>

<div class="content-area" style="padding: 1.5rem;">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'My Tasks',
        'description' => 'Manage and track your assigned service tasks',
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Summary Cards -->
    <?php
    $config = [
        'stats' => [
            [
                'label' => 'Pending Tasks',
                'value' => '0',
                'icon' => 'fas fa-clock',
                'color' => 'warning'
            ],
            [
                'label' => 'In Progress',
                'value' => '0',
                'icon' => 'fas fa-tasks',
                'color' => 'primary'
            ],
        //     [
        //         'label' => 'Completed',
        //         'value' => '0',
        //         'icon' => 'fas fa-check-circle',
        //         'color' => 'success'
        //     ]
        ]
    ];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <!-- Filter Bar -->
    <?php
    $config = [
        'search' => [
            'id' => 'searchBar',
            'name' => 'search',
            'label' => 'Search Tasks',
            'placeholder' => 'Search by title or customer...'
        ],
        'filters' => [
            [
                'id' => 'statusFilter',
                'name' => 'status',
                'label' => 'Status',
                'options' => [
                    ['value' => 'all',         'label' => 'All Status'],
                    ['value' => 'pending',     'label' => 'Pending'],
                    ['value' => 'in-progress', 'label' => 'In Progress'],
                    ['value' => 'completed',   'label' => 'Completed']
                ]
            ],
            [
                'id' => 'sortOption',
                'name' => 'sort',
                'label' => 'Sort By',
                'options' => [
                    ['value' => 'date',     'label' => 'Date'],
                    ['value' => 'customer', 'label' => 'Customer']
                ]
            ]
        ],
        'buttons' => [],
        'form_action' => '',
        'form_method' => 'GET',
        'auto_submit' => false,
        'reset_on_clear' => false,
        'result_count' => true,
        'result_count_id' => 'resultCount'
    ];
    include __DIR__ . '/../../inc/components/filter_bar.php';
    ?>

    <!-- Task List -->
    <div id="taskList"></div>

    <!-- Empty State -->
    <div id="emptyState" class="card shadow-sm rounded-xl" style="display: none;">
        <div class="card-body text-center" style="padding: 3rem;">
            <i class="fas fa-tasks text-secondary" style="font-size: 4rem; opacity: 0.3;"></i>
            <h4 class="mt-4 mb-2">No Tasks Found</h4>
            <p class="text-secondary mb-0">There are no tasks matching your search criteria.</p>
        </div>
    </div>
</div>

<!-- View Task Modal -->
<div id="taskModal" class="custom-modal" style="display: none;">
    <div class="modal-overlay" onclick="closeTaskModal()"></div>
    <div class="modal-dialog" style="max-width: 700px;">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-list text-primary mr-2"></i>Task Details
                </h5>
                <button type="button" class="btn-close" onclick="closeTaskModal()" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <h4 class="mb-2 font-bold" id="modalTitle"></h4>
                        <div class="d-flex gap-2 align-items-center">
                            <span id="modalPriorityBadge" class="badge"></span>
                            <span id="modalStatusBadge" class="badge"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Customer Name</label>
                        <p class="mb-0 font-semibold" id="modalCustomer"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Panel ID</label>
                        <p class="mb-0 font-semibold" id="modalPanel"></p>
                    </div>
                    <div class="col-12">
                        <label class="text-secondary text-sm mb-1">Address</label>
                        <p class="mb-0 font-semibold" id="modalAddress"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Date Assigned</label>
                        <p class="mb-0 font-semibold" id="modalDate"></p>
                    </div>
                    <div class="col-12">
                        <label class="text-secondary text-sm mb-1">Notes</label>
                        <p class="mb-0 font-semibold" id="modalNotes"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Contact Number</label>
                        <p class="mb-0 font-semibold" id="modalContactNumber"></p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" onclick="closeTaskModal()">
                    <i class="fas fa-times mr-2"></i>Close
                </button>
                <button type="button" id="modalStartBtn" class="btn btn-sm btn-primary" style="display: none;">
                    <i class="fas fa-play mr-2"></i>Start Task
                </button>
                <button type="button" id="modalCompleteBtn" class="btn btn-sm btn-success" style="display: none;">
                    <i class="fas fa-check mr-2"></i>Complete Task
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Start Task Confirmation Modal -->
<?php
$config = [
    'modal_id' => 'startTaskModal',
    'title' => 'Start Task',
    'icon' => 'fas fa-play-circle',
    'icon_color' => 'text-primary',
    'heading' => 'Start this task?',
    'message' => 'You are about to start working on this task.',
    'confirm_text' => 'Start Task',
    'cancel_text' => 'Cancel',
    'confirm_action' => 'confirmStartTask()',
    'confirm_method' => 'onclick',
    'confirm_class' => 'btn-primary',
    'confirm_icon' => 'fas fa-play'
];
include __DIR__ . '/../../inc/models/confirmation_modal.php';
?>

<!-- Complete Task Confirmation Modal -->
<?php
$config = [
    'modal_id' => 'completeTaskModal',
    'title' => 'Complete Task',
    'icon' => 'fas fa-check-circle',
    'icon_color' => 'text-success',
    'heading' => 'Complete this task?',
    'message' => 'You will be redirected to the service report page to complete your task details.',
    'confirm_text' => 'Continue to Report',
    'cancel_text' => 'Cancel',
    'confirm_action' => 'confirmCompleteTask()',
    'confirm_method' => 'onclick',
    'confirm_class' => 'btn-success',
    'confirm_icon' => 'fas fa-arrow-right'
];
include __DIR__ . '/../../inc/models/confirmation_modal.php';
?>

<style>
/* Task Card Styling */
.task-card {
    background: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
    border-left: 4px solid #ccc;
    transition: all 0.3s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.task-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.task-card.priority-high {
    border-left-color: #ef4444;
}

.task-card.priority-medium {
    border-left-color: #f59e0b;
}

.task-card.priority-low {
    border-left-color: #22c55e;
}

.task-info {
    flex: 1;
    min-width: 300px;
}

.task-info h3 {
    margin: 0 0 0.5rem;
    font-size: 1.125rem;
    font-weight: 600;
    color: #212121;
}

.task-info p {
    margin: 0.25rem 0;
    font-size: 0.875rem;
    color: #6b7280;
}

.task-info p i {
    width: 20px;
    color: #9ca3af;
}

.task-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}

.badge.priority-high {
    background-color: #fee2e2;
    color: #b91c1c;
}

.badge.priority-medium {
    background-color: #fef3c7;
    color: #92400e;
}

.badge.priority-low {
    background-color: #dcfce7;
    color: #166534;
}

.badge.status-pending {
    background-color: #f3f4f6;
    color: #374151;
    color: #ff0000;
}

.badge.status-in-progress {
    background-color: #dbeafe;
    color: #1d4ed8;
}

.badge.status-completed {
    background-color: #dcfce7;
    color: #166534;
}

/* Modal Styling */
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
}

.custom-modal.show {
    display: flex;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-dialog {
    position: relative;
    z-index: 10000;
    width: 100%;
    max-width: 600px;
    margin: 1rem;
}

.modal-content {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: scale(0.9);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
    color: #212121;
    display: flex;
    align-items: center;
}

.btn-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #6b7280;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.375rem;
    transition: all 0.2s;
}

.btn-close:hover {
    background-color: #f3f4f6;
    color: #212121;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

/* Responsive Design */
@media (max-width: 768px) {
    .task-card {
        flex-direction: column;
        align-items: flex-start;
    }

    .task-info {
        min-width: 100%;
    }

    .task-actions {
        width: 100%;
        justify-content: flex-start;
    }

    .modal-dialog {
        margin: 0.5rem;
    }

    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 1rem;
    }
}
</style>
<script>
// Safely encode PHP array into JS
const tasks = <?php echo json_encode(
    $mappedTasks,
    JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
); ?>;

console.log("Tasks loaded:", tasks);

const taskList = document.getElementById("taskList");
const searchBar = document.getElementById("searchBar");
const statusFilter = document.getElementById("statusFilter");
const sortOption = document.getElementById("sortOption");
const emptyState = document.getElementById("emptyState");

// Store current task for modal actions
let currentTask = null;

// -----------------------------
// Update summary stat cards
// -----------------------------
function updateStatCards() {
    const pendingCount    = tasks.filter(t => t.status === 'pending').length;
    const inProgressCount = tasks.filter(t => t.status === 'in-progress').length;
    const completedCount  = tasks.filter(t => t.status === 'completed').length;

    const statCards = document.querySelectorAll('.stat-card .stat-value');
    if (statCards.length >= 2) {
        statCards[0].textContent = pendingCount;
        statCards[1].textContent = inProgressCount;
        // statCards[2].textContent = completedCount;
    }
}

// -----------------------------
// Persist status to server
// -----------------------------
function persistStatus(taskId, newStatus) {
    const dbStatus = { 'pending': 'Pending', 'in-progress': 'In Progress', 'completed': 'Completed' }[newStatus] || newStatus;
    const fd = new FormData();
    fd.append('task_id', taskId);
    fd.append('status', dbStatus);
    fetch('<?php echo URLROOT ?>/serviceagent/update_task_status', { method: 'POST', body: fd })
        .then(r => r.json())
        .catch(e => console.error('Status update failed', e));
}

// -----------------------------
// Confirmation modal actions
// -----------------------------
function confirmStartTask() {
    if (!currentTask) return;
    currentTask.status = 'in-progress';
    persistStatus(currentTask.id, 'in-progress');
    updateStatCards();
    renderTasks();
    closeConfirmationModal('startTaskModal');
    closeTaskModal();
}

function confirmCompleteTask() {
    if (!currentTask) return;
    persistStatus(currentTask.id, 'completed');
    closeConfirmationModal('completeTaskModal');
    closeTaskModal();
    window.location.href = "<?php echo URLROOT?>/serviceagent/report/" + currentTask.id;
}

// -----------------------------
// Open task modal
// -----------------------------
function openTaskModal(task) {
    currentTask = task;

    const modal = document.getElementById("taskModal");
    const modalTitle = document.getElementById("modalTitle");
    const modalCustomer = document.getElementById("modalCustomer");
    const modalAddress = document.getElementById("modalAddress");
    const modalContactNumber = document.getElementById("modalContactNumber");
    const modalDate = document.getElementById("modalDate");
    const modalPanel = document.getElementById("modalPanel");
    const modalNotes = document.getElementById("modalNotes");
    const modalPriorityBadge = document.getElementById("modalPriorityBadge");
    const modalStatusBadge = document.getElementById("modalStatusBadge");
    const modalStartBtn = document.getElementById("modalStartBtn");
    const modalCompleteBtn = document.getElementById("modalCompleteBtn");

    modalTitle.textContent = task.title || '—';
    modalCustomer.textContent = task.customer || '—';
    modalAddress.textContent = task.address || '—';
    modalContactNumber.textContent = task.contact_number || '—';
    modalDate.textContent = task.date || '—';
    modalNotes.textContent = task.notes || '(No notes)';
    if (modalPanel) modalPanel.textContent = '#' + task.id;

    // Status badge
    const statusLabel = { 'pending': 'Pending', 'in-progress': 'In Progress', 'completed': 'Completed' }[task.status] || task.status;
    modalPriorityBadge.style.display = 'none';
    modalStatusBadge.className = `badge status-${task.status}`;
    modalStatusBadge.textContent = statusLabel;

    // Show/hide action buttons
    modalStartBtn.style.display = 'none';
    modalCompleteBtn.style.display = 'none';
    if (task.status === 'pending') {
        modalStartBtn.style.display = 'inline-flex';
        modalStartBtn.onclick = function() {
            closeTaskModal();
            showConfirmationModal('startTaskModal');
        };
    } else if (task.status === 'in-progress') {
        modalCompleteBtn.style.display = 'inline-flex';
        modalCompleteBtn.onclick = function() {
            closeTaskModal();
            showConfirmationModal('completeTaskModal');
        };
    }

    modal.style.display = 'flex';
    modal.classList.add("show");
}

function closeTaskModal() {
    const modal = document.getElementById("taskModal");
    modal.classList.remove("show");
    modal.style.display = 'none';
}

// -----------------------------
// Render task list
// -----------------------------
function renderTasks() {
    taskList.innerHTML = '';

    let filteredTasks = tasks.filter(task => {
        const q = searchBar.value.toLowerCase();
        const matchesSearch = task.title.toLowerCase().includes(q) ||
                              task.customer.toLowerCase().includes(q);
        const matchesStatus = statusFilter.value === 'all' || task.status === statusFilter.value;
        return matchesSearch && matchesStatus;
    });

    // Sort
    if (sortOption.value === 'date') {
        filteredTasks.sort((a, b) => {
            const dateA = a.date ? new Date(a.date) : 0;
            const dateB = b.date ? new Date(b.date) : 0;
            return dateB - dateA;
        });
    } else if (sortOption.value === 'customer') {
        filteredTasks.sort((a, b) => a.customer.localeCompare(b.customer));
    }

    // Update result count
    const resultCount = document.getElementById('resultCount');
    if (resultCount) resultCount.textContent = filteredTasks.length;

    // Empty state
    emptyState.style.display = filteredTasks.length === 0 ? 'block' : 'none';
    if (filteredTasks.length === 0) return;

    // Render cards
    filteredTasks.forEach(task => {
        const card = document.createElement('div');

        const statusLabel = { 'pending': 'Pending', 'in-progress': 'In Progress', 'completed': 'Completed' }[task.status] || task.status;
        const borderColor = { 'pending': '#f59e0b', 'in-progress': '#3b82f6', 'completed': '#22c55e' }[task.status] || '#ccc';

        card.className = 'task-card';
        card.style.borderLeftColor = borderColor;

        let actionButtons = '';
        if (task.status === 'pending') {
            actionButtons = `<button class="btn btn-sm btn-primary start-btn"><i class="fas fa-play mr-1"></i>Start</button>`;
        } else if (task.status === 'in-progress') {
            actionButtons = `<button class="btn btn-sm btn-success done-btn"><i class="fas fa-check mr-1"></i>Complete</button>`;
        }

        card.innerHTML = `
            <div class="task-info">
                <h3>${task.title}</h3>
                <p><i class="fas fa-user"></i> ${task.customer}</p>
                <p><i class="fas fa-calendar"></i> ${task.date || '—'}</p>
            </div>
            <div class="task-actions">
                <span class="badge status-${task.status}">${statusLabel}</span>
                <button class="btn btn-sm btn-info view-btn"><i class="fas fa-eye mr-1"></i>View</button>
                ${actionButtons}
            </div>
        `;

        // View button
        card.querySelector('.view-btn').addEventListener('click', () => openTaskModal(task));

        // Start button
        const startBtn = card.querySelector('.start-btn');
        if (startBtn) startBtn.addEventListener('click', () => {
            currentTask = task;
            closeTaskModal();
            showConfirmationModal('startTaskModal');
        });

        // Done button
        const doneBtn = card.querySelector('.done-btn');
        if (doneBtn) doneBtn.addEventListener('click', () => {
            currentTask = task;
            closeTaskModal();
            showConfirmationModal('completeTaskModal');
        });

        taskList.appendChild(card);
    });
}

// -----------------------------
// Event listeners
// -----------------------------
searchBar.addEventListener("input", renderTasks);
statusFilter.addEventListener("change", renderTasks);
sortOption.addEventListener("change", renderTasks);

// Click outside modal to close
window.addEventListener("click", (e) => {
    const modalOverlay = document.querySelector(".modal-overlay");
    if (modalOverlay && e.target === modalOverlay) {
        closeTaskModal();
    }
});

// -----------------------------
// Initial render
// -----------------------------
updateStatCards();
renderTasks();

</script>