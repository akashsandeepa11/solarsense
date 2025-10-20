    <?php
    // --- PHP Setup for Dummy Data ---

    // Summary Card Data
    $summary_cards = [
        ['label' => 'Total Clients', 'value' => '128', 'icon' => 'fas fa-users', 'color' => 'primary'],
        ['label' => 'Systems with Active Faults', 'value' => '4', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'error'],
        ['label' => 'Pending Maintenance', 'value' => '9', 'icon' => 'fas fa-wrench', 'color' => 'warning'],
        ['label' => 'Services Completed (Month)', 'value' => '23', 'icon' => 'fas fa-check-circle', 'color' => 'success']
    ];

    // Get Client Table Data from Database
    $fleetModel = new M_Fleet();
    $clients = $fleetModel->get_customer_by_company(1);

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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/installer_admin/team.css">


    <link rel="stylesheet" href="<?php echo URLROOT?>/css/pages/installer/fleet_dashboard.css">
    <link rel="stylesheet" href="<?php echo URLROOT?>/css/components.css">

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
                    'class' => 'btn-primary btn-lg'
                ]
            ]
        ];
        include __DIR__ . '/../../inc/components/page_header.php';
        ?>

        <!-- Summary Cards -->
        <?php
        $config = [
            'stats' => $summary_cards,
            'columns' => 4
        ];
        include __DIR__ . '/../../inc/components/stats_grid.php';
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
                                    <div class="agent-avatar">
                                        <img src="' . htmlspecialchars($row['avatar']) . '" alt="' . htmlspecialchars($row['name']) . '">
                                    </div>
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
                'onclick' => 'onclick="deleteAgent(' . 'this' . ')"'
                ],
            ],
            'empty_message' => 'No clients available'
        ];
        include __DIR__ . '/../../inc/components/data_table.php';
        ?>
    </div>


                                