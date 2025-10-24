    <?php
    // --- PHP Setup for Dummy Data ---

    // Summary Card Data
    $summary_cards = [
        ['label' => 'Total Companies', 'value' => '128', 'icon' => 'fas fa-users', 'color' => 'primary'],
        ['label' => 'Suspended', 'value' => '4', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'error'],
        ['label' => 'Pending', 'value' => '9', 'icon' => 'fas fa-wrench', 'color' => 'warning'],
        ['label' => 'Active', 'value' => '23', 'icon' => 'fas fa-check-circle', 'color' => 'success']
    ];

    // Get all customers from controller
    $clients = isset($data['customers']) ? $data['customers'] : [];

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


    // Function to determine the status dot color
    function getStatusClass($health) {
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

    <link rel="stylesheet" href="<?php echo URLROOT?>/css/components.css">

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
        // Build buttons array based on role
        $buttons = [];
        if($data['user']['role'] === ROLE_INSTALLER_ADMIN) {
            $buttons[] = [
                'label' => 'Add Customer',
                'url' => URLROOT . '/installeradmin/fleet/add_customer',
                'icon' => 'fas fa-plus',
                'class' => 'btn-primary btn-md'
            ];
        }
        
        $config = [
            'title' => 'Companies Fleet Dashboard',
            'description' => 'Overview of your companies.',
            'buttons' => $buttons
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
                'placeholder' => 'Search by Company name ...'
            ],
            'filters' => [
                [
                    'id' => 'Status',
                    'name' => 'Status',
                    'label' => 'Client Status',
                    'options' => [
                        ['value' => '', 'label' => 'Status'],
                        ['value' => 'Active', 'label' => 'Active'],
                        ['value' => 'Suspended', 'label' => 'Suspended'],
                        ['value' => 'Pending', 'label' => 'Pending']
                    ]
                ],
            //     [
            //         'id' => 'filterSize',
            //         'name' => 'size',
            //         'label' => 'System Size',
            //         'options' => [
            //             ['value' => '', 'label' => 'All Sizes'],
            //             ['value' => 'small', 'label' => 'Small (< 5kW)'],
            //             ['value' => 'medium', 'label' => 'Medium (5-10kW)'],
            //             ['value' => 'large', 'label' => 'Large (> 10kW)']
            //         ]
            //     ]
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
            'columns' => 4
        ];
        include __DIR__ . '/../../inc/components/stat_card.php';
        ?>

        <!-- Clients Table -->
        <?php
        $config = [
             'headers' => [
        ['key' => 'company_name', 'label' => 'Company Name'],
        ['key' => 'employees', 'label' => 'Number of Employees'],
        ['key' => 'installations', 'label' => 'Total Installations'],
        ['key' => 'registration_no', 'label' => 'Registration Number'],
        ['key' => 'status', 'label' => 'Status']
    ],
            'rows' => $clients,
            'columns' => [
                [
                    'key' => 'name',
                    'render' => function($row) {
                        return '<div class="d-flex align-center gap-3">
                                    <img src="' . getAvatarUrl($row['name']) . '" alt="' . htmlspecialchars($row['name']) . '">
                                    <div class="agent-details">
                                        <div class="agent-name font-semibold">' . htmlspecialchars($row['name']) . '</div>
                                        <div class="agent-role text-secondary text-sm">' . htmlspecialchars($row['location']) . '</div>
                                    </div>
                                </div>';
                    }
                ],
                [
                    'key' => 'health',
                    'render' => function($row) {
                        return '<div class="d-flex align-center">
                                    <span class="status-dot ' . getStatusClass($row['health']) . ' mr-2"></span>
                                    ' . htmlspecialchars($row['health']) . '
                                </div>';
                    }
                ],
                [
                    'key' => 'performance',
                    'render' => function($row) {
                        return htmlspecialchars($row['performance']) . '%';
                    }
                ],
                [
                    'key' => 'size',
                    'render' => function($row) {
                        return htmlspecialchars($row['size']) . ' kWp';
                    }
                ]
            ],
            'actions' => [],
            'empty_message' => 'No clients available'
        ];
        
        // Build actions array - View Details always available
        $config['actions'] = [
            [
                'label' => 'View Details',
                'icon' => 'fas fa-eye',
                'url' => URLROOT . '/installeradmin/fleet/customer_details/{id}',
                'class' => 'btn-sm btn-info'
            ]
        ];
        
        // Add Edit and Remove actions only for Installer Admin
        if($data['user']['role'] === ROLE_INSTALLER_ADMIN) {
            $config['actions'][] = [
                'label' => 'Edit',
                'icon' => 'fas fa-edit',
                'url' => URLROOT . '/installeradmin/fleet/edit_customer/{id}',
                'class' => 'btn-sm btn-primary'
            ];
            $config['actions'][] = [
                'label' => 'Remove',
                'icon' => 'fas fa-trash',
                'class' => 'btn-icon-danger',
                'onclick' => 'onclick="openDeleteModal(' . '{id}' . ')"'
            ];
        }
        
        include __DIR__ . '/../../inc/components/data_table.php';
?>

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
        // Get the form inside the modal and update its action
        const modal = document.getElementById('deletefleetModal');
        const form = modal.querySelector('form');
        if (form) {
            form.action = '<?php echo URLROOT; ?>/installeradmin/fleet/delete_customer/' + customerId;
        }
        // Show the modal
        showConfirmationModal('deletefleetModal');
    }
    </script>
    </div>


                                