<?php
$rawTasks   = $data['tasks']   ?? [];
$rawHistory = $data['history'] ?? [];

$mappedTasks = [];
foreach ($rawTasks as $t) {
    $jsStatus = match(strtolower(trim($t->status ?? 'pending'))) {
        'in progress', 'in-progress' => 'In Progress',
        'completed'                  => 'Completed',
        default                      => 'Pending',
    };
    $mappedTasks[] = [
        'title'    => $t->title    ?? 'Untitled',
        'customer' => $t->customer ?? 'Unknown',
        'date'     => $t->date     ? date('Y-m-d', strtotime($t->date)) : 'N/A',
        'status'   => $jsStatus,
    ];
}

$mappedHistory = [];
foreach ($rawHistory as $h) {
    $isInstall = ($h->task_id === null || $h->task_id === '') && $h->order_id !== null;

    if ($isInstall) {
        $title = 'Order #' . $h->linked_order_id . ' — Installation';
        $customer = $h->order_customer ?? 'N/A';
    } else {
        $title = $h->task_title ?? 'Service Task';
        $customer = $h->task_customer ?? 'N/A';
    }

    $mappedHistory[] = [
        'title'        => $title,
        'customer'     => $customer,
        'date'         => $h->completion_date ? date('Y-m-d', strtotime($h->completion_date)) : 'N/A',
        'actions'      => $h->actions_taken ?? '—',
        'final_status' => $h->final_status ?? '—',
        'time_spent'   => $h->time_spent ?? '—',
    ];
}

$totalTasks    = count($mappedTasks);
$pending       = count(array_filter($mappedTasks, fn($t) => $t['status'] === 'Pending'));
$inProgress    = count(array_filter($mappedTasks, fn($t) => $t['status'] === 'In Progress'));
$totalHistory  = count($mappedHistory);
?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/css/pages/homeowner/service.css">
<link rel="stylesheet" href="<?php echo URLROOT ?>/css/pages/homeowner/reports.css">

<div class="content-area">

    <?php
    $config = [
        'title'       => 'Reports',
        'description' => 'Active tasks and completed task history',
    ];
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <!-- ── SECTION 1 : Active Tasks ──────────────────────────────── -->
    <div class="rp-section-header mb-4">
        <div>
            <h2 class="rp-section-title">Active Tasks</h2>
            <p class="rp-section-sub">
                <?php echo $totalTasks ?> task<?php echo $totalTasks != 1 ? 's' : '' ?> &bull;
                Pending: <?php echo $pending ?> &bull;
                In Progress: <?php echo $inProgress ?>
            </p>
        </div>
        <?php
        $config = [
            'table_selector' => '.tasks-report-table',
            'report_title'   => 'Active Tasks Report',
            'report_subtitle'=> 'Current assigned service tasks',
            'columns'        => ['Task', 'Customer', 'Date', 'Status'],
            'btn_id'         => 'btn-tasks-pdf',
        ];
        require APPROOT . '/views/inc/components/download_report_btn.php';
        ?>
    </div>

    <div class="card shadow-lg rounded-xl mb-6">
        <div class="card-body p-0">
            <?php if (!empty($mappedTasks)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 tasks-report-table">
                        <thead class="table-light">
                            <tr>
                                <th>Task</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mappedTasks as $t): ?>
                            <?php
                            $statusClass = match($t['status']) {
                                'Completed'  => 'badge-success',
                                'In Progress'=> 'badge-primary',
                                default      => 'badge-warning',
                            };
                            ?>
                            <tr>
                                <td class="font-semibold"><?php echo htmlspecialchars($t['title']) ?></td>
                                <td><?php echo htmlspecialchars($t['customer']) ?></td>
                                <td><?php echo htmlspecialchars($t['date']) ?></td>
                                <td>
                                    <span class="badge <?php echo $statusClass ?>">
                                        <?php echo htmlspecialchars($t['status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-tasks text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                    <p class="text-secondary">No active tasks found.</p>
                </div>
            <?php endif ?>
        </div>
    </div>

    <!-- ── SECTION 2 : Service Reports ──────────────────────────────── -->
    <div class="rp-section-header mb-4 mt-6">
        <div>
            <h2 class="rp-section-title">Service Reports</h2>
            <p class="rp-section-sub">
                <?php echo $totalHistory ?> service report<?php echo $totalHistory != 1 ? 's' : '' ?>
            </p>
        </div>
        <?php
        $config = [
            'table_selector' => '.history-report-table',
            'report_title'   => 'Service Reports',
            'report_subtitle'=> 'Completed tasks and installations reports',
            'columns'        => ['Job', 'Customer', 'Completed Date', 'Actions', 'Final Status', 'Time Spent (Hrs)'],
            'btn_id'         => 'btn-history-pdf',
        ];
        require APPROOT . '/views/inc/components/download_report_btn.php';
        ?>
    </div>

    <div class="card shadow-lg rounded-xl mb-6">
        <div class="card-body p-0">
            <?php if (!empty($mappedHistory)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 history-report-table">
                        <thead class="table-light">
                            <tr>
                                <th>Job</th>
                                <th>Customer</th>
                                <th>Completed Date</th>
                                <th>Actions</th>
                                <th>Status</th>
                                <th>Time (Hrs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mappedHistory as $h): ?>
                            <tr>
                                <td class="font-semibold"><?php echo htmlspecialchars($h['title']) ?></td>
                                <td><?php echo htmlspecialchars($h['customer']) ?></td>
                                <td><?php echo htmlspecialchars($h['date']) ?></td>
                                <td class="text-secondary text-sm"><?php echo htmlspecialchars($h['actions']) ?></td>
                                <td>
                                    <?php
                                    $sClass = match(strtolower(trim($h['final_status']))) {
                                        'resolved', 'success', 'completed', 'done' => 'badge-success',
                                        default => 'badge-info',
                                    };
                                    ?>
                                    <span class="badge <?php echo $sClass ?>"><?php echo htmlspecialchars($h['final_status']) ?></span>
                                </td>
                                <td class="text-center"><?php echo htmlspecialchars($h['time_spent']) ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-file-contract text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                    <p class="text-secondary">No service reports found.</p>
                </div>
            <?php endif ?>
        </div>
    </div>

</div>

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>
