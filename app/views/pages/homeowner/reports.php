<?php
$chartData      = $data['chart_data']      ?? [];
$smsHistory     = $data['sms_history']     ?? [];
$serviceHistory = $data['service_history'] ?? [];
$selectedYear   = $data['selected_year']   ?? (int)date('Y');
$availYears     = $data['available_years'] ?? [(int)date('Y')];

// Build chart arrays (all 12 months, padded)
$allMonths    = [];
for ($m = 1; $m <= 12; $m++) {
    $allMonths[] = date('M Y', mktime(0, 0, 0, $m, 1, $selectedYear));
}
$dataMap = [];
foreach ($chartData as $row) {
    $dataMap[$row->label] = $row;
}
$chartLabels   = $allMonths;
$chartActual   = [];
$chartExpected = [];
$chartBill     = [];
$chartImport   = [];
foreach ($allMonths as $lbl) {
    $row = $dataMap[$lbl] ?? null;
    $chartActual[]   = $row ? (float)($row->grid_export        ?? 0) : null;
    $chartExpected[] = $row ? (float)($row->expected_generation ?? 0) : null;
    $chartBill[]     = $row ? (float)($row->monthly_bill        ?? 0) : null;
    $chartImport[]   = $row ? (float)($row->grid_import         ?? 0) : null;
}

// System health from latest real row
$latest = !empty($chartData) ? end($chartData) : null;
$latestActual   = $latest ? (float)($latest->grid_export        ?? 0) : 0;
$latestExpected = $latest ? (float)($latest->expected_generation ?? 0) : 0;
$perfPct = ($latestExpected > 0) ? min(100, round(($latestActual / $latestExpected) * 100)) : 0;

if ($perfPct >= HEALTH_EXCELLENT_THRESHOLD)   { $healthLabel = HEALTH_STATUS_EXCELLENT; $healthColor = HEALTH_COLOR_EXCELLENT; }
elseif ($perfPct >= HEALTH_GOOD_THRESHOLD)    { $healthLabel = HEALTH_STATUS_GOOD;      $healthColor = HEALTH_COLOR_GOOD; }
elseif ($perfPct >= HEALTH_WARNING_THRESHOLD) { $healthLabel = HEALTH_STATUS_WARNING;   $healthColor = HEALTH_COLOR_WARNING; }
else                                          { $healthLabel = HEALTH_STATUS_CRITICAL;  $healthColor = HEALTH_COLOR_CRITICAL; }

// SMS summary stats
$totalSMSRows   = count($smsHistory);
$totalBillAmt   = array_sum(array_map(fn($r) => (float)$r->monthly_bill, $smsHistory));
$totalConsumption = array_sum(array_map(fn($r) => (float)$r->consumption_units, $smsHistory));
?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/css/pages/homeowner/service.css">
<link rel="stylesheet" href="<?php echo URLROOT ?>/css/pages/homeowner/reports.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-area">

    <?php
    $config = [
        'title'       => 'Reports',
        'description' => 'Solar performance, billing history and service records',
    ];
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <!-- Year filter -->
    <div class="rp-year-bar mb-6">
        <span class="rp-year-label"><i class="fas fa-calendar-alt mr-2"></i>Report Year</span>
        <form method="GET" action="" class="d-flex align-center gap-2">
            <select name="year" onchange="this.form.submit()" class="rp-year-select">
                <?php foreach ($availYears as $yr): ?>
                    <option value="<?php echo $yr ?>" <?php echo $yr == $selectedYear ? 'selected' : '' ?>><?php echo $yr ?></option>
                <?php endforeach ?>
            </select>
        </form>
    </div>

    <!-- ── SECTION 1 : System Performance ───────────────────────────── -->
    <div class="rp-section-header mb-4">
        <div>
            <h2 class="rp-section-title">System Performance</h2>
            <p class="rp-section-sub">Actual vs. expected solar generation for <?php echo $selectedYear ?></p>
        </div>
        <button class="btn btn-sm btn-outline-primary rounded-lg d-flex align-center gap-2"
                onclick="SolarSenseReport.download({tableSelector:'#perfTable',title:'System Performance <?php echo $selectedYear ?>',subtitle:'Monthly actual vs expected generation',columns:['Month','Actual Export (kWh)','Expected (kWh)','Grid Import (kWh)','Bill (Rs.)']}, this)"
                type="button">
            <i class="fas fa-file-pdf"></i> Download PDF
        </button>
    </div>

    <div class="row mb-6">
        <div class="col-lg-8">
            <div class="card shadow-lg rounded-xl h-100">
                <div class="card-body">
                    <h3 class="card-title text-lg font-semibold mb-4">Monthly Generation — <?php echo $selectedYear ?></h3>
                    <div style="position:relative;height:280px;">
                        <canvas id="genChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-lg rounded-xl h-100">
                <div class="card-body">
                    <h3 class="card-title text-lg font-semibold mb-4">System Health</h3>

                    <div class="rp-health-pct" style="color:<?php echo $healthColor ?>">
                        <?php echo $perfPct ?>%
                    </div>
                    <div class="rp-health-bar-wrap">
                        <div class="rp-health-bar" style="width:<?php echo $perfPct ?>%;background:<?php echo $healthColor ?>;"></div>
                    </div>
                    <p class="text-center text-sm mt-2 font-semibold" style="color:<?php echo $healthColor ?>"><?php echo $healthLabel ?></p>

                    <div class="rp-stat-pair mt-4">
                        <div class="rp-mini-stat">
                            <div class="rp-mini-val"><?php echo number_format($latestActual, 1) ?></div>
                            <div class="rp-mini-lbl">Actual kWh</div>
                        </div>
                        <div class="rp-mini-stat">
                            <div class="rp-mini-val"><?php echo number_format($latestExpected, 1) ?></div>
                            <div class="rp-mini-lbl">Expected kWh</div>
                        </div>
                    </div>

                    <p class="text-xs text-secondary mt-4" style="text-align:center;">
                        Thresholds — Critical &lt;<?php echo HEALTH_WARNING_THRESHOLD ?>%
                        &bull; Warning &lt;<?php echo HEALTH_GOOD_THRESHOLD ?>%
                        &bull; Excellent &ge;<?php echo HEALTH_EXCELLENT_THRESHOLD ?>%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden table for performance PDF -->
    <table id="perfTable" style="display:none;" aria-hidden="true">
        <thead>
            <tr>
                <th>Month</th><th>Actual Export (kWh)</th><th>Expected (kWh)</th><th>Grid Import (kWh)</th><th>Bill (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chartData as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row->label) ?></td>
                <td><?php echo number_format((float)($row->grid_export ?? 0), 1) ?></td>
                <td><?php echo number_format((float)($row->expected_generation ?? 0), 1) ?></td>
                <td><?php echo number_format((float)($row->grid_import ?? 0), 1) ?></td>
                <td><?php echo number_format((float)($row->monthly_bill ?? 0), 2) ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <!-- ── SECTION 2 : Bill & Consumption History (SMS) ─────────────── -->
    <div class="rp-section-header mb-4 mt-6">
        <div>
            <h2 class="rp-section-title">Bill &amp; Consumption History</h2>
            <p class="rp-section-sub"><?php echo $totalSMSRows ?> records &bull; Total billed: Rs. <?php echo number_format($totalBillAmt, 2) ?> &bull; Total consumption: <?php echo number_format($totalConsumption) ?> Units</p>
        </div>
        <?php
        $config = [
            'table_selector' => '.sms-hist-table',
            'report_title'   => 'CEB Billing History',
            'report_subtitle'=> 'Complete record of uploaded electricity bill SMS messages',
            'columns'        => ['Reading Date','Export Reading','Import Reading','Consumption (Units)','Monthly Bill (Rs.)'],
            'btn_id'         => 'btn-sms-pdf',
        ];
        require APPROOT . '/views/inc/components/download_report_btn.php';
        ?>
    </div>

    <div class="card shadow-lg rounded-xl mb-6">
        <div class="card-body p-0">
            <?php if (!empty($smsHistory)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 sms-hist-table">
                        <thead class="table-light">
                            <tr>
                                <th>Reading Date</th>
                                <th>Export Reading</th>
                                <th>Import Reading</th>
                                <th>Consumption (Units)</th>
                                <th>Monthly Bill (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($smsHistory as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row->reading_date) ?></td>
                                <td><?php echo htmlspecialchars($row->export_reading) ?></td>
                                <td><?php echo htmlspecialchars($row->import_reading) ?></td>
                                <td><?php echo number_format((int)$row->consumption_units) ?></td>
                                <td class="font-semibold"><?php echo number_format((float)$row->monthly_bill, 2) ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                    <p class="text-secondary">No SMS records uploaded yet.</p>
                </div>
            <?php endif ?>
        </div>
    </div>

    <!-- ── SECTION 3 : Service History ──────────────────────────────── -->
    <div class="rp-section-header mb-4 mt-6">
        <div>
            <h2 class="rp-section-title">Service History</h2>
            <p class="rp-section-sub"><?php echo count($serviceHistory) ?> service request<?php echo count($serviceHistory) !== 1 ? 's' : '' ?> on record</p>
        </div>
        <?php
        $config = [
            'table_selector' => '.service-hist-table',
            'report_title'   => 'Service History',
            'report_subtitle'=> 'Complete record of solar system service requests',
            'columns'        => ['Service ID','Request Date','Service Type','Technician','Status'],
            'btn_id'         => 'btn-service-pdf',
        ];
        require APPROOT . '/views/inc/components/download_report_btn.php';
        ?>
    </div>

    <div class="card shadow-lg rounded-xl mb-6">
        <div class="card-body p-0">
            <?php if (!empty($serviceHistory)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 service-hist-table">
                        <thead class="table-light">
                            <tr>
                                <th>Service ID</th>
                                <th>Request Date</th>
                                <th>Service Type</th>
                                <th>Technician</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($serviceHistory as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row->task_id) ?></td>
                                <td><?php echo htmlspecialchars($row->request_date) ?></td>
                                <td><?php echo htmlspecialchars($row->service_type) ?></td>
                                <td>
                                    <?php if (!empty($row->agent_name)): ?>
                                        <?php echo htmlspecialchars($row->agent_name) ?>
                                    <?php else: ?>
                                        <span class="text-secondary">Not Assigned</span>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = match($row->status) {
                                        'Completed'   => 'badge-success',
                                        'In Progress' => 'badge-primary',
                                        default       => 'badge-warning',
                                    };
                                    ?>
                                    <span class="badge <?php echo $statusClass ?>"><?php echo htmlspecialchars($row->status) ?></span>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-tools text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                    <p class="text-secondary">No service records found.</p>
                </div>
            <?php endif ?>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('genChart');
    if (!ctx) return;

    const labels   = <?php echo json_encode($chartLabels) ?>;
    const actual   = <?php echo json_encode($chartActual) ?>;
    const expected = <?php echo json_encode($chartExpected) ?>;

    new Chart(ctx, {
        data: {
            labels,
            datasets: [
                {
                    type: 'line',
                    label: 'Expected (kWh)',
                    data: expected,
                    borderColor: 'rgba(12,84,163,0.8)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    fill: false,
                    tension: 0.4,
                    pointRadius: 3,
                    spanGaps: true,
                },
                {
                    type: 'bar',
                    label: 'Actual Export (kWh)',
                    data: actual,
                    backgroundColor: 'rgba(254,150,48,0.75)',
                    borderColor: 'rgba(254,150,48,1)',
                    borderWidth: 1,
                    borderRadius: 4,
                    barThickness: 28,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'kWh' } }
            },
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>
