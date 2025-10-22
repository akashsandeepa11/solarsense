    <?php
    // --- PHP Setup for Dummy Data ---

    // Summary Card Data
    $summary_cards = [
        ['label' => 'Total Clients', 'value' => '128', 'icon' => 'fas fa-users', 'color' => 'primary'],
        ['label' => 'Systems with Active Faults', 'value' => '4', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'error'],
        ['label' => 'Pending Maintenance', 'value' => '9', 'icon' => 'fas fa-wrench', 'color' => 'warning'],
        ['label' => 'Services Completed (Month)', 'value' => '23', 'icon' => 'fas fa-check-circle', 'color' => 'success']
    ];

    // Get all customers from controller
    $clients = isset($data['customers']) ? $data['customers'] : [];

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
        $config = [
            'title' => 'Fleet Dashboard',
            'description' => 'Overview of your client systems.',
            'buttons' => [
                [
                    'label' => 'Add Customer',
                    'url' => URLROOT . '/installeradmin/fleet/add_customer',
                    'icon' => 'fas fa-plus',
                    'class' => 'btn-primary btn-md'
                ]
            ]
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
            'columns' => 4
        ];
        include __DIR__ . '/../../inc/components/stat_card.php';
        ?>

        <!-- Clients Table -->
        <?php
        $config = [
            'headers' => [
                ['key' => 'name', 'label' => 'Client Name'],
                ['key' => 'location', 'label' => 'Location'],
                ['key' => 'size', 'label' => 'System Size'],
                ['key' => 'health', 'label' => 'System Health'],
                ['key' => 'performance', 'label' => 'Performance'],
                ['key' => 'last_upload', 'label' => 'Last SMS Upload']
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
            'actions' => [
                [
                    'label' => 'View Details',
                    'icon' => 'fas fa-eye',
                    'url' => URLROOT . '/installeradmin/fleet/customer_details/{id}',
                    'class' => 'btn-sm btn-info'
                ],
                [
                    'label' => 'Edit',
                    'icon' => 'fas fa-edit',
                    'url' => URLROOT . '/installeradmin/fleet/edit_customer/{id}',
                    'class' => 'btn-sm btn-primary'
                ],
                [
                'label' => 'Remove',
                'icon' => 'fas fa-trash',
                'class' => 'btn-icon-danger',
                'onclick' => 'onclick="openDeleteModal(' . '{id}' . ')"'
                ],
            ],
            'empty_message' => 'No clients available'
        ];
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


                                