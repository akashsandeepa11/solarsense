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
    // Sample agent data
    $agents = [
        [
            'id' => 1,
            'name' => 'John Doe',
            'role' => 'Service Agent',
            'email' => 'john@example.com',
            'phone' => '+94 77 123 4567',
            'assigned' => '5',
            'completed' => '3',
            'pending' => '2',
            'status' => 'Active',
            'last_active' => 'Today, 2:30 PM',
            'avatar' => 'https://ui-avatars.com/api/?name=John+Doe&background=fe9630&color=fff'
        ],
        [
            'id' => 2,
            'name' => 'Sarah Smith',
            'role' => 'Senior Agent',
            'email' => 'sarah@example.com',
            'phone' => '+94 77 234 5678',
            'assigned' => '8',
            'completed' => '6',
            'pending' => '2',
            'status' => 'Active',
            'last_active' => '2 hours ago',
            'avatar' => 'https://ui-avatars.com/api/?name=Sarah+Smith&background=22c55e&color=fff'
        ],
        [
            'id' => 3,
            'name' => 'Mike Johnson',
            'role' => 'Service Agent',
            'email' => 'mike@example.com',
            'phone' => '+94 77 345 6789',
            'assigned' => '3',
            'completed' => '1',
            'pending' => '2',
            'status' => 'On Leave',
            'last_active' => 'Yesterday',
            'avatar' => 'https://ui-avatars.com/api/?name=Mike+Johnson&background=f59e0b&color=fff'
        ],
        [
            'id' => 4,
            'name' => 'Lisa Brown',
            'role' => 'Service Agent',
            'email' => 'lisa@example.com',
            'phone' => '+94 77 456 7890',
            'assigned' => '6',
            'completed' => '3',
            'pending' => '3',
            'status' => 'Active',
            'last_active' => '30 min ago',
            'avatar' => 'https://ui-avatars.com/api/?name=Lisa+Brown&background=00bcd4&color=fff'
        ]
    ];

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
        'rows' => $agents,
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
                'onclick' => 'onclick="deleteAgent(' . 'this' . ')"'
            ]
        ]
    ];
    include __DIR__ . '/../../inc/components/data_table.php';
    ?>
</div>
