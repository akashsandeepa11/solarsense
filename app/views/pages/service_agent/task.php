<?php
// Dummy task data 
$tasks = [
    [
        "title" => "Inverter Fault Detected",
        "customer" => "John Doe",
        "address" => "123 Main Street, Colombo",
        "date" => "2025-09-01",
        "priority" => "high",
        "panelId" => "P-1001",
        "notes" => "Inverter shutting down frequently. Customer reports power loss during peak hours.",
        "status" => "pending"
    ],
    [
        "title" => "Install Solar Battery",
        "customer" => "Sarah Smith",
        "address" => "45 Green Lane, Kandy",
        "date" => "2025-08-30",
        "priority" => "medium",
        "panelId" => "P-1002",
        "notes" => "New battery installation for energy storage system.",
        "status" => "in-progress"
    ],
    [
        "title" => "Routine Maintenance",
        "customer" => "Michael Lee",
        "address" => "78 Oak Road, Galle",
        "date" => "2025-08-29",
        "priority" => "low",
        "panelId" => "P-1003",
        "notes" => "Quarterly checkup and system performance evaluation.",
        "status" => "pending"
    ],
    [
        "title" => "Panel Cleaning Service",
        "customer" => "Emily Johnson",
        "address" => "22 Beach Avenue, Negombo",
        "date" => "2025-08-28",
        "priority" => "low",
        "panelId" => "P-1004",
        "notes" => "Annual solar panel cleaning and inspection.",
        "status" => "done"
    ],
    [
        "title" => "Wiring Issue Repair",
        "customer" => "David Brown",
        "address" => "15 Hill View, Nuwara Eliya",
        "date" => "2025-08-27",
        "priority" => "high",
        "panelId" => "P-1005",
        "notes" => "Exposed wiring detected, immediate repair required.",
        "status" => "in-progress"
    ],
    [
        "title" => "System Upgrade",
        "customer" => "Lisa Anderson",
        "address" => "88 Park Street, Jaffna",
        "date" => "2025-08-26",
        "priority" => "medium",
        "panelId" => "P-1006",
        "notes" => "Upgrade existing system to increase capacity.",
        "status" => "pending"
    ]
];
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
            [
                'label' => 'Completed',
                'value' => '0',
                'icon' => 'fas fa-check-circle',
                'color' => 'success'
            ]
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
                'id' => 'priorityFilter',
                'name' => 'priority',
                'label' => 'Priority',
                'options' => [
                    ['value' => 'all', 'label' => 'All Priorities'],
                    ['value' => 'high', 'label' => 'High'],
                    ['value' => 'medium', 'label' => 'Medium'],
                    ['value' => 'low', 'label' => 'Low']
                ]
            ],
            [
                'id' => 'statusFilter',
                'name' => 'status',
                'label' => 'Status',
                'options' => [
                    ['value' => 'all', 'label' => 'All Status'],
                    ['value' => 'pending', 'label' => 'Pending'],
                    ['value' => 'in-progress', 'label' => 'In Progress'],
                    ['value' => 'done', 'label' => 'Done']
                ]
            ],
            [
                'id' => 'sortOption',
                'name' => 'sort',
                'label' => 'Sort By',
                'options' => [
                    ['value' => 'priority', 'label' => 'Priority'],
                    ['value' => 'date', 'label' => 'Date'],
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
}

.badge.status-in-progress {
    background-color: #dbeafe;
    color: #1d4ed8;
}

.badge.status-done {
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
const tasks = <?php echo json_encode($tasks); ?>;

const taskList = document.getElementById("taskList");
const searchBar = document.getElementById("searchBar");
const priorityFilter = document.getElementById("priorityFilter");
const statusFilter = document.getElementById("statusFilter");
const sortOption = document.getElementById("sortOption");
const emptyState = document.getElementById("emptyState");

// Store current task for modal actions
let currentTask = null;

// Update stat cards
function updateStatCards() {
    const pendingCount = tasks.filter(t => t.status === "pending").length;
    const inProgressCount = tasks.filter(t => t.status === "in-progress").length;
    const doneCount = tasks.filter(t => t.status === "done").length;

    // Update stat card values
    const statCards = document.querySelectorAll('.stat-card .stat-value');
    if (statCards.length >= 3) {
        statCards[0].textContent = pendingCount;
        statCards[1].textContent = inProgressCount;
        statCards[2].textContent = doneCount;
    }
}

// Confirmation functions for modal actions
function confirmStartTask() {
    if (currentTask) {
        currentTask.status = 'in-progress';
        updateStatCards();
        renderTasks();
        closeConfirmationModal('startTaskModal');
        closeTaskModal();
    }
}

function confirmCompleteTask() {
    if (currentTask) {
        closeConfirmationModal('completeTaskModal');
        closeTaskModal();
        window.location.href = "<?php echo URLROOT?>/serviceagent/report/" + currentTask.panelId;
    }
}

// Render tasks
function renderTasks() {
    taskList.innerHTML = "";

    // Filter tasks
    let filteredTasks = tasks.filter(task => {
        const matchesSearch = task.title.toLowerCase().includes(searchBar.value.toLowerCase()) ||
                              task.customer.toLowerCase().includes(searchBar.value.toLowerCase());
        const matchesPriority = priorityFilter.value === "all" || task.priority === priorityFilter.value;
        const matchesStatus = statusFilter.value === "all" || task.status === statusFilter.value;
        return matchesSearch && matchesPriority && matchesStatus;
    });

    // Sort tasks
    if (sortOption.value === "date") {
        filteredTasks.sort((a, b) => new Date(b.date) - new Date(a.date));
    } else if (sortOption.value === "customer") {
        filteredTasks.sort((a, b) => a.customer.localeCompare(b.customer));
    } else if (sortOption.value === "priority") {
        const priorityOrder = { high: 1, medium: 2, low: 3 };
        filteredTasks.sort((a, b) => priorityOrder[a.priority] - priorityOrder[b.priority]);
    }

    // Update result count
    const resultCount = document.getElementById('resultCount');
    if (resultCount) {
        resultCount.textContent = filteredTasks.length;
    }

    // Show/hide empty state
    if (filteredTasks.length === 0) {
        emptyState.style.display = 'block';
        return;
    } else {
        emptyState.style.display = 'none';
    }

    // Render each task
    filteredTasks.forEach(task => {
        const card = document.createElement("div");
        card.className = `task-card priority-${task.priority}`;

        // Get priority and status labels
        const priorityLabel = task.priority.charAt(0).toUpperCase() + task.priority.slice(1);
        const statusLabel = task.status === 'in-progress' ? 'In Progress' : 
                          task.status.charAt(0).toUpperCase() + task.status.slice(1);

        // Determine action buttons
        let actionButtons = '';
        if (task.status === "pending") {
            actionButtons = `<button class="btn btn-sm btn-primary start-btn">
                <i class="fas fa-play mr-1"></i>Start
            </button>`;
        } else if (task.status === "in-progress") {
            actionButtons = `<button class="btn btn-sm btn-success done-btn">
                <i class="fas fa-check mr-1"></i>Complete
            </button>`;
        }

        card.innerHTML = `
            <div class="task-info">
                <h3>${task.title}</h3>
                <p><i class="fas fa-user"></i> ${task.customer}</p>
                <p><i class="fas fa-map-marker-alt"></i> ${task.address}</p>
                <p><i class="fas fa-calendar"></i> ${task.date}</p>
            </div>
            <div class="task-actions">
                <span class="badge priority-${task.priority}">${priorityLabel}</span>
                <span class="badge status-${task.status}">${statusLabel}</span>
                <button class="btn btn-sm btn-info view-btn">
                    <i class="fas fa-eye mr-1"></i>View
                </button>
                ${actionButtons}
            </div>
        `;

        // View button
        card.querySelector(".view-btn").addEventListener("click", () => openTaskModal(task));

        // Start button
        const startBtn = card.querySelector(".start-btn");
        if (startBtn) {
            startBtn.addEventListener("click", () => {
                task.status = "in-progress";
                updateStatCards();
                renderTasks();
            });
        }

        // Done button
        const doneBtn = card.querySelector(".done-btn");
        if (doneBtn) {
            doneBtn.addEventListener("click", () => {
                window.location.href = "<?php echo URLROOT?>/serviceagent/report/" + task.panelId;
            });
        }

        taskList.appendChild(card);
    });
}

// Open task modal
function openTaskModal(task) {
    currentTask = task; // Store the current task
    
    const modal = document.getElementById("taskModal");
    const modalTitle = document.getElementById("modalTitle");
    const modalCustomer = document.getElementById("modalCustomer");
    const modalAddress = document.getElementById("modalAddress");
    const modalDate = document.getElementById("modalDate");
    const modalPanel = document.getElementById("modalPanel");
    const modalNotes = document.getElementById("modalNotes");
    const modalPriorityBadge = document.getElementById("modalPriorityBadge");
    const modalStatusBadge = document.getElementById("modalStatusBadge");
    const modalStartBtn = document.getElementById("modalStartBtn");
    const modalCompleteBtn = document.getElementById("modalCompleteBtn");

    modalTitle.textContent = task.title;
    modalCustomer.textContent = task.customer;
    modalAddress.textContent = task.address;
    modalDate.textContent = task.date;
    modalPanel.textContent = task.panelId;
    modalNotes.textContent = task.notes;

    // Set badges
    const priorityLabel = task.priority.charAt(0).toUpperCase() + task.priority.slice(1);
    const statusLabel = task.status === 'in-progress' ? 'In Progress' : 
                      task.status.charAt(0).toUpperCase() + task.status.slice(1);
    
    modalPriorityBadge.className = `badge priority-${task.priority}`;
    modalPriorityBadge.textContent = priorityLabel;
    
    modalStatusBadge.className = `badge status-${task.status}`;
    modalStatusBadge.textContent = statusLabel;

    // Show/hide action buttons based on status
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

    // Show modal
    modal.style.display = 'flex';
    modal.classList.add("show");
}

// Close task modal
function closeTaskModal() {
    const modal = document.getElementById("taskModal");
    modal.classList.remove("show");
    modal.style.display = 'none';
}

// Close modal when clicking outside
window.addEventListener("click", (e) => {
    const modal = document.getElementById("taskModal");
    if (e.target === document.querySelector(".modal-overlay")) {
        closeTaskModal();
    }
});

// Filters & sorting event listeners
searchBar.addEventListener("input", renderTasks);
priorityFilter.addEventListener("change", renderTasks);
statusFilter.addEventListener("change", renderTasks);
sortOption.addEventListener("change", renderTasks);

// Initial render
updateStatCards();
renderTasks();
</script>

