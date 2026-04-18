<!-- <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/installer_admin/team.css"> -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components.css">

<style>
    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #ffffff;
        border: none;
    }
</style>

<div class="team-container container-fluid p-8">

    <!-- Page Header -->
    <?php
    $buttons = [];
    if ($data['user']['role'] === ROLE_INSTALLER_ADMIN) {
        $buttons[] = [
            'label' => 'Add New Agent',
            'url'   => URLROOT . '/installeradmin/team/add_service_agent',
            'icon'  => 'fas fa-plus',
            'class' => 'btn-primary'
        ];
    }
    $buttons[] = [
        'label'   => 'Download PDF',
        'icon'    => 'fas fa-file-pdf',
        'class'   => 'btn-outline-primary',
        'onclick' => 'onclick="SolarSenseReport.download({tableSelector:\'.data-table\',title:\'Service Agents Report\',subtitle:\'Agent performance and task summary\',columns:[\'Agent\',\'Contact\',\'Assigned Tasks\',\'Completed\',\'Pending\',\'Status\']},this)"'
    ];

    $config = [
        'title'       => 'Service Agents',
        'description' => 'Manage your team of service agents and track their tasks',
        'buttons'     => $buttons
    ];
    include __DIR__ . '/../../inc/components/page_header.php';

    $stats = $data['stats'];
    ?>

    <!-- Filter & Search Section -->
    <?php
    $config = [
        'search' => [
            'id' => 'searchAgents',
            'name' => 'search',
            'label' => 'Search Agents',
            'placeholder' => 'Search by name or email...'
        ],
        'filters' => [
            [
                'id' => 'filterStatus',
                'name' => 'status',
                'label' => 'Status',
                'options' => [
                    ['value' => '', 'label' => 'All Status'],
                    ['value' => 'active', 'label' => 'Active'],
                    ['value' => 'inactive', 'label' => 'Inactive'],
                    ['value' => 'on_leave', 'label' => 'On Leave']
                ]
            ],
            [
                'id' => 'filterWorkload',
                'name' => 'workload',
                'label' => 'Workload',
                'options' => [
                    ['value' => '', 'label' => 'All Workloads'],
                    ['value' => 'high', 'label' => 'High (5+ tasks)'],
                    ['value' => 'medium', 'label' => 'Medium (2-4 tasks)'],
                    ['value' => 'low', 'label' => 'Low (0-1 tasks)']
                ]
            ]
        ],
        'buttons' => [],
        'form_action' => URLROOT . '/installeradmin/team',
        'form_method' => 'GET',
        'auto_submit' => true,
        'reset_on_clear' => true
    ];
    include __DIR__ . '/../../inc/components/filter_bar.php';
    ?>

    <!-- Teams Stats Section -->
    <?php
    $stats_data = [
        ['label' => 'Total Agents', 'value' => $stats['total_agents'] ?? '0', 'icon' => 'fas fa-users', 'color' => 'primary'],
        ['label' => 'Active', 'value' => $stats['active_agents'] ?? '0', 'icon' => 'fas fa-check-circle', 'color' => 'success'],
        ['label' => 'Total Tasks', 'value' => $stats['total_tasks'] ?? '0', 'icon' => 'fas fa-tasks', 'color' => 'warning'],
        ['label' => 'Pending Tasks', 'value' => $stats['pending_tasks'] ?? '0', 'icon' => 'fas fa-clock', 'color' => 'accent']
    ];

    $config = [
        'stats' => $stats_data,
        'columns' => 4
    ];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <!-- Service Agents List -->
    <?php
    $agents = $data['agents'];
    $processedAgents = [];
    if (!empty($agents)) {
        foreach ($agents as $agent) {
            $agent = (array) $agent; // Convert stdClass to array for easier access
    
            $processedAgents[] = [
                'id' => $agent['id'],
                'name' => $agent['full_name'],
                'role' => 'ServiceAgent',
                'email' => $agent['email'],
                'phone' => $agent['contact'],
                'assigned' => $agent['assigned_tasks'] ?? 0,
                'language' => $agent['language'] ?? 0,
                'completed' => $agent['completed_tasks'] ?? 0,
                'pending' => $agent['pending_tasks'] ?? 0,
                'status' => ucfirst($agent['agent_status'] ?? 'Inactive'),
                'avatar' => getAvatarUrl($agent['full_name'])
            ];
        }
    }

    $config = [
        'headers' => [
            ['key' => 'name', 'label' => 'Agent'],
            ['key' => 'email', 'label' => 'Contact'],
            ['key' => 'assigned', 'label' => 'Assigned Tasks'],
            ['key' => 'completed', 'label' => 'Completed'],
            ['key' => 'pending', 'label' => 'Pending'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'language', 'label' => 'Language']
        ],
        'rows' => $processedAgents,
        'columns' => [
            [
                'key' => 'name',
                'render' => function ($row) {
                    return '<div class="d-flex align-center gap-3">
                                <div class="agent-avatar">
                                    <img src="' . htmlspecialchars($row['avatar']) . '" alt="' . htmlspecialchars($row['name']) . '">
                                </div>
                                <div class="agent-details">
                                    <div class="agent-name font-semibold">' . htmlspecialchars($row['name']) . '</div>
                                    <div class="agent-role text-secondary text-sm">' . htmlspecialchars($row['role']) . '</div>
                                </div>
                            </div>';
                }
            ],
            [
                'key' => 'email',
                'render' => function ($row) {
                    return '<div class="contact-info">
                                <div class="email text-sm">' . htmlspecialchars($row['email']) . '</div>
                                <div class="phone text-secondary text-sm">' . htmlspecialchars($row['phone']) . '</div>
                            </div>';
                }
            ],
            [
                'key' => 'assigned',
                'render' => function ($row) {
                    return '<div class="assgined text-sm">' . htmlspecialchars($row['assigned']) . '</div>';
                }
            ],
            [
                'key' => 'completed',
                'render' => function ($row) {
                    return '<div class="completed text-sm">' . htmlspecialchars($row['completed']) . '</div>';
                }
            ],
            [
                'key' => 'pending',
                'render' => function ($row) {
                    return '<div class="pending text-sm">' . htmlspecialchars($row['pending']) . '</div>';
                }
            ],
            [
                'key' => 'status',
                'render' => function ($row) {
                    $statusColor = $row['status'] === 'Active' ? 'text-success' : 'text-warning';
                    return '<span class="status-badge status-' . strtolower(str_replace(' ', '-', $row['status'])) . '">
                                <i class="fas fa-circle ' . $statusColor . ' mr-1"></i>' . htmlspecialchars($row['status']) . '
                            </span>';
                }
            ]
        ]
    ];

    $rolePath = ($data['user']['role'] === ROLE_INSTALLER_ADMIN) ? 'installeradmin' : 'operationmanager';

    $config['actions'] = [
        [
            'label' => 'View Details',
            'icon' => 'fas fa-eye',
            'url' => URLROOT . '/' . $rolePath . '/team/agent_details/{id}',
            'class' => 'btn-sm btn-info'
        ]
    ];

    // Add Edit and Remove actions only for Installer Admin
    if ($data['user']['role'] === ROLE_INSTALLER_ADMIN) {
        $config['actions'][] = [
            'label' => 'Edit',
            'icon' => 'fas fa-edit',
            'url' => URLROOT . '/installeradmin/team/edit_agent/{id}'
        ];
        $config['actions'][] = [
            'label' => 'Remove',
            'icon' => 'fas fa-trash',
            'class' => 'btn-icon-danger',
            'onclick' => 'onclick="openDeleteModal(' . '{id}' . ')"'
        ];
    }

    $config['empty_message'] = 'No clients available';

    include __DIR__ . '/../../inc/components/data_table.php';
    ?>

    <!-- Delete Confirmation Modal -->
    <?php
    $config = [
        'modal_id' => 'deleteTeamModal',
        'title' => 'Confirm Delete',
        'icon' => 'fas fa-exclamation-triangle',
        'icon_color' => 'text-warning',
        'heading' => 'Delete Service Agent?',
        'message' => 'Are you sure you want to delete this agent? This action cannot be undone. All associated task data will be archived.',
        'confirm_text' => 'Delete Agent',
        'confirm_icon' => 'fas fa-check',
        'cancel_text' => 'Cancel',
        'cancel_icon' => 'fas fa-times',
        'confirm_action' => URLROOT . '/installeradmin/team/delete_agent/',
        'confirm_method' => 'POST',
        'confirm_class' => 'btn-error'
    ];
    include __DIR__ . '/../../inc/models/confirmation_modal.php';
    ?>

    <!-- Dynamic Delete Modal Handler -->
    <script>
        function openDeleteModal(agentId) {
            const modal = document.getElementById('deleteTeamModal');
            const form = modal.querySelector('form');
            if (form) {
                form.action = '<?php echo URLROOT; ?>/installeradmin/team/delete_agent/' + agentId;
            }
            showConfirmationModal('deleteTeamModal');
        }
    </script>

    <?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>
</div>