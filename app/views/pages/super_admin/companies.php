<?php
// Summary Card Data
$summary_cards = [
    ['label' => 'Total Companies', 'value' => '128', 'icon' => 'fas fa-users', 'color' => 'primary'],
    ['label' => 'Suspended', 'value' => '4', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'error'],
    ['label' => 'Pending', 'value' => '9', 'icon' => 'fas fa-wrench', 'color' => 'warning'],
    ['label' => 'Active', 'value' => '23', 'icon' => 'fas fa-check-circle', 'color' => 'success']
];

// Mock data provided in your snippet
$clients = [
    [
        'id' => 1,
        'company_name' => 'GreenMove Logistics',
        'employees' => 85,
        'installations' => 24,
        'registration_no' => 'REG-10234',
        'status' => 'Active'
    ],
    [
        'id' => 2,
        'company_name' => 'FleetPro Lanka',
        'employees' => 60,
        'installations' => 15,
        'registration_no' => 'REG-20456',
        'status' => 'Suspended'
    ],
    [
        'id' => 3,
        'company_name' => 'SmartTrans Pvt Ltd',
        'employees' => 120,
        'installations' => 32,
        'registration_no' => 'REG-30211',
        'status' => 'Pending'
    ],
    [
        'id' => 4,
        'company_name' => 'EcoDrive Solutions',
        'employees' => 40,
        'installations' => 12,
        'registration_no' => 'REG-40122',
        'status' => 'Active'
    ]
];

// Updated function to match Company Statuses
function getStatusClass($status)
{
    switch ($status) {
        case 'Active':
            return 'bg-success';
        case 'Pending':
            return 'bg-warning';
        case 'Suspended':
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

    /* Layout Styles */
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
</style>

<div class="container-fluid p-8">

    <?php
    $config = [
        'title'   => 'Companies Management',
        'buttons' => [
            [
                'label' => 'Add Company',
                'url'   => URLROOT . '/superadmin/companies/add',
                'icon'  => 'fas fa-plus',
                'class' => 'btn-primary'
            ],
            [
                'label'   => 'Download PDF',
                'icon'    => 'fas fa-file-pdf',
                'class'   => 'btn-outline-primary',
                'onclick' => 'onclick="SolarSenseReport.download({tableSelector:\'.data-table\',title:\'Companies Report\',subtitle:\'Verified installer companies\',columns:[\'ID\',\'Company Name\',\'Email\',\'Address\',\'Contact\']},this)"'
            ]
        ]
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <?php
    $config = [
        'search' => [
            'id' => 'searchCompanies',
            'name' => 'search',
            'label' => 'Search Companies',
            'placeholder' => 'Search by company name or Reg No...'
        ],
        'filters' => [
            [
                'id' => 'filterStatus',
                'name' => 'status',
                'label' => 'Company Status',
                'options' => [
                    ['value' => '', 'label' => 'All Statuses'],
                    ['value' => 'Active', 'label' => 'Active'],
                    ['value' => 'Suspended', 'label' => 'Suspended'],
                    ['value' => 'Pending', 'label' => 'Pending']
                ]
            ]
        ],
        'form_action' => URLROOT . '/installeradmin/companies',
        'form_method' => 'GET',
        'auto_submit' => true,
        'reset_on_clear' => true
    ];
    include __DIR__ . '/../../inc/components/filter_bar.php';
    ?>

    <?php
    $config = [
        'stats' => $summary_cards,
        'columns' => 4
    ];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company Name</th>
                            <th>Email Address</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['companies'])): ?>
                            <tr><td colspan="6" class="text-center p-6 text-secondary">No verified companies found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($data['companies'] as $row): ?>
                                <?php
                                $cid   = is_object($row) ? $row->company_id   : $row['company_id'];
                                $name  = is_object($row) ? $row->company_name : $row['company_name'];
                                $email = is_object($row) ? $row->email        : $row['email'];
                                $addr  = is_object($row) ? $row->address      : $row['address'];
                                $phone = is_object($row) ? $row->contact      : $row['contact'];
                                ?>
                                <tr class="data-table-row">
                                    <td><?php echo htmlspecialchars($cid); ?></td>
                                    <td>
                                        <div class="d-flex align-center gap-3">
                                            <img src="<?php echo getAvatarUrl($name); ?>" alt="<?php echo htmlspecialchars($name); ?>" style="width:35px;border-radius:50%;">
                                            <div class="agent-details">
                                                <div class="agent-name font-semibold"><?php echo htmlspecialchars($name); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($email); ?></td>
                                    <td><?php echo htmlspecialchars($addr); ?></td>
                                    <td><?php echo htmlspecialchars($phone); ?></td>
                                    <td>
                                        <div class="actions-menu d-flex gap-2 justify-center">
                                            <a href="<?php echo URLROOT; ?>/superadmin/companies/details/<?php echo $cid; ?>" class="btn-icon btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                            <a href="<?php echo URLROOT; ?>/superadmin/companies/edit/<?php echo $cid; ?>" class="btn-icon btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                            <button class="btn-icon btn-icon-danger" title="Remove" onclick="openDeleteModal(<?php echo (int)$cid; ?>)"><i class="fas fa-trash"></i></button>
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

    <?php
    $config = [
        'modal_id' => 'deleteCompanyModal',
        'title' => 'Confirm Delete',
        'icon' => 'fas fa-exclamation-triangle',
        'icon_color' => 'text-warning',
        'heading' => 'Delete Company?',
        'message' => 'Are you sure you want to delete this company? This will archive all associated data.',
        'confirm_text' => 'Delete',
        'confirm_action' => URLROOT . '/superadmin/companies/delete/',
        'confirm_method' => 'POST',
        'confirm_class' => 'btn-danger'
    ];
    include __DIR__ . '/../../inc/models/confirmation_modal.php';
    ?>

    <script>
        function openDeleteModal(id) {
            const modal = document.getElementById('deleteCompanyModal');
            const form = modal.querySelector('form');
            if (form) {
                form.action = '<?php echo URLROOT; ?>/superadmin/companies/delete/' + id;
            }
            showConfirmationModal('deleteCompanyModal');
        }
    </script>

    <?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>
</div>