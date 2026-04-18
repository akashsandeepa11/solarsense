<?php
// Get completed tasks from the model
$rawTasks = $data['history'] ?? [];

// Map DB objects to JS-friendly array
$tasks = [];
foreach ($rawTasks as $t) {
    $tasks[] = [
        "title" => $t->title,
        "customer" => $t->customer ?? 'N/A',
        "address" => $t->address,
        "date" => $t->date,
        "completedDate" => $t->date, // Use actual completed date if available
        "notes" => $t->notes,
        "status" => "done"
    ];
}
?>

<div class="content-area" style="padding: 1.5rem;">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Task History',
        'description' => 'View your completed service tasks',
        'buttons' => [
            [
                'label'   => 'Download PDF',
                'url'     => 'javascript:void(0)',
                'icon'    => 'fas fa-file-pdf',
                'class'   => 'btn-outline-primary btn-md',
                'onclick' => 'onclick="downloadHistoryReport(this)"'
            ]
        ]
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
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-check text-success mr-2"></i>Completed Task Details
                </h5>
                <button type="button" class="btn-close" onclick="closeTaskModal()" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <h4 class="mb-2 font-bold" id="modalTitle"></h4>
                        <span class="badge status-done">Completed</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Customer Name</label>
                        <p class="mb-0 font-semibold" id="modalCustomer"></p>
                    </div>
                    <div class="col-12">
                        <label class="text-secondary text-sm mb-1">Address</label>
                        <p class="mb-0 font-semibold" id="modalAddress"></p>
                    </div>

                    <div class="col-12">
                        <label class="text-secondary text-sm mb-1">Service Notes</label>
                        <p class="mb-0 font-semibold" id="modalNotes"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Date Assigned</label>
                        <p class="mb-0 font-semibold" id="modalDate"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Date Completed</label>
                        <p class="mb-0 font-semibold" id="modalCompletedDate"></p>
                    </div>
                  
                </div>
            </div>

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
    transition: all 0.3s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}
.task-card:hover {
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.task-info { flex: 1; min-width: 300px; }
.task-info h3 { margin:0 0 0.5rem; font-size:1.125rem; font-weight:600; color:#212121; }
.task-info p { margin:0.25rem 0; font-size:0.875rem; color:#6b7280; }
.task-info p i { width:20px; color:#9ca3af; }
.task-actions { display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap; }
.badge { padding:0.25rem 0.75rem; border-radius:0.375rem; font-size:0.75rem; font-weight:600; white-space:nowrap; }
.badge.status-done { background-color:#dcfce7; color:#166534; }

/* Modal Styling */
.custom-modal { position: fixed; top:0; left:0; right:0; bottom:0; z-index:9999; display:none; align-items:center; justify-content:center; }
.custom-modal.show { display:flex; }
.modal-overlay { position:absolute; top:0; left:0; right:0; bottom:0; background-color:rgba(0,0,0,0.5); }
.modal-dialog { position:relative; z-index:10000; width:100%; max-width:600px; margin:1rem; }
.modal-content { background:white; border-radius:0.75rem; box-shadow:0 10px 25px rgba(0,0,0,0.2); animation:slideIn 0.3s ease-out; }
@keyframes slideIn { from { transform: scale(0.9); opacity:0; } to { transform: scale(1); opacity:1; } }
.modal-header, .modal-body, .modal-footer { padding:1.5rem; }
.modal-header { border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center; }
.btn-close { background:none; border:none; font-size:1.5rem; color:#6b7280; cursor:pointer; padding:0; width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:0.375rem; transition:all 0.2s; }
.btn-close:hover { background-color:#f3f4f6; color:#212121; }

/* Responsive */
@media (max-width:768px) {
    .task-card { flex-direction:column; align-items:flex-start; }
    .task-info { min-width:100%; }
    .task-actions { width:100%; justify-content:flex-start; }
    .modal-dialog { margin:0.5rem; }
    .modal-header,.modal-body,.modal-footer { padding:1rem; }
}
</style>

<script>
const tasks = <?php echo json_encode($tasks); ?>;

const taskList = document.getElementById("taskList");
const searchBar = document.getElementById("searchBar");
const emptyState = document.getElementById("emptyState");

// Render tasks
function renderTasks() {
    taskList.innerHTML = "";
    let filteredTasks = tasks.filter(task => task.status === "done");

    // Search filter
    const query = searchBar.value.toLowerCase();
    if(query) {
        filteredTasks = filteredTasks.filter(task => 
            task.title.toLowerCase().includes(query) || 
            task.customer.toLowerCase().includes(query)
        );
    }

    // Update result count
    const resultCount = document.getElementById('resultCount');
    if(resultCount) resultCount.textContent = filteredTasks.length;

    // Show/hide empty state
    emptyState.style.display = filteredTasks.length === 0 ? 'block' : 'none';
    if(filteredTasks.length === 0) return;

    // Render each task
    filteredTasks.forEach(task => {
        const card = document.createElement("div");
        card.className = "task-card";
        card.innerHTML = `
            <div class="task-info">
                <h3>${task.title}</h3>
                <p><i class="fas fa-user"></i> ${task.customer}</p>
                <p><i class="fas fa-map-marker-alt"></i> ${task.address}</p>
                <p><i class="fas fa-calendar-check"></i> Completed: ${task.completedDate}</p>
            </div>
            <div class="task-actions">
                <span class="badge status-done">Completed</span>
                <button class="btn btn-sm btn-info view-btn">
                    <i class="fas fa-eye mr-1"></i>View
                </button>
            </div>
        `;
        card.querySelector(".view-btn").addEventListener("click", () => openTaskModal(task));
        taskList.appendChild(card);
    });
}

// Modal functions
function openTaskModal(task) {
    document.getElementById("modalTitle").textContent = task.title;
    document.getElementById("modalCustomer").textContent = task.customer;
    document.getElementById("modalAddress").textContent = task.address;
    document.getElementById("modalDate").textContent = task.date;
    document.getElementById("modalCompletedDate").textContent = task.completedDate;
    document.getElementById("modalNotes").textContent = task.notes;

    const modal = document.getElementById("taskModal");
    modal.style.display = 'flex';
    modal.classList.add("show");
}

function closeTaskModal() {
    const modal = document.getElementById("taskModal");
    modal.classList.remove("show");
    modal.style.display = 'none';
}

// Close modal on overlay click
window.addEventListener("click", (e) => {
    if (e.target === document.querySelector(".modal-overlay")) closeTaskModal();
});

// Search input
searchBar.addEventListener("input", renderTasks);

// Initial render
renderTasks();

// ── Hidden table for PDF export ─────────────────────────────
function buildHistoryTable() {
    var tbl = document.getElementById('historyExportTable');
    if (!tbl) return;
    var tbody = tbl.querySelector('tbody');
    tbody.innerHTML = '';
    var filtered = tasks.filter(function(t) { return t.status === 'done'; });
    filtered.forEach(function(task) {
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td>' + (task.title        || '') + '</td>' +
            '<td>' + (task.customer     || '') + '</td>' +
            '<td>' + (task.address      || '') + '</td>' +
            '<td>' + (task.completedDate|| '') + '</td>' +
            '<td>Completed</td>';
        tbody.appendChild(tr);
    });
}
buildHistoryTable();

function downloadHistoryReport(btnEl) {
    buildHistoryTable(); // rebuild with current filter if needed
    SolarSenseReport.download({
        tableSelector: '#historyExportTable',
        title:         'Task History Report',
        subtitle:      'A complete record of completed service tasks',
        columns:       ['Task Title', 'Customer', 'Address', 'Completed Date', 'Status']
    }, btnEl);
}
</script>

<!-- Hidden export table (never visible to users) -->
<table id="historyExportTable" style="display:none;" aria-hidden="true">
    <thead>
        <tr>
            <th>Task Title</th>
            <th>Customer</th>
            <th>Address</th>
            <th>Completed Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>