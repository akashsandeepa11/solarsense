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
                'completed' => $agent['completed_tasks'] ?? 0,
                'pending' => $agent['pending_tasks'] ?? 0,
                'status' => ucfirst($agent['agent_status'] ?? 'Inactive'),
                'avatar' => getAvatarUrl($agent['full_name'])
            ];
        }
    }

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Agent</th>
                            <th>Contact</th>
                            <th>Assigned Tasks</th>
                            <th>Completed</th>
                            <th>Pending</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($processedAgents)): ?>
                            <tr><td colspan="7" class="text-center p-6 text-secondary">No clients available</td></tr>
                        <?php else: ?>
                            <?php foreach ($processedAgents as $row): ?>
                                <?php
                                $statusColor = $row['status'] === 'Active' ? 'text-success' : 'text-warning';
                                ?>
                                <tr class="data-table-row">
                                    <td>
                                        <div class="d-flex align-center gap-3">
                                            <div class="agent-avatar"><img src="<?php echo htmlspecialchars($row['avatar']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"></div>
                                            <div class="agent-details">
                                                <div class="agent-name font-semibold"><?php echo htmlspecialchars($row['name']); ?></div>
                                                <div class="agent-role text-secondary text-sm"><?php echo htmlspecialchars($row['role']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact-info">
                                            <div class="email text-sm"><?php echo htmlspecialchars($row['email']); ?></div>
                                            <div class="phone text-secondary text-sm"><?php echo htmlspecialchars($row['phone']); ?></div>
                                        </div>
                                    </td>
                                    <td><div class="assgined text-sm"><?php echo htmlspecialchars($row['assigned']); ?></div></td>
                                    <td><div class="completed text-sm"><?php echo htmlspecialchars($row['completed']); ?></div></td>
                                    <td><div class="pending text-sm"><?php echo htmlspecialchars($row['pending']); ?></div></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>">
                                            <i class="fas fa-circle <?php echo $statusColor; ?> mr-1"></i><?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions-menu d-flex gap-2 justify-center">
                                            <a href="<?php echo URLROOT; ?>/<?php echo $rolePath; ?>/team/agent_details/<?php echo $row['id']; ?>" class="btn-icon btn-sm btn-info" title="View Details"><i class="fas fa-eye"></i></a>
                                            <?php if ($data['user']['role'] === ROLE_INSTALLER_ADMIN): ?>
                                            <a href="<?php echo URLROOT; ?>/installeradmin/team/edit_agent/<?php echo $row['id']; ?>" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                            <button class="btn-icon btn-icon-danger" title="Remove" onclick="openDeleteModal(<?php echo (int)$row['id']; ?>)"><i class="fas fa-trash"></i></button>
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