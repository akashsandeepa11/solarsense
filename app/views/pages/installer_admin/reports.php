<?php
$customers = $data['customers'] ?? [];
$agents    = $data['agents']    ?? [];

$totalCustomers    = count($customers);
$totalAgents       = count($agents);
$activeAgents      = count(array_filter($agents, fn($a) => strtolower($a->agent_status ?? '') === 'active'));
$totalAssigned     = array_sum(array_map(fn($a) => (int)($a->assigned_tasks  ?? 0), $agents));
$totalCompleted    = array_sum(array_map(fn($a) => (int)($a->completed_tasks ?? 0), $agents));
?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/css/pages/homeowner/service.css">
<link rel="stylesheet" href="<?php echo URLROOT ?>/css/pages/homeowner/reports.css">

<div class="content-area">

    <?php
    $config = [
        'title'       => 'Reports',
        'description' => 'Customer fleet and service team reports',
    ];
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <table border=1>
        <tr>
            <th>name</th>
            <th>Des</th>
        </tr>
        <?php foreach ($data['items'] as $item): ?>
            <tr>
                <td><?php echo $item->item_name?></td>
                <td><?php echo $item->description?></td>
            </tr>
        <?php endforeach?>
    </table>

    <table border=1>
        <tr>
            <th>name</th>
            <th>Des</th>
        </tr>
        <?php foreach ($data['hehe'] as $hehe): ?>
            <tr>
                <td><?php echo $hehe->date?></td>
                <td><?php echo $hehe->total_amount?></td>
            </tr>
        <?php endforeach?>
    </table>

    <!-- Filter & Download Bar -->
    <div class="filter-bar">
        <div class="filter-group">
            <label class="filter-label">Report Period</label>
            <select class="filter-select" id="period-filter">
                <option value="">All Periods</option>
                <option value="last_7">Last 7 Days</option>
                <option value="last_30">Last 30 Days</option>
                <option value="last_90">Last 90 Days</option>
                <option value="yearly">This Year</option>
                <option value="all_time">All Time</option>
            </select>
    <!-- ── SECTION 1 : Customer Fleet ────────────────────────────── -->
    <div class="rp-section-header mb-4">
        <div>
            <h2 class="rp-section-title">Customer Fleet</h2>
            <p class="rp-section-sub">
                <?php echo $totalCustomers ?> registered customer<?php echo $totalCustomers != 1 ? 's' : '' ?>
            </p>
        </div>
        <?php
        $config = [
            'table_selector' => '.customers-report-table',
            'report_title'   => 'Customer Fleet Report',
            'report_subtitle'=> 'All customers with system and last reading info',
            'columns'        => ['Customer', 'District', 'Capacity (kW)', 'Last SMS Upload'],
            'btn_id'         => 'btn-customers-pdf',
        ];
        require APPROOT . '/views/inc/components/download_report_btn.php';
        ?>
    </div>

    <div class="card shadow-lg rounded-xl mb-6">
        <div class="card-body p-0">
            <?php if (!empty($customers)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 customers-report-table">
                        <thead class="table-light">
                            <tr>
                                <th>Customer</th>
                                <th>District</th>
                                <th>Capacity (kW)</th>
                                <th>Last SMS Upload</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $c): ?>
                            <tr>
                                <td class="font-semibold"><?php echo htmlspecialchars($c['name'] ?? '—') ?></td>
                                <td><?php echo htmlspecialchars($c['location'] ?? '—') ?></td>
                                <td><?php echo htmlspecialchars($c['size'] ?? '—') ?></td>
                                <td>
                                    <?php
                                    $upload = $c['last_upload'] ?? 'No readings yet';
                                    echo $upload === 'No readings yet'
                                        ? '<span class="text-secondary text-sm">' . $upload . '</span>'
                                        : htmlspecialchars($upload);
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-users text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                    <p class="text-secondary">No customers found.</p>
                </div>
            <?php endif ?>
        </div>
    </div>

    <!-- ── SECTION 2 : Service Team ──────────────────────────────── -->
    <div class="rp-section-header mb-4 mt-6">
        <div>
            <h2 class="rp-section-title">Service Team</h2>
            <p class="rp-section-sub">
                <?php echo $totalAgents ?> agent<?php echo $totalAgents != 1 ? 's' : '' ?> &bull;
                Active: <?php echo $activeAgents ?> &bull;
                Tasks assigned: <?php echo $totalAssigned ?> &bull;
                Completed: <?php echo $totalCompleted ?>
            </p>
        </div>
        <?php
        $config = [
            'table_selector' => '.agents-report-table',
            'report_title'   => 'Service Team Report',
            'report_subtitle'=> 'Agent performance and task summary',
            'columns'        => ['Agent', 'Email', 'Contact', 'Status', 'Assigned', 'Completed', 'Pending'],
            'btn_id'         => 'btn-agents-pdf',
        ];
        require APPROOT . '/views/inc/components/download_report_btn.php';
        ?>
    </div>

    <div class="card shadow-lg rounded-xl mb-6">
        <div class="card-body p-0">
            <?php if (!empty($agents)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 agents-report-table">
                        <thead class="table-light">
                            <tr>
                                <th>Agent</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Assigned</th>
                                <th>Completed</th>
                                <th>Pending</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($agents as $a): ?>
                            <?php
                            $status      = $a->agent_status ?? 'unknown';
                            $statusClass = strtolower($status) === 'active' ? 'badge-success' : 'badge-warning';
                            ?>
                            <tr>
                                <td class="font-semibold"><?php echo htmlspecialchars($a->full_name ?? '—') ?></td>
                                <td><?php echo htmlspecialchars($a->email ?? '—') ?></td>
                                <td><?php echo htmlspecialchars($a->contact ?? '—') ?></td>
                                <td>
                                    <span class="badge <?php echo $statusClass ?>">
                                        <?php echo htmlspecialchars(ucfirst($status)) ?>
                                    </span>
                                </td>
                                <td><?php echo (int)($a->assigned_tasks  ?? 0) ?></td>
                                <td><?php echo (int)($a->completed_tasks ?? 0) ?></td>
                                <td><?php echo (int)($a->pending_tasks   ?? 0) ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-hard-hat text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                    <p class="text-secondary">No service agents found.</p>
                </div>
            <?php endif ?>
        </div>
    </div>

</div>

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>
