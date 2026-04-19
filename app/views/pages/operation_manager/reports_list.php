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

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Report ID</th>
                            <th>Task Type</th>
                            <th>Customer</th>
                            <th>Technician</th>
                            <th>Completion Date</th>
                            <th>Final Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['reports'])): ?>
                            <tr><td colspan="7" class="text-center p-6 text-secondary">No service reports found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($data['reports'] as $row): ?>
                                <?php $cls = $row->final_status === 'completed' ? 'badge-success' : 'badge-warning'; ?>
                                <tr class="data-table-row">
                                    <td><span class="font-semibold">#REP-<?php echo $row->report_id; ?></span></td>
                                    <td><?php echo htmlspecialchars($row->service_type); ?></td>
                                    <td><?php echo htmlspecialchars($row->customer_name); ?></td>
                                    <td><span class="text-primary"><?php echo htmlspecialchars($row->agent_name); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($row->completion_date)); ?></td>
                                    <td><span class="badge <?php echo $cls; ?>"><?php echo ucfirst($row->final_status); ?></span></td>
                                    <td>
                                        <div class="actions-menu d-flex gap-2 justify-center">
                                            <a href="<?php echo URLROOT; ?>/operationmanager/maintenance/reports/view/<?php echo $row->report_id; ?>"
                                               class="btn-icon btn-sm btn-info" title="View Full Report">
                                                <i class="fas fa-eye"></i>
                                            </a>
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
</div>