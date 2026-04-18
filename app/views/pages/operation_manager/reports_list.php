<div class="container-fluid p-8">
    <?php
    $config = [
        'title' => 'Service Report History',
        'description' => 'View completed service documentation for your company.'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>
    <link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/components.css">
    <link rel="stylesheet" href="<?php echo URLROOT ?>/css/pages/installer_admin/managers.css">

    <link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/pages/installer/dashboard.css">
    <link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/pages/operation_manager/maintenance.css">


    <div class="managers-tabs mb-6">
        <div class="tabs-container">
            <a href="<?php echo URLROOT; ?>/operationmanager/maintenance/tasks"
                class="tab-item <?php echo ($data['active_tab'] === 'tasks') ? 'active' : ''; ?>">
                <i class="fas fa-tools"></i>
                <span>Tasks</span>
            </a>

            <a href="<?php echo URLROOT; ?>/operationmanager/maintenance/purchases/all"
                class="tab-item <?php echo ($data['active_tab'] === 'purchases') ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i>
                <span>Purchases</span>
            </a>

            <a href="<?php echo URLROOT; ?>/operationmanager/maintenance/reports"
                class="tab-item <?php echo ($data['active_tab'] === 'reports') ? 'active' : ''; ?>">
                <i class="fas fa-file-contract"></i>
                <span>Service Reports</span>
            </a>
        </div>
    </div>

    <?php
    // Table configuration
    $config = [
        'headers' => [
            ['key' => 'id', 'label' => 'Report ID'],
            ['key' => 'type', 'label' => 'Task Type'],
            ['key' => 'customer', 'label' => 'Customer'],
            ['key' => 'agent', 'label' => 'Technician'],
            ['key' => 'date', 'label' => 'Completion Date'],
            ['key' => 'status', 'label' => 'Final Status']
        ],
        'rows' => $data['reports'],
        'columns' => [
            ['key' => 'id', 'render' => fn($row) => '<span class="font-semibold">#REP-' . $row->report_id . '</span>'],
            ['key' => 'type', 'render' => fn($row) => htmlspecialchars($row->service_type)],
            ['key' => 'customer', 'render' => fn($row) => htmlspecialchars($row->customer_name)],
            ['key' => 'agent', 'render' => fn($row) => '<span class="text-primary">' . htmlspecialchars($row->agent_name) . '</span>'],
            ['key' => 'date', 'render' => fn($row) => date('M d, Y', strtotime($row->completion_date))],
            [
                'key' => 'status',
                'render' => function ($row) {
                    $cls = $row->final_status === 'completed' ? 'badge-success' : 'badge-warning';
                    return '<span class="badge ' . $cls . '">' . ucfirst($row->final_status) . '</span>';
                }
            ]
        ],
        'actions' => [
            [
                'label' => 'View Full Report',
                'icon' => 'fas fa-eye',
                'class' => 'btn-sm btn-info',
                'url' => URLROOT . '/operationmanager/maintenance/reports/view/{report_id}'
            ]
        ],
        'empty_message' => 'No service reports found.'
    ];
    include __DIR__ . '/../../inc/components/data_table.php';
    ?>
</div>