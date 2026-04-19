<?php
// --- PHP Setup for Dummy Data ---
$stats = $data['stats'];

// Summary Card Data
$summary_cards = [
    ['label' => 'Total Clients', 'value' => $stats['total_clients'], 'icon' => 'fas fa-users', 'color' => 'primary'],
    ['label' => 'Systems with Active Faults', 'value' => '0', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'error'],
    ['label' => 'Pending Maintenance', 'value' => $stats['pending_maintenace'], 'icon' => 'fas fa-wrench', 'color' => 'warning'],
    ['label' => 'Services Completed (Month)', 'value' => $stats['completed_services'], 'icon' => 'fas fa-check-circle', 'color' => 'success']
];

// Get all customers from controller
$clients = isset($data['customers']) ? $data['customers'] : [];

// Function to determine the status dot color
function getStatusClass($health)
{
    switch ($health) {
        case 'Healthy':
            return 'bg-success';
        case 'Underperforming':
            return 'bg-warning';
        case 'Fault':
            return 'bg-error';
        default:
            return 'bg-secondary';
    }
}
?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/components.css">
<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/pages/installer/dashboard.css">

<style>
    /* Status Dot */
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

    .status-dot.bg-error {
        background-color: #ef4444 !important;
    }

    .status-dot.bg-secondary {
        background-color: #9ca3af !important;
    }

    /* Agent Details */
    .agent-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        min-width: 0;
    }

    .agent-name {
        font-size: 0.95rem;
        color: #212121;
        font-weight: 600;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .agent-role {
        font-size: 0.85rem;
        color: #6b7280;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .agent-name {
            font-size: 0.85rem;
        }

        .agent-role {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .agent-name {
            font-size: 0.8rem;
        }

        .agent-role {
            font-size: 0.7rem;
        }
    }
</style>

<div class="container-fluid p-8">

    <!-- Page Header -->
    <?php
    $buttons = [];
    if ($data['user']['role'] === ROLE_INSTALLER_ADMIN) {
        $buttons[] = [
            'label'   => 'Add Customer',
            'url'     => URLROOT . '/installeradmin/fleet/add_customer',
            'icon'    => 'fas fa-plus',
            'class'   => 'btn-primary btn-md'
        ];
    }
    $buttons[] = [
        'label'   => 'Download PDF',
        'icon'    => 'fas fa-file-pdf',
        'class'   => 'btn-outline-primary btn-md',
        'onclick' => 'onclick="SolarSenseReport.download({tableSelector:\'.data-table\',title:\'Fleet Dashboard Report\',subtitle:\'Client systems overview\',columns:[\'Client Name\',\'Location\',\'System Size\',\'Health\',\'Performance\',\'Last SMS Upload\']},this)"'
    ];

    $config = [
        'title'       => 'Fleet Dashboard',
        'description' => 'Overview of your client systems.',
        'buttons'     => $buttons
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>


    <!-- Filter & Search Section -->
    <?php
    $config = [
        'search' => [
            'id' => 'searchClients',
            'name' => 'search',
            'label' => 'Search Clients',
            'placeholder' => 'Search by name or location...'
        ],
        'filters' => [
            [
                'id' => 'filterHealth',
                'name' => 'health',
                'label' => 'System Health',
                'options' => [
                    ['value' => '', 'label' => 'All Health Status'],
                    ['value' => 'healthy', 'label' => 'Healthy'],
                    ['value' => 'underperforming', 'label' => 'Underperforming'],
                    ['value' => 'fault', 'label' => 'Fault']
                ]
            ],
            [
                'id' => 'filterSize',
                'name' => 'size',
                'label' => 'System Size',
                'options' => [
                    ['value' => '', 'label' => 'All Sizes'],
                    ['value' => 'small', 'label' => 'Small (< 5kW)'],
                    ['value' => 'medium', 'label' => 'Medium (5-10kW)'],
                    ['value' => 'large', 'label' => 'Large (> 10kW)']
                ]
            ]
        ],
        'buttons' => [],
        'form_action' => URLROOT . '/installeradmin/fleet',
        'form_method' => 'GET',
        'auto_submit' => true,
        'reset_on_clear' => true
    ];
    include __DIR__ . '/../../inc/components/filter_bar.php';
    ?>

    <!-- Summary Cards -->
    <?php
    $config = [
        'stats' => $summary_cards,
        'columns' => 6
    ];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <!-- Clients Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Location</th>
                            <th>System Size</th>
                            <th>System Health</th>
                            <th>Performance</th>
                            <th>Last SMS Upload</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($clients)): ?>
                            <tr><td colspan="7" class="text-center p-6 text-secondary">No clients available</td></tr>
                        <?php else: ?>
                            <?php foreach ($clients as $row): ?>
                                <tr class="data-table-row">
                                    <td>
                                        <div class="d-flex align-center gap-3">
                                            <img src="<?php echo getAvatarUrl($row['name']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                            <div class="agent-details">
                                                <div class="agent-name font-semibold"><?php echo htmlspecialchars($row['name']); ?></div>
                                                <div class="agent-role text-secondary text-sm"><?php echo htmlspecialchars($row['location']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                                    <td><?php echo htmlspecialchars($row['size']); ?> kWp</td>
                                    <td>
                                        <div class="d-flex align-center">
                                            <span class="status-dot <?php echo getStatusClass($row['health']); ?> mr-2"></span>
                                            <?php echo htmlspecialchars($row['health']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['performance']); ?>%</td>
                                    <td><?php echo htmlspecialchars($row['last_upload'] ?? '—'); ?></td>
                                    <td>
                                        <div class="actions-menu d-flex gap-2 justify-center">
                                            <a href="<?php echo URLROOT; ?>/<?php echo $rolePath; ?>/fleet/customer_details/<?php echo $row['id']; ?>" class="btn-icon btn-sm btn-info" title="View Details"><i class="fas fa-eye"></i></a>
                                            <?php if ($data['user']['role'] === ROLE_INSTALLER_ADMIN): ?>
                                            <a href="<?php echo URLROOT; ?>/installeradmin/fleet/edit_customer/<?php echo $row['id']; ?>" class="btn-icon btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                            <button class="btn-icon btn-icon-danger" title="Remove" onclick="openDeleteModal(<?php echo (int)$row['id']; ?>)"><i class="fas fa-trash"></i></button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <?php
    $config = [
        'modal_id' => 'deletefleetModal',
        'title' => 'Confirm Delete',
        'icon' => 'fas fa-exclamation-triangle',
        'icon_color' => 'text-warning',
        'heading' => 'Delete Service Customer?',
        'message' => 'Are you sure you want to delete this customer? This action cannot be undone. All associated task data will be archived.',
        'confirm_text' => 'Delete Customer',
        'confirm_icon' => 'fas fa-check',
        'cancel_text' => 'Cancel',
        'cancel_icon' => 'fas fa-times',
        'confirm_action' => URLROOT . '/installeradmin/fleet/delete_customer/',
        'confirm_method' => 'POST',
        'confirm_class' => 'btn-danger'
    ];
    include __DIR__ . '/../../inc/models/confirmation_modal.php';
    ?>

    <!-- Dynamic Delete Modal Handler -->
    <script>
        function openDeleteModal(customerId) {
            const modal = document.getElementById('deletefleetModal');
            const form = modal.querySelector('form');
            if (form) {
                form.action = '<?php echo URLROOT; ?>/installeradmin/fleet/delete_customer/' + customerId;
            }
            showConfirmationModal('deletefleetModal');
        }
    </script>

    <?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>
</div>