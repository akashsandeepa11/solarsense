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
        'title' => 'Company Verifications',
        'description' => 'Review and verify installer company registration requests.',
        'buttons' => [
            [
                'label' => 'Download PDF',
                'icon' => 'fas fa-file-pdf',
                'class' => 'btn-outline-primary',
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
        'auto_submit' => false, // Set to false for client-side filtering
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
            ['key' => 'company_name', 'label' => 'Company Name'],
            ['key' => 'contact', 'label' => 'Contact'],
            ['key' => 'address', 'label' => 'Address'],
            ['key' => 'request_date', 'label' => 'Submitted Date'],
            ['key' => 'status', 'label' => 'Status']
        ],
        'rows' => $verifications,
        'columns' => [
            [
                'key' => 'company_name',
                'render' => function ($row) {
                    $name = is_object($row) ? $row->company_name : $row['company_name'];
                    return '<div class="d-flex align-center gap-3">
                                <img src="' . getAvatarUrl($name) . '" alt="' . htmlspecialchars($name) . '">
                                <div class="company-details">
                                    <div class="company-name">' . htmlspecialchars($name) . '</div>
                                </div>
                            </div>';
                }
            ],
            [
                'key' => 'contact', // Custom renderer to merge email and contact number
                'render' => function ($row) {
                    $email = is_object($row) ? $row->email : $row['email'];
                    $phone = is_object($row) ? $row->contact : $row['contact'];
                    return '<div class="company-details">
                                <div class="email">' . htmlspecialchars($email) . '</div>
                                <div class="text-secondary text-xs">' . htmlspecialchars($phone) . '</div>
                            </div>';
                }
            ],
            [
                'key' => 'status',
                'render' => function ($row) {
                    $status = is_object($row) ? $row->status : $row['status'];
                    return '<div class="d-flex align-center" data-filter="status"> 
                    <span class="status-dot ' . getVerificationStatusClass($status) . ' mr-2"></span>
                    <span class="badge ' . (strtolower($status) === 'pending' ? 'badge-warning' : 'badge-success') . '">' . ucfirst($status) . '</span>
                </div>';
                }
            ]
        ],
        'actions' => [
            // [
            //     'label' => 'View Details',
            //     'icon' => 'fas fa-eye',
            //     'class' => 'btn-sm btn-info',
            //     'onclick' => 'onclick="openViewModal({companyId})"'
            // ],
            [
                'label' => 'Verify',
                'icon' => 'fas fa-check-circle',
                'class' => 'btn-sm btn-success',
                'onclick' => 'onclick="openVerifyModal({companyId}, \'{company_name}\')"',
                'condition' => function ($row) {
                    $status = is_object($row) ? $row->status : $row['status'];
                    return strtolower($status) === 'pending';
                }
            ]
        ],
        'empty_message' => 'No verification requests found.'
    ];
    include __DIR__ . '/../../inc/components/data_table.php';
    ?>
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
    const applyFilters = () => {
        const searchQuery = document.getElementById('searchBar').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();

        document.querySelectorAll('tbody tr').forEach(row => {
            // Get text content for search
            const rowText = row.innerText.toLowerCase();
            
            // Get specific status value from our data-filter attribute
            const rowStatus = row.querySelector('[data-filter="status"]')?.innerText.trim().toLowerCase() || "";

            const matchesSearch = rowText.includes(searchQuery);
            const matchesStatus = !statusFilter || rowStatus === statusFilter;

            // Show row only if it matches BOTH filters
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    };

    // Attach listeners to both elements
    document.getElementById('searchBar').addEventListener('input', applyFilters);
    document.getElementById('statusFilter').addEventListener('change', applyFilters);

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