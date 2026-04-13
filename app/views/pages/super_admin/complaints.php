<?php
// Retrieve real data from SuperAdmin controller
$tasks = $data['complaints'] ?? [];
?>

<div class="content-area" style="padding: 1.5rem;">
    <?php
    $config = [
        'title' => 'System Support Tickets',
        'description' => 'Review and resolve technical issues reported by platform users.',
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <?php
    $config = [
        'search' => [
            'id' => 'searchBar',
            'name' => 'search',
            'label' => 'Search Requests',
            'placeholder' => 'Search by name or user type...'
        ],
        'filters' => [
            [
                'id' => 'statusFilter',
                'name' => 'status',
                'label' => 'Status Filter',
                'options' => [
                    ['value' => 'all', 'label' => 'All Status'],
                    ['value' => 'Pending', 'label' => 'Pending'],
                    ['value' => 'Resolved', 'label' => 'Resolved']
                ]
            ]
        ],
        'buttons' => [],
        'form_action' => '',
        'form_method' => 'GET',
        'auto_submit' => false,
        'result_count' => true,
        'result_count_id' => 'resultCount'
    ];
    include __DIR__ . '/../../inc/components/filter_bar.php';
    ?>

    <?php
    $summary_cards = [
        ['label' => 'Pending Tickets', 'value' => 0, 'icon' => 'fas fa-exclamation-circle', 'color' => 'warning', 'id' => 'pendingCount'],
        ['label' => 'Resolved Tickets', 'value' => 0, 'icon' => 'fas fa-check-circle', 'color' => 'success', 'id' => 'doneCount'],
        ['label' => 'Total Requests', 'value' => 0, 'icon' => 'fas fa-headset', 'color' => 'primary', 'id' => 'totalCount'],
    ];
    $config = ['stats' => $summary_cards, 'columns' => 6];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <div id="taskList"></div>

    <div id="emptyState" class="card shadow-sm rounded-xl" style="display: none;">
        <div class="card-body text-center" style="padding: 3rem;">
            <i class="fas fa-inbox text-secondary" style="font-size: 4rem; opacity: 0.3;"></i>
            <h4 class="mt-4 mb-2">No Support Requests Found</h4>
            <p class="text-secondary mb-0">There are no tickets matching your current search or filter.</p>
        </div>
    </div>
</div>

<div id="viewModal" class="custom-modal" style="display: none;">
    <div class="modal-overlay" onclick="closeViewModal()"></div>
    <div class="modal-dialog" style="max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-ticket-alt text-primary mr-2"></i>Support Ticket Details
                </h5>
                <button type="button" class="btn-close" onclick="closeViewModal()"><i class="fas fa-times"></i></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <h4 class="mb-0 font-bold" id="modalTitle"></h4>
                        <span id="modalStatusBadge" class="badge mt-2"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Submitted By</label>
                        <p class="mb-0 font-semibold" id="modalUsername"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Date Received</label>
                        <p class="mb-0 font-semibold" id="modalDate"></p>
                    </div>
                    <div class="col-12">
                        <label class="text-secondary text-sm mb-1">User Role</label>
                        <p class="mb-0 font-semibold" id="modalUser"></p>
                    </div>
                    <div class="col-12 border-top pt-3">
                        <label class="text-secondary text-sm mb-1">Issue Description</label>
                        <div class="p-3 bg-light rounded" id="modalNotes" style="white-space: pre-wrap;"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" onclick="closeViewModal()">Close</button>
                <button type="button" id="modalResolveBtn" class="btn btn-sm btn-success" style="display: none;">
                    <i class="fas fa-check-circle mr-2"></i>Mark as Resolved
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Initialize data and elements
    const tasks = <?php echo json_encode($tasks); ?>;
    const taskList = document.getElementById("taskList");
    const searchBar = document.getElementById("searchBar");
    const statusFilter = document.getElementById("statusFilter");
    const emptyState = document.getElementById("emptyState");
    const viewModal = document.getElementById("viewModal"); // Added missing declaration

    let currentTaskIndex = null;

    // 2. Count management
    function updateCounts(filtered) {
        const pending = tasks.filter(t => (t.status || '').toLowerCase() === 'pending').length;
        const resolved = tasks.filter(t => (t.status || '').toLowerCase() === 'resolved').length;

        document.getElementById('pendingCount').textContent = pending;
        document.getElementById('doneCount').textContent = resolved;
        document.getElementById('totalCount').textContent = tasks.length;
        document.getElementById('resultCount').textContent = filtered.length;
    }

    // 3. Render cards with correct DB aliases
    function renderTasks() {
        taskList.innerHTML = "";
        const search = (searchBar.value || "").toLowerCase();
        const filter = (statusFilter.value || "all").toLowerCase();

        const filtered = tasks.filter(task => {
            const matchesSearch = (task.customer || '').toLowerCase().includes(search) ||
                (task.user_type || '').toLowerCase().includes(search);
            const taskStatus = (task.status || '').toLowerCase();
            const matchesFilter = filter === "all" || taskStatus === filter;
            return matchesSearch && matchesFilter;
        });

        updateCounts(filtered);

        if (filtered.length === 0) {
            emptyState.style.display = 'block';
            return;
        } else {
            emptyState.style.display = 'none';
        }

        filtered.forEach((task) => {
            const card = document.createElement("div");
            card.className = "complaint-card";

            const isPending = (task.status || '').toLowerCase() === 'pending';
            const statusClass = isPending ? 'bg-warning text-dark' : 'bg-success';
            const iconClass = isPending ? 'fa-clock' : 'fa-check-circle';

            card.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-start gap-3">
                        <div class="stat-icon ${isPending ? 'bg-warning' : 'bg-success'}" style="width: 50px; height: 50px;">
                            <i class="fas ${iconClass}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1 font-bold">Support Request: ${task.user_type}</h5>
                            <div class="text-secondary text-sm">
                                <i class="fas fa-user mr-1"></i><strong>${task.customer}</strong>
                                <span class="mx-2">|</span>
                                <i class="fas fa-id-badge mr-1"></i>${task.user_type}
                                <span class="mx-2">|</span>
                                <i class="fas fa-calendar mr-1"></i>${task.date}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex gap-2 justify-content-end align-items-center">
                        <span class="badge ${statusClass} p-2 px-3" style="font-size: 0.85rem;">
                            <i class="fas ${iconClass} mr-1"></i>${task.status}
                        </span>
                        <button class="btn btn-sm btn-primary py-2" onclick="showViewModal(${tasks.indexOf(task)})">
                            <i class="fas fa-eye mr-1"></i>View Details
                        </button>
                    </div>
                </div>
            </div>
        `;
            taskList.appendChild(card);
        });
    }

    // 4. Modal handling
    function showViewModal(index) {
        const task = tasks[index];
        if (!task) return;
        currentTaskIndex = index;

        // Map modal IDs to M_Help.php aliases
        document.getElementById('modalTitle').textContent = "Ticket #" + task.complaint_id;
        document.getElementById('modalUsername').textContent = task.customer; // alias for full_name
        document.getElementById('modalUser').textContent = task.user_type; // alias for type
        document.getElementById('modalDate').textContent = task.date; // alias for received_at
        document.getElementById('modalNotes').textContent = task.notes; // alias for description

        const statusBadge = document.getElementById('modalStatusBadge');
        const isPending = (task.status || '').toLowerCase() === 'pending';
        statusBadge.className = `badge mt-2 ${isPending ? 'bg-warning text-dark' : 'bg-success'}`;
        statusBadge.innerHTML = `<i class="fas ${isPending ? 'fa-clock' : 'fa-check-circle'} mr-1"></i>${task.status}`;

        // Show button only for pending
        const resolveBtn = document.getElementById('modalResolveBtn');
        if (resolveBtn) {
            resolveBtn.style.display = isPending ? 'inline-block' : 'none';
            resolveBtn.onclick = function () {
                closeViewModal();
                // Optional: call your resolve confirmation modal here
            };
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

    // 5. Global Event Listeners
    searchBar.addEventListener("input", renderTasks);
    statusFilter.addEventListener("change", renderTasks);
    document.querySelector('#viewModal .modal-overlay')?.addEventListener('click', closeViewModal);

    // Initial render
    renderTasks();
</script>