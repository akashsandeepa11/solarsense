<?php
// --- PHP Setup for Data ---
$verifications = $data['verifications'] ?? [];

// Calculate Statistics
$pending = count(array_filter($verifications, fn($r) => (is_object($r) ? $r->status : $r['status']) === 'Pending'));
$verified = count(array_filter($verifications, fn($r) => in_array((is_object($r) ? $r->status : $r['status']), ['Verified', 'verified'])));

$summary_cards = [
    ['label' => 'Pending Verification', 'value' => $pending, 'icon' => 'fas fa-clock', 'color' => 'warning'],
    ['label' => 'Verified Companies', 'value' => $verified, 'icon' => 'fas fa-check-circle', 'color' => 'success'],
    ['label' => 'Total Requests', 'value' => count($verifications), 'icon' => 'fas fa-building', 'color' => 'primary'],
];

// Helper for status dot class
function getVerificationStatusClass($status)
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

    /* Company Details Layout */
    .company-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        min-width: 0;
    }

    .company-name {
        font-size: 0.95rem;
        color: #212121;
        font-weight: 600;
    }
</style>

<div class="container-fluid p-8">
    <?php
    $config = [
        'title'       => 'Company Verifications',
        'description' => 'Review and verify installer company registration requests.',
        'buttons'     => [
            [
                'label'   => 'Download PDF',
                'icon'    => 'fas fa-file-pdf',
                'class'   => 'btn-outline-primary',
                'onclick' => 'onclick="SolarSenseReport.download({tableSelector:\'.data-table\',title:\'Verification Requests Report\',subtitle:\'Company registration and verification status\',columns:[\'Company Name\',\'Contact\',\'Address\',\'Submitted Date\',\'Status\']},this)"'
            ]
        ]
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <?php
    $config = [
        'search' => [
            'id' => 'searchBar',
            'name' => 'search',
            'label' => 'Search Company',
            'placeholder' => 'Search by company name...'
        ],
        'filters' => [
            [
                'id' => 'statusFilter',
                'name' => 'status',
                'label' => 'Status Filter',
                'options' => [
                    ['value' => '', 'label' => 'All Status'],
                    ['value' => 'Pending', 'label' => 'Pending'],
                    ['value' => 'Verified', 'label' => 'Verified']
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

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Submitted Date</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($verifications)): ?>
                            <tr><td colspan="6" class="text-center p-6 text-secondary">No verification requests found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($verifications as $row): ?>
                                <?php
                                $name   = is_object($row) ? $row->company_name : $row['company_name'];
                                $email  = is_object($row) ? $row->email        : $row['email'];
                                $phone  = is_object($row) ? $row->contact      : $row['contact'];
                                $addr   = is_object($row) ? $row->address      : $row['address'];
                                $date   = is_object($row) ? $row->request_date : $row['request_date'];
                                $status = is_object($row) ? $row->status       : $row['status'];
                                $cid    = is_object($row) ? $row->companyId    : $row['companyId'];
                                ?>
                                <tr class="data-table-row">
                                    <td>
                                        <div class="d-flex align-center gap-3">
                                            <img src="<?php echo getAvatarUrl($name); ?>" alt="<?php echo htmlspecialchars($name); ?>">
                                            <div class="company-details">
                                                <div class="company-name"><?php echo htmlspecialchars($name); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="company-details">
                                            <div class="email"><?php echo htmlspecialchars($email); ?></div>
                                            <div class="text-secondary text-xs"><?php echo htmlspecialchars($phone); ?></div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($addr); ?></td>
                                    <td><?php echo htmlspecialchars($date); ?></td>
                                    <td>
                                        <div class="d-flex align-center">
                                            <span class="status-dot <?php echo getVerificationStatusClass($status); ?> mr-2"></span>
                                            <span class="badge <?php echo strtolower($status) === 'pending' ? 'badge-warning' : 'badge-success'; ?>"><?php echo ucfirst($status); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="actions-menu d-flex gap-2 justify-center">
                                            <button class="btn-icon btn-sm btn-info" title="View Details"
                                                    onclick="openViewModal(<?php echo (int)$cid; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if (strtolower($status) === 'pending'): ?>
                                            <button class="btn-icon btn-sm btn-success" title="Verify"
                                                    onclick="openVerifyModal(<?php echo (int)$cid; ?>, '<?php echo htmlspecialchars($name, ENT_QUOTES); ?>')">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
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
</div>

<?php
$config = [
    'modal_id' => 'verifyModal',
    'title' => 'Verify Company',
    'icon' => 'fas fa-check-circle',
    'icon_color' => 'text-success',
    'heading' => 'Confirm Verification',
    'message' => 'Are you sure you want to verify this company? This will grant them access to the platform.',
    'confirm_text' => 'Verify Company',
    'cancel_text' => 'Cancel',
    'confirm_action' => '',
    'confirm_method' => 'GET',
    'confirm_class' => 'btn-success',
    'confirm_icon' => 'fas fa-check-circle'
];
include __DIR__ . '/../../inc/models/confirmation_modal.php';
?>

<script>
    const verifications = <?php echo json_encode($verifications); ?>;

    function openViewModal(id) {
        const reg = verifications.find(r => (is_object(r) ? r.companyId : r.companyId) == id);
        if (reg) {
            // Re-use your existing showViewModal logic here
            showViewModal(reg);
        }
    }

    function openVerifyModal(id, name) {
        const modal = document.getElementById('verifyModal');
        modal.querySelector('.modal-body p').innerHTML = `Are you sure you want to verify <strong>${name}</strong>? This will grant them access to the platform.`;

        const confirmBtn = modal.querySelector('.btn-success');
        if (confirmBtn) {
            confirmBtn.setAttribute('href', "<?php echo URLROOT; ?>/superadmin/verify_company/" + id);
        }
        showConfirmationModal('verifyModal');
    }

    function is_object(val) {
        return val != null && typeof val === 'object' && !Array.isArray(val);
    }
</script>

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>