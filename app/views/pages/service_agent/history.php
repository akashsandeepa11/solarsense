<?php
// Dummy task data (all done tasks)
$tasks = [
    [
        "title" => "Inverter Fault Detected",
        "customer" => "John Doe",
        "address" => "123 Main Street, Colombo",
        "date" => "2025-09-01",
        "completedDate" => "2025-09-02",
        "priority" => "high",
        "panelId" => "P-1001",
        "notes" => "Inverter shutting down frequently. Successfully replaced faulty inverter unit.",
        "status" => "done"
    ],
    [
        "title" => "Install Solar Battery",
        "customer" => "Sarah Smith",
        "address" => "45 Green Lane, Kandy",
        "date" => "2025-08-30",
        "completedDate" => "2025-08-31",
        "priority" => "medium",
        "panelId" => "P-1002",
        "notes" => "New battery installation for energy storage system completed successfully.",
        "status" => "done"
    ],
    [
        "title" => "Routine Maintenance",
        "customer" => "Michael Lee",
        "address" => "78 Oak Road, Galle",
        "date" => "2025-08-29",
        "completedDate" => "2025-08-29",
        "priority" => "low",
        "panelId" => "P-1003",
        "notes" => "Quarterly checkup completed. All systems operating normally.",
        "status" => "done"
    ],
    [
        "title" => "Panel Cleaning Service",
        "customer" => "Emily Johnson",
        "address" => "22 Beach Avenue, Negombo",
        "date" => "2025-08-28",
        "completedDate" => "2025-08-28",
        "priority" => "low",
        "panelId" => "P-1004",
        "notes" => "Annual solar panel cleaning and inspection completed.",
        "status" => "done"
    ],
    [
        "title" => "Wiring Issue Repair",
        "customer" => "David Brown",
        "address" => "15 Hill View, Nuwara Eliya",
        "date" => "2025-08-27",
        "completedDate" => "2025-08-28",
        "priority" => "high",
        "panelId" => "P-1005",
        "notes" => "Exposed wiring repaired and secured properly.",
        "status" => "done"
    ],
    [
        "title" => "System Upgrade",
        "customer" => "Lisa Anderson",
        "address" => "88 Park Street, Jaffna",
        "date" => "2025-08-26",
        "completedDate" => "2025-08-27",
        "priority" => "medium",
        "panelId" => "P-1006",
        "notes" => "Upgraded existing system to increase capacity by 30%.",
        "status" => "done"
    ]
];
?>

<div class="content-area" style="padding: 1.5rem;">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Task History',
        'description' => 'View your completed service tasks',
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Summary Card -->
    <?php
    $config = [
        'stats' => [
            [
                'label' => 'Total Completed',
                'value' => count($tasks),
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
            'label' => 'Search History',
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
                'id' => 'sortOption',
                'name' => 'sort',
                'label' => 'Sort By',
                'options' => [
                    ['value' => 'date', 'label' => 'Completion Date'],
                    ['value' => 'customer', 'label' => 'Customer'],
                    ['value' => 'priority', 'label' => 'Priority']
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

    <!-- Date Range Filter -->
    <div class="card shadow-sm rounded-xl mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <?php
                    $inputConfig = [
                        'id' => 'startDate',
                        'name' => 'start_date',
                        'label' => 'From Date',
                        'type' => 'date',
                        'value' => '',
                        'icon' => 'fas fa-calendar-alt',
                        'editable' => true,
                        'required' => false
                    ];
                    include __DIR__ . '/../../inc/components/input_field.php';
                    ?>
                </div>
                <div class="col-md-5">
                    <?php
                    $inputConfig = [
                        'id' => 'endDate',
                        'name' => 'end_date',
                        'label' => 'To Date',
                        'type' => 'date',
                        'value' => '',
                        'icon' => 'fas fa-calendar-check',
                        'editable' => true,
                        'required' => false
                    ];
                    include __DIR__ . '/../../inc/components/input_field.php';
                    ?>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-secondary w-100" onclick="clearDateFilter()">
                        <i class="fas fa-times mr-1"></i>Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Task List -->
    <div id="taskList"></div>

    <!-- Empty State -->
    <div id="emptyState" class="card shadow-sm rounded-xl" style="display: none;">
        <div class="card-body text-center" style="padding: 3rem;">
            <i class="fas fa-history text-secondary" style="font-size: 4rem; opacity: 0.3;"></i>
            <h4 class="mt-4 mb-2">No Completed Tasks Found</h4>
            <p class="text-secondary mb-0">There are no completed tasks matching your search criteria.</p>
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
                    <i class="fas fa-clipboard-check text-success mr-2"></i>Completed Task Details
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
                            <span class="badge status-done">Completed</span>
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
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Date Completed</label>
                        <p class="mb-0 font-semibold" id="modalCompletedDate"></p>
                    </div>
                    <div class="col-12">
                        <label class="text-secondary text-sm mb-1">Service Notes</label>
                        <p class="mb-0 font-semibold" id="modalNotes"></p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" onclick="closeTaskModal()">
                    <i class="fas fa-times mr-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

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
const sortOption = document.getElementById("sortOption");
const startDate = document.getElementById("startDate");
const endDate = document.getElementById("endDate");
const emptyState = document.getElementById("emptyState");

// Clear date filter
function clearDateFilter() {
    startDate.value = '';
    endDate.value = '';
    renderTasks();
}

// Render tasks
function renderTasks() {
    taskList.innerHTML = "";

    // Only done tasks
    let filteredTasks = tasks.filter(task => task.status === "done");

    // Search filter
    filteredTasks = filteredTasks.filter(task =>
        task.title.toLowerCase().includes(searchBar.value.toLowerCase()) ||
        task.customer.toLowerCase().includes(searchBar.value.toLowerCase())
    );

    // Priority filter
    if (priorityFilter.value !== "all") {
        filteredTasks = filteredTasks.filter(task => task.priority === priorityFilter.value);
    }

    // Date range filter
    if (startDate.value) {
        filteredTasks = filteredTasks.filter(task => new Date(task.completedDate) >= new Date(startDate.value));
    }
    if (endDate.value) {
        filteredTasks = filteredTasks.filter(task => new Date(task.completedDate) <= new Date(endDate.value));
    }

    // Sorting
    if (sortOption.value === "date") {
        filteredTasks.sort((a, b) => new Date(b.completedDate) - new Date(a.completedDate));
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

        // Get priority label
        const priorityLabel = task.priority.charAt(0).toUpperCase() + task.priority.slice(1);

        card.innerHTML = `
            <div class="task-info">
                <h3>${task.title}</h3>
                <p><i class="fas fa-user"></i> ${task.customer}</p>
                <p><i class="fas fa-map-marker-alt"></i> ${task.address}</p>
                <p><i class="fas fa-calendar-check"></i> Completed: ${task.completedDate}</p>
            </div>
            <div class="task-actions">
                <span class="badge priority-${task.priority}">${priorityLabel}</span>
                <span class="badge status-done">Completed</span>
                <button class="btn btn-sm btn-info view-btn">
                    <i class="fas fa-eye mr-1"></i>View
                </button>
            </div>
        `;

        // View button
        card.querySelector(".view-btn").addEventListener("click", () => openTaskModal(task));

        taskList.appendChild(card);
    });
}

// Open task modal
function openTaskModal(task) {
    const modal = document.getElementById("taskModal");
    const modalTitle = document.getElementById("modalTitle");
    const modalCustomer = document.getElementById("modalCustomer");
    const modalAddress = document.getElementById("modalAddress");
    const modalDate = document.getElementById("modalDate");
    const modalCompletedDate = document.getElementById("modalCompletedDate");
    const modalPanel = document.getElementById("modalPanel");
    const modalNotes = document.getElementById("modalNotes");
    const modalPriorityBadge = document.getElementById("modalPriorityBadge");

    modalTitle.textContent = task.title;
    modalCustomer.textContent = task.customer;
    modalAddress.textContent = task.address;
    modalDate.textContent = task.date;
    modalCompletedDate.textContent = task.completedDate;
    modalPanel.textContent = task.panelId;
    modalNotes.textContent = task.notes;

    // Set priority badge
    const priorityLabel = task.priority.charAt(0).toUpperCase() + task.priority.slice(1);
    modalPriorityBadge.className = `badge priority-${task.priority}`;
    modalPriorityBadge.textContent = priorityLabel;

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
    if (e.target === document.querySelector(".modal-overlay")) {
        closeTaskModal();
    }
});

// Filters & sorting event listeners
searchBar.addEventListener("input", renderTasks);
priorityFilter.addEventListener("change", renderTasks);
sortOption.addEventListener("change", renderTasks);
startDate.addEventListener("change", renderTasks);
endDate.addEventListener("change", renderTasks);

// Initial render
renderTasks();
</script>
