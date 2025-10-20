<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/installer_admin/team.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components.css">

<div class="team-container container-fluid p-8">
    
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Service Agents',
        'description' => 'Manage your team of service agents and track their tasks',
        'buttons' => [
            [
                'label' => 'Add New Agent',
                'url' => URLROOT . '/installeradmin/team/add_service_agent',
                'icon' => 'fas fa-plus',
                'class' => 'btn-success'
            ]
        ]
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Filter & Search Section -->
    <?php
    $config = [
        'search' => [
            'id' => 'searchAgents',
            'label' => 'Search Agents',
            'placeholder' => 'Search by name or email...'
        ],
        'filters' => [
            [
                'id' => 'filterStatus',
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
                'label' => 'Workload',
                'options' => [
                    ['value' => '', 'label' => 'All Workloads'],
                    ['value' => 'high', 'label' => 'High (5+ tasks)'],
                    ['value' => 'medium', 'label' => 'Medium (2-4 tasks)'],
                    ['value' => 'low', 'label' => 'Low (0-1 tasks)']
                ]
            ]
        ],
        'buttons' => [
            [
                'id' => 'filterBtn',
                'label' => 'Filter',
                'icon' => 'fas fa-filter'
            ]
        ]
    ];
    include __DIR__ . '/../../inc/components/filter_bar.php';
    ?>

    <!-- Teams Stats Section -->
    <?php
    $stats_data = [
        ['label' => 'Total Agents', 'value' => '12', 'icon' => 'fas fa-users', 'color' => 'primary'],
        ['label' => 'Active', 'value' => '10', 'icon' => 'fas fa-check-circle', 'color' => 'success'],
        ['label' => 'Total Tasks', 'value' => '48', 'icon' => 'fas fa-tasks', 'color' => 'warning'],
        ['label' => 'Pending Tasks', 'value' => '15', 'icon' => 'fas fa-clock', 'color' => 'accent']
    ];
    
    $config = [
        'stats' => $stats_data,
        'columns' => 4
    ];
    include __DIR__ . '/../../inc/components/stats_grid.php';
    ?>

    <!-- Service Agents List -->
    <?php
    // Transform retrieved agent data into the format needed for display
    // Retrieved data columns: user_id, email, type, full_name, contact, status
    $agents = isset($data['agents']) ? $data['agents'] : [];
    $processedAgents = [];
    
    // Convert stdClass object to array if needed
    if (is_object($agents)) {
        $agents = json_decode(json_encode($agents), true);
    }
    
    // Handle single object result - convert to array of objects
    if (is_object($agents)) {
        $agents = [$agents];
    } elseif (!is_array($agents)) {
        $agents = [];
    }
    
    if (!empty($agents)) {
        foreach ($agents as $agent) {
            // Support both array and object access
            $id = is_object($agent) ? ($agent->user_id ?? 0) : ($agent['user_id'] ?? 0);
            $fullName = is_object($agent) ? ($agent->full_name ?? 'Unknown Agent') : ($agent['full_name'] ?? 'Unknown Agent');
            $email = is_object($agent) ? ($agent->email ?? 'N/A') : ($agent['email'] ?? 'N/A');
            $contact = is_object($agent) ? ($agent->contact ?? 'N/A') : ($agent['contact'] ?? 'N/A');
            $status = is_object($agent) ? ($agent->status ?? 'Inactive') : ($agent['status'] ?? 'Inactive');
            
            $processedAgents[] = [
                'id' => $id,
                'name' => $fullName,
                'role' => 'Service Agent', // constant value
                'email' => $email,
                'phone' => $contact,
                'assigned' => rand(3, 8), // constant/simulated value
                'completed' => rand(1, 6), // constant/simulated value
                'pending' => rand(1, 3), // constant/simulated value
                'status' => ucfirst($status), // use status from db
                'last_active' => 'Today, 2:30 PM', // constant value
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&background=fe9630&color=fff'
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
            ['key' => 'last_active', 'label' => 'Last Active']
        ],
        'rows' => $processedAgents,
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
                                    <div class="agent-role text-secondary text-sm">' . htmlspecialchars($row['role']) . '</div>
                                </div>
                            </div>';
                }
            ],
            [
                'key' => 'email',
                'render' => function($row) {
                    return '<div class="contact-info">
                                <div class="email text-sm">' . htmlspecialchars($row['email']) . '</div>
                                <div class="phone text-secondary text-sm">' . htmlspecialchars($row['phone']) . '</div>
                            </div>';
                }
            ],
            [
                'key' => 'assigned',
                'render' => function($row) {
                    return '<span class="badge bg-primary">' . htmlspecialchars($row['assigned']) . '</span>';
                }
            ],
            [
                'key' => 'completed',
                'render' => function($row) {
                    $percentage = $row['assigned'] > 0 ? intval(($row['completed'] / $row['assigned']) * 100) : 0;
                    return '<div class="task-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: ' . $percentage . '%"></div>
                                </div>
                                <div class="progress-text text-sm">' . htmlspecialchars($row['completed']) . '</div>
                            </div>';
                }
            ],
            [
                'key' => 'pending',
                'render' => function($row) {
                    return '<span class="badge bg-warning">' . htmlspecialchars($row['pending']) . '</span>';
                }
            ],
            [
                'key' => 'status',
                'render' => function($row) {
                    $statusColor = $row['status'] === 'Active' ? 'text-success' : 'text-warning';
                    return '<span class="status-badge status-' . strtolower(str_replace(' ', '-', $row['status'])) . '">
                                <i class="fas fa-circle ' . $statusColor . ' mr-1"></i>' . htmlspecialchars($row['status']) . '
                            </span>';
                }
            ]
        ],
        'actions' => [
            [
                'label' => 'View Details',
                'icon' => 'fas fa-eye',
                'url' => URLROOT . '/installeradmin/team/agent_details/{id}'
            ],
            [
                'label' => 'Edit',
                'icon' => 'fas fa-edit',
                'url' => URLROOT . '/installeradmin/team/edit/{id}'
            ],
            [
                'label' => 'Remove',
                'icon' => 'fas fa-trash',
                'class' => 'btn-icon-danger',
                'onclick' => 'onclick="openDeleteModal(' . '{id}' . ')"'
            ]
        ]
    ];
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
        'confirm_class' => 'btn-danger'
    ];
    include __DIR__ . '/../../inc/models/confirmation_modal.php';
    ?>

    <!-- Dynamic Delete Modal Handler -->
    <script>
    function openDeleteModal(agentId) {
        // Get the form inside the modal and update its action
        const modal = document.getElementById('deleteTeamModal');
        const form = modal.querySelector('form');
        if (form) {
            form.action = '<?php echo URLROOT; ?>/installeradmin/team/delete_agent/' + agentId;
        }
        // Show the modal
        showConfirmationModal('deleteTeamModal');
    }
    </script>
</div>
