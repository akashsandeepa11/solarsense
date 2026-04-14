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
    $buttons = [];
    if ($data['user']['role'] === ROLE_INSTALLER_ADMIN) {
        $buttons[] = [
            'label' => 'Add Company',
            'url' => URLROOT . '/installeradmin/companies/add',
            'icon' => 'fas fa-plus',
            'class' => 'btn-primary btn-md'
        ];
    }

    $config = [
        'title' => 'Companies Management',
        'buttons' => [
            [
                'label' => 'Add Company',
                'url' => URLROOT . '/superadmin/companies/add', // Corrected route
                'icon' => 'fas fa-plus',
                'class' => 'btn-primary'
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

    <?php
    $config = [
        'headers' => [
            ['key' => 'company_id', 'label' => 'ID'],
            ['key' => 'company_name', 'label' => 'Company Name'],
            ['key' => 'email', 'label' => 'Email Address'],
            ['key' => 'address', 'label' => 'Address'],
            ['key' => 'contact', 'label' => 'Contact Number']
        ],
        'rows' => $data['companies'],
        'columns' => [
            [
                'key' => 'company_name',
                'render' => function ($row) {
                    $name = is_object($row) ? $row->company_name : $row['company_name'];
                    return '<div class="d-flex align-center gap-3">
                            <img src="' . getAvatarUrl($name) . '" alt="' . htmlspecialchars($name) . '" style="width: 35px; border-radius: 50%;">
                            <div class="agent-details">
                                <div class="agent-name font-semibold">' . htmlspecialchars($name) . '</div>
                            </div>
                        </div>';
                }
            ]
        ],
        'actions' => [
            ['label' => 'View', 'icon' => 'fas fa-eye', 'url' => URLROOT . '/superadmin/companies/details/{company_id}', 'class' => 'btn-sm btn-info'],
            ['label' => 'Edit', 'icon' => 'fas fa-edit', 'url' => URLROOT . '/superadmin/companies/edit/{company_id}', 'class' => 'btn-sm btn-primary'],
            ['label' => 'Remove', 'icon' => 'fas fa-trash', 'class' => 'btn-icon-danger', 'onclick' => 'onclick="openDeleteModal({company_id})"']
        ],
        'empty_message' => 'No verified companies found.'
    ];

    include __DIR__ . '/../../inc/components/data_table.php';
    ?>

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
                // Update the form action to point to the superadmin controller
                form.action = '<?php echo URLROOT; ?>/superadmin/companies/delete/' + id;
            }
            showConfirmationModal('deleteCompanyModal');
        }
    </script>
</div>