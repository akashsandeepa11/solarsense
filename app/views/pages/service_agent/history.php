<?php
// Build a unified JS-friendly array. Type determined by null check on task_id / order_id.
$rawHistory = $data['history'] ?? [];
$records = [];
foreach ($rawHistory as $r) {
    $isInstall = ($r->task_id === null || $r->task_id === '') && $r->order_id !== null;

    if ($isInstall) {
        $itemCount = (int)($r->item_count ?? 0);
        $records[] = [
            'type'          => 'installation',
            'title'         => 'Order #' . $r->linked_order_id . ' — Installation',
            'customer'      => $r->order_customer ?? 'N/A',
            'address'       => '—',
            'date'          => $r->order_date ? date('Y-m-d', strtotime($r->order_date)) : '—',
            'completedDate' => $r->completion_date ? date('Y-m-d', strtotime($r->completion_date)) : '—',
            'notes'         => $r->actions_taken ?? '',
            'techNotes'     => $r->technician_notes ?? '',
            'finalStatus'   => $r->final_status ?? '—',
            'extra'         => $itemCount . ' item' . ($itemCount !== 1 ? 's' : '') . ' · LKR ' . number_format($r->total_amount ?? 0, 2),
            'timeSpent'     => $r->time_spent ?? '—',
        ];
    } else {
        $records[] = [
            'type'          => 'task',
            'title'         => $r->task_title ?? 'Service Task',
            'customer'      => $r->task_customer ?? 'N/A',
            'address'       => $r->task_address ?? '—',
            'date'          => $r->task_date ? date('Y-m-d', strtotime($r->task_date)) : '—',
            'completedDate' => $r->completion_date ? date('Y-m-d', strtotime($r->completion_date)) : '—',
            'notes'         => $r->actions_taken ?? '',
            'techNotes'     => $r->technician_notes ?? '',
            'finalStatus'   => $r->final_status ?? '—',
            'extra'         => '',
            'timeSpent'     => $r->time_spent ?? '—',
        ];
    }
}

$taskCount    = count(array_filter($records, fn($r) => $r['type'] === 'task'));
$installCount = count(array_filter($records, fn($r) => $r['type'] === 'installation'));
?>

<div class="content-area" style="padding: 1.5rem;">
    <!-- Page Header -->
    <?php
    $config = [
        'title'       => 'History',
        'description' => 'Your completed service tasks and installation jobs',
        'buttons'     => [
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

    <!-- Summary Cards -->
    <?php
    $config = [
        'stats' => [
            ['label' => 'Service Tasks',   'value' => $taskCount,    'icon' => 'fas fa-check-circle', 'color' => 'success'],
            ['label' => 'Installations',   'value' => $installCount, 'icon' => 'fas fa-solar-panel',  'color' => 'primary'],
        ]
    ];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <!-- Filter Bar -->
    <?php
    $config = [
        'search' => [
            'id'          => 'searchBar',
            'name'        => 'search',
            'label'       => 'Search History',
            'placeholder' => 'Search by title or customer...'
        ],
        'filters' => [
            [
                'id'      => 'typeFilter',
                'name'    => 'type',
                'label'   => 'Type',
                'options' => [
                    ['value' => 'all',          'label' => 'All'],
                    ['value' => 'task',         'label' => 'Service Tasks'],
                    ['value' => 'installation', 'label' => 'Installations'],
                ]
            ]
        ],
        'buttons'         => [],
        'form_action'     => '',
        'form_method'     => 'GET',
        'auto_submit'     => false,
        'reset_on_clear'  => false,
        'result_count'    => true,
        'result_count_id' => 'resultCount'
    ];
    include __DIR__ . '/../../inc/components/filter_bar.php';
    ?>

    <!-- History List -->
    <div id="taskList"></div>

    <!-- Empty State -->
    <div id="emptyState" class="card shadow-sm rounded-xl" style="display: none;">
        <div class="card-body text-center" style="padding: 3rem;">
            <i class="fas fa-history text-secondary" style="font-size: 4rem; opacity: 0.3;"></i>
            <h4 class="mt-4 mb-2">No History Found</h4>
            <p class="text-secondary mb-0">No completed tasks or installations match your search.</p>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="taskModal" class="custom-modal" style="display: none;">
    <div class="modal-overlay" onclick="closeTaskModal()"></div>
    <div class="modal-dialog" style="max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i id="modalIcon" class="fas fa-clipboard-check text-success mr-2"></i>
                    <span id="modalHeaderText">Completed Details</span>
                </h5>
                <button type="button" class="btn-close" onclick="closeTaskModal()">
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
                        <label class="text-secondary text-sm mb-1">Customer</label>
                        <p class="mb-0 font-semibold" id="modalCustomer"></p>
                    </div>
                    <div class="col-md-6" id="modalFinalStatusRow">
                        <label class="text-secondary text-sm mb-1">Final Status</label>
                        <p class="mb-0 font-semibold" id="modalFinalStatus"></p>
                    </div>
                    <!-- Task-only -->
                    <div class="col-12" id="modalAddressRow">
                        <label class="text-secondary text-sm mb-1">Address</label>
                        <p class="mb-0 font-semibold" id="modalAddress"></p>
                    </div>
                    <!-- Installation-only -->
                    <div class="col-md-6" id="modalExtraRow" style="display:none;">
                        <label class="text-secondary text-sm mb-1">Order Summary</label>
                        <p class="mb-0 font-semibold" id="modalExtra"></p>
                    </div>
                    <div class="col-12">
                        <label class="text-secondary text-sm mb-1">Steps / Actions Taken</label>
                        <p class="mb-0 font-semibold" id="modalNotes"></p>
                    </div>
                    <div class="col-12" id="modalTechNotesRow">
                        <label class="text-secondary text-sm mb-1">Technician Notes</label>
                        <p class="mb-0 font-semibold" id="modalTechNotes"></p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-secondary text-sm mb-1">Date Assigned</label>
                        <p class="mb-0 font-semibold" id="modalDate"></p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-secondary text-sm mb-1">Date Completed</label>
                        <p class="mb-0 font-semibold" id="modalCompletedDate"></p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-secondary text-sm mb-1">Time Spent (hrs)</label>
                        <p class="mb-0 font-semibold" id="modalTimeSpent"></p>
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
.task-card { background:white; padding:1.5rem; border-radius:.75rem; box-shadow:0 1px 3px rgba(0,0,0,.1); margin-bottom:1rem; transition:all .3s ease; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
.task-card:hover { box-shadow:0 4px 6px rgba(0,0,0,.1); transform:translateY(-2px); }
.task-card.type-task         {}
.task-card.type-installation {}
.task-info { flex:1; min-width:300px; }
.task-info h3 { margin:0 0 .5rem; font-size:1.125rem; font-weight:600; color:#212121; }
.task-info p  { margin:.25rem 0; font-size:.875rem; color:#6b7280; }
.task-info p i { width:20px; color:#9ca3af; }
.task-actions { display:flex; gap:.5rem; align-items:center; flex-wrap:wrap; }
.badge { padding:.25rem .75rem; border-radius:.375rem; font-size:.75rem; font-weight:600; white-space:nowrap; }
.badge.status-done { background-color:#dcfce7; color:#166534; }
.custom-modal { position:fixed; top:0; left:0; right:0; bottom:0; z-index:9999; display:none; align-items:center; justify-content:center; }
.custom-modal.show { display:flex; }
.modal-overlay { position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,.5); }
.modal-dialog  { position:relative; z-index:10000; width:100%; max-width:600px; margin:1rem; }
.modal-content { background:white; border-radius:.75rem; box-shadow:0 10px 25px rgba(0,0,0,.2); animation:slideIn .3s ease-out; }
@keyframes slideIn { from{transform:scale(.9);opacity:0}to{transform:scale(1);opacity:1} }
.modal-header,.modal-body,.modal-footer { padding:1.5rem; }
.modal-header { border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center; }
.modal-footer { border-top:1px solid #e5e7eb; }
.btn-close { background:none; border:none; font-size:1.5rem; color:#6b7280; cursor:pointer; padding:0; width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:.375rem; transition:all .2s; }
.btn-close:hover { background:#f3f4f6; color:#212121; }
@media(max-width:768px) {
    .task-card { flex-direction:column; align-items:flex-start; }
    .task-info { min-width:100%; }
    .task-actions { width:100%; justify-content:flex-start; }
    .modal-dialog { margin:.5rem; }
    .modal-header,.modal-body,.modal-footer { padding:1rem; }
}
</style>

<script>
const records   = <?php echo json_encode($records, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
const taskList  = document.getElementById('taskList');
const searchBar = document.getElementById('searchBar');
const typeFilter= document.getElementById('typeFilter');
const emptyState= document.getElementById('emptyState');

function renderTasks() {
    taskList.innerHTML = '';
    const q  = searchBar.value.toLowerCase();
    const tf = typeFilter ? typeFilter.value : 'all';

    const filtered = records.filter(r =>
        (r.title.toLowerCase().includes(q) || r.customer.toLowerCase().includes(q)) &&
        (tf === 'all' || r.type === tf)
    );

    const rc = document.getElementById('resultCount');
    if (rc) rc.textContent = filtered.length;

    emptyState.style.display = filtered.length === 0 ? 'block' : 'none';
    if (filtered.length === 0) return;

    filtered.forEach(r => {
        const card = document.createElement('div');
        card.className = `task-card type-${r.type}`;

        const extraLine = r.type === 'installation' && r.extra
            ? `<p><i class="fas fa-boxes-stacked"></i> ${r.extra}</p>` : '';
        const addrLine  = r.type === 'task'
            ? `<p><i class="fas fa-map-marker-alt"></i> ${r.address}</p>` : '';

        card.innerHTML = `
            <div class="task-info">
                <h3>${r.title}</h3>
                <p><i class="fas fa-user"></i> ${r.customer}</p>
                ${addrLine}
                ${extraLine}
                <p><i class="fas fa-calendar-check"></i> Completed: ${r.completedDate}</p>
            </div>
            <div class="task-actions">
                <span class="badge status-done">Completed</span>
                <button class="btn btn-sm btn-info view-btn"><i class="fas fa-eye mr-1"></i>View</button>
            </div>
        `;
        card.querySelector('.view-btn').addEventListener('click', () => openModal(r));
        taskList.appendChild(card);
    });
}

function openModal(r) {
    const isInstall = r.type === 'installation';
    document.getElementById('modalIcon').className     = isInstall
        ? 'fas fa-solar-panel text-primary mr-2'
        : 'fas fa-clipboard-check text-success mr-2';
    document.getElementById('modalHeaderText').textContent = isInstall ? 'Installation Details' : 'Service Task Details';

    document.getElementById('modalTitle').textContent        = r.title;
    document.getElementById('modalCustomer').textContent     = r.customer;
    document.getElementById('modalDate').textContent         = r.date;
    document.getElementById('modalCompletedDate').textContent= r.completedDate;
    document.getElementById('modalNotes').textContent        = r.notes || '(none)';
    document.getElementById('modalFinalStatus').textContent  = r.finalStatus;
    document.getElementById('modalTechNotes').textContent    = r.techNotes || '(none)';
    document.getElementById('modalTimeSpent').textContent    = r.timeSpent;

    document.getElementById('modalAddressRow').style.display = isInstall ? 'none' : '';
    document.getElementById('modalAddress').textContent      = r.address;

    document.getElementById('modalExtraRow').style.display   = isInstall ? '' : 'none';
    document.getElementById('modalExtra').textContent        = r.extra || '—';

    const modal = document.getElementById('taskModal');
    modal.style.display = 'flex';
    modal.classList.add('show');
}

function closeTaskModal() {
    const modal = document.getElementById('taskModal');
    modal.classList.remove('show');
    modal.style.display = 'none';
}

searchBar.addEventListener('input', renderTasks);
if (typeFilter) typeFilter.addEventListener('change', renderTasks);
renderTasks();

// ── PDF Export ────────────────────────────────────────────────
function buildHistoryTable() {
    const tbl = document.getElementById('historyExportTable');
    if (!tbl) return;
    const tbody = tbl.querySelector('tbody');
    tbody.innerHTML = '';
    const q  = searchBar.value.toLowerCase();
    const tf = typeFilter ? typeFilter.value : 'all';
    records.filter(r =>
        (r.title.toLowerCase().includes(q) || r.customer.toLowerCase().includes(q)) &&
        (tf === 'all' || r.type === tf)
    ).forEach(r => {
        const tr = document.createElement('tr');
        tr.innerHTML =
            '<td>' + r.title         + '</td>' +
            '<td>' + (r.type === 'installation' ? 'Installation' : 'Service Task') + '</td>' +
            '<td>' + r.customer      + '</td>' +
            '<td>' + r.completedDate + '</td>' +
            '<td>Completed</td>';
        tbody.appendChild(tr);
    });
}
buildHistoryTable();

function downloadHistoryReport(btnEl) {
    buildHistoryTable();
    SolarSenseReport.download({
        tableSelector: '#historyExportTable',
        title:         'Service History Report',
        subtitle:      'Completed service tasks and installation jobs',
        columns:       ['Title', 'Type', 'Customer', 'Completed Date', 'Status']
    }, btnEl);
}
</script>

<!-- Hidden PDF export table -->
<table id="historyExportTable" style="display:none;" aria-hidden="true">
    <thead>
        <tr><th>Title</th><th>Type</th><th>Customer</th><th>Completed Date</th><th>Status</th></tr>
    </thead>
    <tbody></tbody>
</table>

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>