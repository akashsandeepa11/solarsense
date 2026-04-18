<?php
$reportData    = $data['report_data']    ?? [];
$statCards     = $reportData['platform_overview'] ?? null;
$companies     = $reportData['companies_report']  ?? [];
$verifications = $reportData['verifications']     ?? [];
$userRoles     = $reportData['user_role_data']    ?? [];

$totalCompanies   = count($companies);
$totalVerifs      = count($verifications);
$pendingVerifs    = count(array_filter($verifications, fn($r) => strtolower(is_object($r) ? $r->status : $r['status']) === 'pending'));
$verifiedCount    = $totalVerifs - $pendingVerifs;
?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/css/pages/homeowner/service.css">
<link rel="stylesheet" href="<?php echo URLROOT ?>/css/pages/homeowner/reports.css">

<div class="content-area">

    <?php
    $config = [
        'title'       => 'Reports',
        'description' => 'Platform-wide company and verification reports',
    ];
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <!-- ── SECTION 1 : Companies ─────────────────────────────────── -->
    <div class="rp-section-header mb-4">
        <div>
            <h2 class="rp-section-title">Companies</h2>
            <p class="rp-section-sub">
                <?php echo $totalCompanies ?> registered compan<?php echo $totalCompanies != 1 ? 'ies' : 'y' ?>
            </p>
        </div>
        <?php
        $config = [
            'table_selector' => '.companies-report-table',
            'report_title'   => 'Companies Report',
            'report_subtitle'=> 'All registered installer companies',
            'columns'        => ['Company', 'Email', 'Address', 'Clients', 'Employees'],
            'btn_id'         => 'btn-companies-pdf',
        ];
        require APPROOT . '/views/inc/components/download_report_btn.php';
        ?>
    </div>

    <div class="card shadow-lg rounded-xl mb-6">
        <div class="card-body p-0">
            <?php if (!empty($companies)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 companies-report-table">
                        <thead class="table-light">
                            <tr>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Clients</th>
                                <th>Employees</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($companies as $c):
                                $name    = is_object($c) ? ($c->name       ?? $c->company_name ?? '—') : ($c['name']       ?? $c['company_name'] ?? '—');
                                $email   = is_object($c) ? ($c->email      ?? '—') : ($c['email']      ?? '—');
                                $address = is_object($c) ? ($c->address    ?? '—') : ($c['address']    ?? '—');
                                $clients = is_object($c) ? ($c->active_clients ?? '0') : ($c['active_clients'] ?? '0');
                                $emps    = is_object($c) ? ($c->active_agents  ?? '0') : ($c['active_agents']  ?? '0');
                            ?>
                            <tr>
                                <td class="font-semibold"><?php echo htmlspecialchars($name) ?></td>
                                <td><?php echo htmlspecialchars($email) ?></td>
                                <td><?php echo htmlspecialchars($address) ?></td>
                                <td><?php echo htmlspecialchars($clients) ?></td>
                                <td><?php echo htmlspecialchars($emps) ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-building text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                    <p class="text-secondary">No companies found.</p>
                </div>
            <?php endif ?>
        </div>
    </div>

    <!-- ── SECTION 2 : Verifications ─────────────────────────────── -->
    <div class="rp-section-header mb-4 mt-6">
        <div>
            <h2 class="rp-section-title">Verification Requests</h2>
            <p class="rp-section-sub">
                <?php echo $totalVerifs ?> request<?php echo $totalVerifs != 1 ? 's' : '' ?> &bull;
                Pending: <?php echo $pendingVerifs ?> &bull;
                Verified: <?php echo $verifiedCount ?>
            </p>
        </div>
        <?php
        $config = [
            'table_selector' => '.verifications-report-table',
            'report_title'   => 'Verification Requests Report',
            'report_subtitle'=> 'Company registration and verification status',
            'columns'        => ['Company', 'Email', 'Contact', 'Address', 'Submitted', 'Status'],
            'btn_id'         => 'btn-verifications-pdf',
        ];
        require APPROOT . '/views/inc/components/download_report_btn.php';
        ?>
    </div>

    <div class="card shadow-lg rounded-xl mb-6">
        <div class="card-body p-0">
            <?php if (!empty($verifications)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 verifications-report-table">
                        <thead class="table-light">
                            <tr>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Submitted</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($verifications as $v):
                                $vName    = is_object($v) ? ($v->company_name  ?? '—') : ($v['company_name']  ?? '—');
                                $vEmail   = is_object($v) ? ($v->email         ?? '—') : ($v['email']         ?? '—');
                                $vPhone   = is_object($v) ? ($v->contact       ?? '—') : ($v['contact']       ?? '—');
                                $vAddr    = is_object($v) ? ($v->address       ?? '—') : ($v['address']       ?? '—');
                                $vDate    = is_object($v) ? ($v->request_date  ?? '—') : ($v['request_date']  ?? '—');
                                $vStatus  = is_object($v) ? ($v->status        ?? '—') : ($v['status']        ?? '—');
                                $badgeCls = strtolower($vStatus) === 'pending' ? 'badge-warning' : 'badge-success';
                            ?>
                            <tr>
                                <td class="font-semibold"><?php echo htmlspecialchars($vName) ?></td>
                                <td><?php echo htmlspecialchars($vEmail) ?></td>
                                <td><?php echo htmlspecialchars($vPhone) ?></td>
                                <td><?php echo htmlspecialchars($vAddr) ?></td>
                                <td><?php echo htmlspecialchars($vDate) ?></td>
                                <td>
                                    <span class="badge <?php echo $badgeCls ?>">
                                        <?php echo htmlspecialchars(ucfirst($vStatus)) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                    <p class="text-secondary">No verification requests found.</p>
                </div>
            <?php endif ?>
        </div>
    </div>

</div>

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>
