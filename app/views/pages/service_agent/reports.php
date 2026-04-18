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
    $mappedHistory[] = [
        'title'    => $h->title    ?? 'Untitled',
        'customer' => $h->customer ?? 'N/A',
        'address'  => $h->address  ?? 'N/A',
        'date'     => $h->date     ?? 'N/A',
        'notes'    => $h->notes    ?? '',
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

    <!-- ── SECTION 2 : Task History ──────────────────────────────── -->
    <div class="rp-section-header mb-4 mt-6">
        <div>
            <h2 class="rp-section-title">Task History</h2>
            <p class="rp-section-sub">
                <?php echo $totalHistory ?> completed task<?php echo $totalHistory != 1 ? 's' : '' ?>
            </p>
        </div>
        <?php
        $config = [
            'table_selector' => '.history-report-table',
            'report_title'   => 'Task History Report',
            'report_subtitle'=> 'Completed service task history',
            'columns'        => ['Task', 'Customer', 'Address', 'Date', 'Notes'],
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
                                <th>Task</th>
                                <th>Customer</th>
                                <th>Address</th>
                                <th>Date</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mappedHistory as $h): ?>
                            <tr>
                                <td class="font-semibold"><?php echo htmlspecialchars($h['title']) ?></td>
                                <td><?php echo htmlspecialchars($h['customer']) ?></td>
                                <td><?php echo htmlspecialchars($h['address']) ?></td>
                                <td><?php echo htmlspecialchars($h['date']) ?></td>
                                <td class="text-secondary text-sm"><?php echo htmlspecialchars($h['notes'] ?: '—') ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-history text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                    <p class="text-secondary">No completed tasks in history.</p>
                </div>
            <?php endif ?>
        </div>
    </div>

</div>

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>
