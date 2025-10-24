<?php
// Customer complaints data
$tasks = [
    [
        "title" => "Inverter Fault Detected",
        "customer" => "John Doe",
        "address" => "123 Main Street, Colombo",
        "date" => "2025-09-01",
        "notes" => "Inverter shuts down intermittently during peak hours.",
        "status" => "pending"
    ],
    [
        "title" => "Install Solar Battery",
        "customer" => "Sarah Smith",
        "address" => "45 Green Lane, Kandy",
        "date" => "2025-08-30",
        "notes" => "Customer requested hybrid battery setup.",
        "status" => "done"
    ],
    [
        "title" => "Routine Maintenance",
        "customer" => "Michael Lee",
        "address" => "78 Oak Road, Negombo",
        "date" => "2025-08-29",
        "notes" => "Quarterly inspection and cleaning.",
        "status" => "pending"
    ],
    [
        "title" => "Panel Cleaning Request",
        "customer" => "Anusha Perera",
        "address" => "12 Temple Road, Galle",
        "date" => "2025-10-02",
        "notes" => "Dust accumulation reducing efficiency.",
        "status" => "done"
    ],
    [
        "title" => "System Not Generating Power",
        "customer" => "David Fernando",
        "address" => "21 Hill View, Matara",
        "date" => "2025-10-10",
        "notes" => "No power output since morning; inverter shows error 03.",
        "status" => "pending"
    ],
    [
        "title" => "Battery Backup Issue",
        "customer" => "Rashmi Silva",
        "address" => "56 Beach Drive, Trincomalee",
        "date" => "2025-09-20",
        "notes" => "Battery not charging properly during night mode.",
        "status" => "done"
    ],
    [
        "title" => "Voltage Fluctuation",
        "customer" => "Ishara Gunasekara",
        "address" => "102 Main Street, Kurunegala",
        "date" => "2025-10-03",
        "notes" => "Voltage fluctuates between 210–240V during operation.",
        "status" => "pending"
    ],
    [
        "title" => "App Connectivity Issue",
        "customer" => "Nuwan Jayasinghe",
        "address" => "88 Central Avenue, Colombo",
        "date" => "2025-09-25",
        "notes" => "Customer unable to view energy stats via mobile app.",
        "status" => "done"
    ]
];
?>

<div class="content-area" style="padding: 1.5rem;">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Customer Complaints',
        'description' => 'Manage and resolve customer support tickets and complaints',
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Filter & Search Section -->
    <?php
    $config = [
        'search' => [
            'id' => 'searchBar',
            'name' => 'search',
            'label' => 'Search Complaints',
            'placeholder' => 'Search by customer or issue...'
        ],
        'filters' => [
            [
                'id' => 'statusFilter',
                'name' => 'status',
                'label' => 'Status Filter',
                'options' => [
                    ['value' => 'all', 'label' => 'All Status'],
                    ['value' => 'pending', 'label' => 'Pending'],
                    ['value' => 'done', 'label' => 'Resolved']
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

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-xl">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div>
                            <div class="text-secondary text-sm">Pending Complaints</div>
                            <div class="h3 mb-0 font-bold" id="pendingCount">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-xl">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="text-secondary text-sm">Resolved Complaints</div>
                            <div class="h3 mb-0 font-bold" id="doneCount">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-xl">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div>
                            <div class="text-secondary text-sm">Total Complaints</div>
                            <div class="h3 mb-0 font-bold" id="totalCount">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaints List -->
    <div id="taskList"></div>

    <!-- Empty State -->
    <div id="emptyState" class="card shadow-sm rounded-xl" style="display: none;">
        <div class="card-body text-center" style="padding: 3rem;">
            <i class="fas fa-inbox text-secondary" style="font-size: 4rem; opacity: 0.3;"></i>
            <h4 class="mt-4 mb-2">No Complaints Found</h4>
            <p class="text-secondary mb-0">There are no complaints matching your search criteria.</p>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div id="viewModal" class="custom-modal" style="display: none;">
    <div class="modal-overlay" onclick="closeViewModal()"></div>
    <div class="modal-dialog" style="max-width: 700px;">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-ticket-alt text-primary mr-2"></i>Complaint Details
                </h5>
                <button type="button" class="btn-close" onclick="closeViewModal()" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <h4 class="mb-0 font-bold" id="modalTitle"></h4>
                        <span id="modalStatusBadge" class="badge mt-2"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Customer Name</label>
                        <p class="mb-0 font-semibold" id="modalUser"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Complaint Date</label>
                        <p class="mb-0 font-semibold" id="modalDate"></p>
                    </div>
                    <div class="col-12">
                        <label class="text-secondary text-sm mb-1">Address</label>
                        <p class="mb-0 font-semibold" id="modalAddress"></p>
                    </div>
                    <div class="col-12">
                        <label class="text-secondary text-sm mb-1">Complaint Details</label>
                        <p class="mb-0 font-semibold" id="modalNotes"></p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" onclick="closeViewModal()">
                    <i class="fas fa-times mr-2"></i>Close
                </button>
                <button type="button" id="modalResolveBtn" class="btn btn-sm btn-success" style="display: none;">
                    <i class="fas fa-check-circle mr-2"></i>Mark as Resolved
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Resolve Confirmation Modal -->
<?php
$config = [
    'modal_id' => 'resolveModal',
    'title' => 'Resolve Complaint',
    'icon' => 'fas fa-check-circle',
    'icon_color' => 'text-success',
    'heading' => 'Mark Complaint as Resolved',
    'message' => 'Are you sure you want to mark this complaint as resolved? This action confirms that the issue has been addressed.',
    'confirm_text' => 'Mark as Resolved',
    'cancel_text' => 'Cancel',
    'confirm_action' => '',
    'confirm_method' => 'onclick',
    'confirm_class' => 'btn-success',
    'confirm_icon' => 'fas fa-check-circle'
];
include __DIR__ . '/../../inc/models/confirmation_modal.php';
?>

<style>
.complaint-card {
    background: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
    border-left: 4px solid #fe9630;
    transition: all 0.3s ease;
}

.complaint-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    color: #ffffff;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stat-icon.bg-primary {
    background-color: #fe9630 !important;
}

.stat-icon.bg-success {
    background-color: #22c55e !important;
}

.stat-icon.bg-warning {
    background-color: #f59e0b !important;
}

.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    display: flex;
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
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease-in-out;
}

.modal-dialog {
    position: relative;
    z-index: 1051;
    width: 90%;
    max-width: 500px;
    animation: slideUp 0.3s ease-out;
}

.modal-content {
    background-color: #ffffff;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.modal-header {
    padding: 1.5rem;
    background-color: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #212121;
    margin: 0;
}

.modal-body {
    padding: 2rem 1.5rem;
}

.modal-footer {
    padding: 1.5rem;
    background-color: #f9fafb;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.btn-close {
    width: 1.5rem;
    height: 1.5rem;
    background: transparent;
    border: none;
    cursor: pointer;
    opacity: 0.5;
    transition: opacity 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

.btn-close:hover {
    opacity: 1;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>


<script>
const tasks = <?php echo json_encode($tasks); ?>;
const taskList = document.getElementById("taskList");
const searchBar = document.getElementById("searchBar");
const statusFilter = document.getElementById("statusFilter");
const emptyState = document.getElementById("emptyState");
const viewModal = document.getElementById("viewModal");

let currentTaskIndex = null;

function updateCounts(filtered) {
    const pending = filtered.filter(t => t.status === 'pending').length;
    const done = filtered.filter(t => t.status === 'done').length;
    const total = tasks.length;

    document.getElementById('pendingCount').textContent = pending;
    document.getElementById('doneCount').textContent = done;
    document.getElementById('totalCount').textContent = total;
    document.getElementById('resultCount').textContent = filtered.length;
}

function renderTasks() {
    taskList.innerHTML = "";
    const search = searchBar.value.toLowerCase();
    const filter = statusFilter.value;

    const filtered = tasks.filter(task => {
        const matchesSearch = task.title.toLowerCase().includes(search) || task.customer.toLowerCase().includes(search);
        const matchesFilter = filter === "all" || task.status === filter;
        return matchesSearch && matchesFilter;
    });

    updateCounts(filtered);

    if (filtered.length === 0) {
        emptyState.style.display = 'block';
        return;
    } else {
        emptyState.style.display = 'none';
    }

    filtered.forEach((task, index) => {
        const card = document.createElement("div");
        card.className = "complaint-card";

        const statusClass = task.status === 'pending' ? 'bg-warning text-dark' : 'bg-success';
        const statusIcon = task.status === 'pending' ? 'fa-exclamation-circle' : 'fa-check-circle';
        const priorityColor = task.status === 'pending' ? '#f59e0b' : '#22c55e';

        card.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-start gap-3">
                        <div class="stat-icon ${task.status === 'pending' ? 'bg-warning' : 'bg-success'}" style="width: 50px; height: 50px; font-size: 1.25rem;">
                            <i class="fas ${statusIcon}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1 font-bold">${task.title}</h5>
                            <div class="text-secondary text-sm mb-2">
                                <i class="fas fa-user mr-1"></i><strong>${task.customer}</strong>
                                <span class="mx-2">|</span>
                                <i class="fas fa-calendar mr-1"></i>${task.date}
                            </div>
                            <div class="text-secondary text-sm">
                                <i class="fas fa-map-marker-alt mr-1"></i>${task.address}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex gap-2 justify-content-end flex-wrap">
                        <button class="btn btn-sm ${statusClass}" style="cursor: default; pointer-events: none;">
                            <i class="fas ${statusIcon} mr-1"></i>${task.status === 'pending' ? 'Pending' : 'Resolved'}
                        </button>
                        <button class="btn btn-sm btn-primary view-details-btn" data-index="${index}">
                            <i class="fas fa-eye mr-1"></i>View Details
                        </button>
                        ${task.status === 'pending' ? `
                            <button class="btn btn-sm btn-success resolve-complaint-btn" data-index="${index}" data-title="${task.title}">
                                <i class="fas fa-check-circle mr-1"></i>Resolve
                            </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;

        taskList.appendChild(card);
    });

    // Add event listeners for view buttons
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            const task = filtered[index];
            if (task) {
                showViewModal(task, tasks.indexOf(task));
            }
        });
    });

    // Add event listeners for resolve buttons
    document.querySelectorAll('.resolve-complaint-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            const title = this.getAttribute('data-title');
            const task = filtered[index];
            currentTaskIndex = tasks.indexOf(task);
            
            // Update confirmation modal message
            document.querySelector('#resolveModal .modal-body p').innerHTML = 
                'Are you sure you want to mark this complaint as resolved? This action confirms that the issue has been addressed. <strong>' + title + '</strong>';
            
            // Update confirmation action
            const confirmBtn = document.querySelector('#resolveModal .btn-success');
            if (confirmBtn) {
                confirmBtn.onclick = function() {
                    resolveComplaint();
                    closeConfirmationModal('resolveModal');
                };
            }
            
            showConfirmationModal('resolveModal');
        });
    });
}

function showViewModal(task, taskIndex) {
    currentTaskIndex = taskIndex;
    
    document.getElementById('modalTitle').textContent = task.title;
    document.getElementById('modalUser').textContent = task.customer;
    document.getElementById('modalAddress').textContent = task.address;
    document.getElementById('modalDate').textContent = task.date;
    document.getElementById('modalNotes').textContent = task.notes;
    
    const statusBadge = document.getElementById('modalStatusBadge');
    if (task.status === 'pending') {
        statusBadge.className = 'badge bg-warning text-dark mt-2';
        statusBadge.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i>Pending';
    } else {
        statusBadge.className = 'badge bg-success mt-2';
        statusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Resolved';
    }

    const resolveBtn = document.getElementById('modalResolveBtn');
    if (task.status === 'pending') {
        resolveBtn.style.display = 'inline-block';
        resolveBtn.onclick = function() {
            closeViewModal();
            
            // Update confirmation modal
            document.querySelector('#resolveModal .modal-body p').innerHTML = 
                'Are you sure you want to mark this complaint as resolved? This action confirms that the issue has been addressed. <strong>' + task.title + '</strong>';
            
            // Update confirmation action
            const confirmBtn = document.querySelector('#resolveModal .btn-success');
            if (confirmBtn) {
                confirmBtn.onclick = function() {
                    resolveComplaint();
                    closeConfirmationModal('resolveModal');
                };
            }
            
            showConfirmationModal('resolveModal');
        };
    } else {
        resolveBtn.style.display = 'none';
    }

    viewModal.classList.add('show');
    viewModal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeViewModal() {
    viewModal.classList.remove('show');
    viewModal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function resolveComplaint() {
    if (currentTaskIndex !== null) {
        tasks[currentTaskIndex].status = "done";
        renderTasks();
        currentTaskIndex = null;
    }
}

// Event listeners
searchBar.addEventListener("input", renderTasks);
statusFilter.addEventListener("change", renderTasks);

// Close modal when clicking overlay
document.querySelector('#viewModal .modal-overlay')?.addEventListener('click', closeViewModal);

// Initial render
renderTasks();
</script>
