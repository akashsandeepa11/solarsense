<?php
// Retrieve real data from SuperAdmin controller
$tasks = $data['complaints'] ?? [];

// Calculate Statistics
$pending = count(array_filter($tasks, fn($t) => (is_object($t) ? $t->status : $t['status']) === 'Pending'));
$resolved = count(array_filter($tasks, fn($t) => (is_object($t) ? $t->status : $t['status']) === 'Resolved'));

$summary_cards = [
    ['label' => 'Pending Tickets', 'value' => $pending, 'icon' => 'fas fa-exclamation-circle', 'color' => 'warning'],
    ['label' => 'Resolved Tickets', 'value' => $resolved, 'icon' => 'fas fa-check-circle', 'color' => 'success'],
    ['label' => 'Total Requests', 'value' => count($tasks), 'icon' => 'fas fa-headset', 'color' => 'primary'],
];

// Helper for status dot class
function getComplaintStatusClass($status)
{
    return (strtolower($status) === 'pending') ? 'bg-warning' : 'bg-success';
}
?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/components.css">
<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/pages/installer/dashboard.css">

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

    /* User Details Layout (reusing company style from verifications) */
    .user-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        min-width: 0;
    }

    .user-name {
        font-size: 0.95rem;
        color: #212121;
        font-weight: 600;
    }

    .font-semibold {
        font-weight: 600;
    }
</style>

<div class="container-fluid p-8">
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
                    ['value' => '', 'label' => 'All Status'],
                    ['value' => 'Pending', 'label' => 'Pending'],
                    ['value' => 'Resolved', 'label' => 'Resolved']
                ]
            ]
        ],
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
            ['key' => 'customer', 'label' => 'Submitted By'],
            ['key' => 'title', 'label' => 'Subject'],
            ['key' => 'date', 'label' => 'Date Received'],
            ['key' => 'status', 'label' => 'Status']
        ],
        'rows' => $tasks,
        'columns' => [
            [
                'key' => 'complaint_id',
                'render' => function ($row) {
                    $id = is_object($row) ? $row->complaint_id : $row['complaint_id'];
                    return '<span class="font-semibold">#' . $id . '</span>';
                }
            ],
            [
                'key' => 'customer',
                'render' => function ($row) {
                    $name = is_object($row) ? $row->customer : $row['customer'];
                    $role = is_object($row) ? $row->user_type : $row['user_type'];
                    return '<div class="d-flex align-center gap-3">
                                <img src="' . getAvatarUrl($name) . '" alt="' . htmlspecialchars($name) . '">
                                <div class="user-details">
                                    <div class="user-name">' . htmlspecialchars($name) . '</div>
                                    <div class="text-secondary text-xs">' . htmlspecialchars($role) . '</div>
                                </div>
                            </div>';
                }
            ],
            [
                'key' => 'status',
                'render' => function ($row) {
                    $status = is_object($row) ? $row->status : $row['status'];
                    return '<div class="d-flex align-center">
                                <span class="status-dot ' . getComplaintStatusClass($status) . ' mr-2"></span>
                                <span class="badge ' . (strtolower($status) === 'pending' ? 'badge-warning' : 'badge-success') . '">' . ucfirst($status) . '</span>
                            </div>';
                }
            ]
        ],
        'actions' => [
            [
                'label' => 'View Details',
                'icon' => 'fas fa-eye',
                'class' => 'btn-sm btn-info',
                'onclick' => 'onclick="openViewModal({complaint_id})"'
            ],
            [
                'label' => 'Resolve',
                'icon' => 'fas fa-check-circle',
                'class' => 'btn-sm btn-success',
                'onclick' => 'onclick="openResolveModal({complaint_id})"',
                'condition' => function ($row) {
                    $status = is_object($row) ? $row->status : $row['status'];
                    return strtolower($status) === 'pending';
                }
            ]
        ],
        'empty_message' => 'No support requests found.'
    ];
    include __DIR__ . '/../../inc/components/data_table.php';
    ?>
</div>
<div id="viewModal" class="custom-modal" style="display: none;">
    <div class="modal-overlay" onclick="closeViewModal()"></div>
    <div class="modal-dialog" style="max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-ticket-alt text-primary mr-2"></i>Support Ticket Details
                </h5>
                <button type="button" class="btn-close" onclick="closeViewModal()" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <h4 class="mb-0 font-bold" id="modalSubject"></h4>
                        <span id="modalStatusBadge" class="badge mt-2"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Ticket ID</label>
                        <p class="mb-0 font-semibold" id="modalTicketId"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Date Received</label>
                        <p class="mb-0 font-semibold" id="modalDate"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Submitted By</label>
                        <p class="mb-0 font-semibold" id="modalUsername"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">User Role</label>
                        <p class="mb-0 font-semibold" id="modalUserRole"></p>
                    </div>
                    <div class="col-12 border-top pt-3">
                        <label class="text-secondary text-sm mb-1">Issue Description</label>
                        <div class="p-3 bg-light rounded" id="modalDescription" style="white-space: pre-wrap; font-size: 0.9rem;"></div>
                    </div>
                </div>
            </div>

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

<?php
$config = [
    'modal_id' => 'resolveModal',
    'title' => 'Resolve Ticket',
    'icon' => 'fas fa-check-circle',
    'icon_color' => 'text-success',
    'heading' => 'Mark Ticket as Resolved',
    'message' => 'Are you sure you want to mark this support ticket as resolved? This will notify the user that their issue has been addressed.',
    'confirm_text' => 'Resolve Ticket',
    'cancel_text' => 'Cancel',
    'confirm_action' => '', // Set dynamically via JS
    'confirm_method' => 'GET',
    'confirm_class' => 'btn-success',
    'confirm_icon' => 'fas fa-check-circle'
];
include __DIR__ . '/../../inc/models/confirmation_modal.php';
?>

<script>
    // Initialize data from controller
    const tasks = <?php echo json_encode($data['complaints'] ?? []); ?>;

    /**
     * Populate and show the View Details modal
     */
    function showViewModal(index) {
        const task = tasks[index];
        if (!task) return;

        // Helper to handle both objects and arrays
        const getVal = (obj, key) => obj != null && typeof obj === 'object' && !Array.isArray(obj) ? obj[key] : obj[key];
        
        // Map data to modal fields
        document.getElementById('modalSubject').textContent = task.title || 'Support Request';
        document.getElementById('modalTicketId').textContent = '#' + (task.complaint_id || '');
        document.getElementById('modalDate').textContent = task.date || '';
        document.getElementById('modalUsername').textContent = task.customer || '';
        document.getElementById('modalUserRole').textContent = task.user_type || '';
        document.getElementById('modalDescription').textContent = task.notes || '';

        // Status styling
        const statusBadge = document.getElementById('modalStatusBadge');
        const isPending = (task.status || '').toLowerCase() === 'pending';
        statusBadge.className = `badge mt-2 ${isPending ? 'bg-warning text-dark' : 'bg-success'}`;
        statusBadge.innerHTML = `<i class="fas ${isPending ? 'fa-clock' : 'fa-check-circle'} mr-1"></i>${task.status}`;

        // Footer Action Button logic
        const resolveBtn = document.getElementById('modalResolveBtn');
        if (isPending) {
            resolveBtn.style.display = 'inline-block';
            resolveBtn.onclick = function() {
                closeViewModal();
                openResolveModal(task.complaint_id);
            };
        } else {
            resolveBtn.style.display = 'none';
        }

        const modal = document.getElementById('viewModal');
        modal.classList.add('show');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    /**
     * Trigger the Resolve confirmation modal
     */
    function openResolveModal(id) {
        const modal = document.getElementById('resolveModal');
        
        // Update confirmation action URL
        const confirmBtn = modal.querySelector('.btn-success');
        if (confirmBtn) {
            confirmBtn.setAttribute('href', "<?php echo URLROOT; ?>/superadmin/resolve_ticket/" + id);        
        }
        
        showConfirmationModal('resolveModal');
    }

    function closeViewModal() {
        const modal = document.getElementById('viewModal');
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
</script>