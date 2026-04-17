<?php
define('SOLAR_TARIFF_RATE_LKR', 37.50);

$user_name = "Akash Sandeepa";

$power_cut = [
    'district'   => 'Colombo',
    'start_time' => '2:00 PM',
    'end_time'   => '4:00 PM'
];

function mapWeatherCodeToIcon(int $code): string
{
    return match ($code) {
        0, 1 => 'fa-sun',
        2, 3 => 'fa-cloud-sun',
        45, 48 => 'fa-smog',
        51, 53, 55, 61, 63, 65, 80, 81, 82 => 'fa-cloud-rain',
        71, 73, 75, 77, 85, 86 => 'fa-snowflake',
        95, 96, 99 => 'fa-bolt',
        default => 'fa-cloud'
    };
}

// --- Build chart arrays from DB data ---
$labels              = [];
$actual_generation   = [];
$expected_generation = [];
$grid_import         = [];
$grid_export         = [];
$monthly_bills       = [];

foreach ($chart_data as $row) {
    $labels[]              = $row->label;
    $actual_generation[]   = (float) $row->grid_export;
    $expected_generation[] = (float) ($row->expected_generation ?? 0);
    $grid_import[]         = (int)   $row->grid_import;
    $grid_export[]         = (int)   $row->grid_export;
    $monthly_bills[]       = (float) $row->monthly_bill;
}

$performance_chart_data = [
    'labels'              => $labels,
    'actual_generation'   => $actual_generation,
    'expected_generation' => $expected_generation,
    'grid_import'         => $grid_import,
];

// --- Stat card values from the most recent SMS row ---
$latest              = !empty($chart_data) ? end($chart_data) : null;
$latest_consumption  = $latest ? (float) $latest->consumption_units       : 0;
$latest_export       = $latest ? (int)   $latest->grid_export             : 0;
$latest_import       = $latest ? (int)   $latest->grid_import             : 0;
$latest_bill         = $latest ? (float) $latest->monthly_bill            : 0;
$latest_expected     = $latest ? (float) ($latest->expected_generation ?? 0) : 0;
$monthly_income_lkr  = $latest_export * SOLAR_TARIFF_RATE_LKR;

// --- System Health: actual generation vs expected (last month) ---
// Actual generation is tracked as grid_export in the SMS table.
$performance_pct = ($latest_expected > 0)
    ? min(100, round(($latest_export / $latest_expected) * 100))
    : 0;

// Classify the percentage against threshold constants from constants.php
if ($performance_pct >= HEALTH_EXCELLENT_THRESHOLD) {
    $health_status = HEALTH_STATUS_EXCELLENT;
    $health_class  = 'health-excellent';
} elseif ($performance_pct >= HEALTH_GOOD_THRESHOLD) {
    $health_status = HEALTH_STATUS_GOOD;
    $health_class  = 'health-good';
} elseif ($performance_pct >= HEALTH_WARNING_THRESHOLD) {
    $health_status = HEALTH_STATUS_WARNING;
    $health_class  = 'health-warning';
} else {
    $health_status = HEALTH_STATUS_CRITICAL;
    $health_class  = 'health-critical';
}

$system_health = [
    'performance_vs_expected' => $performance_pct,
    'status'                  => $health_status,
    'css_class'               => $health_class,
    'actual_kwh'              => $latest_export,
    'expected_kwh'            => $latest_expected,
];

// --- Bar colors based on actual vs expected (driven by health threshold constants) ---
$bar_colors = [];
foreach ($actual_generation as $i => $actual) {
    $expected = $expected_generation[$i] ?? 0;
    $pct      = ($expected > 0) ? (($actual / $expected) * 100) : 0;
    if ($pct >= HEALTH_EXCELLENT_THRESHOLD)      $bar_colors[] = HEALTH_COLOR_EXCELLENT;
    elseif ($pct >= HEALTH_GOOD_THRESHOLD)       $bar_colors[] = HEALTH_COLOR_GOOD;
    elseif ($pct >= HEALTH_WARNING_THRESHOLD)    $bar_colors[] = HEALTH_COLOR_WARNING;
    else                                         $bar_colors[] = HEALTH_COLOR_CRITICAL;
}


// $selected_year and $available_years are injected by the controller
$currentYear = $selected_year ?? (int) date('Y');

$stats = $data['stats'];

$total_export     = $stats->total_export     ?? 0;
$total_import     = $stats->total_import     ?? 0;
$total_generation = $stats->total_generation ?? 0;

$stat_metrics = [
    [
        'label' => 'Total Solar Generation',
        'value' => $total_generation . ' kWh',
        'icon'  => 'fas fa-solar-panel',
        'color' => 'primary'
    ],
    [
        'label' => 'Grid Export',
        'value' => $total_export . ' kWh',
        'icon'  => 'fas fa-arrow-up',
        'color' => 'success'
    ],
    [
        'label' => 'Grid Import',
        'value' => $total_import . ' kWh',
        'icon'  => 'fas fa-arrow-down',
        'color' => 'warning'
    ]
];

// --- Recent Faults & Alerts: auto-generated from SMS chart data ---
// Only shows months where performance vs expected is Critical or Warning.
$recent_alerts = [];
foreach ($chart_data as $row) {
    $row_expected = (float) ($row->expected_generation ?? 0);
    $row_actual   = (float) ($row->grid_export ?? 0);

    if ($row_expected <= 0) continue; // skip rows with no expected value

    $row_pct = min(100, round(($row_actual / $row_expected) * 100));

    if ($row_pct >= HEALTH_WARNING_THRESHOLD) continue; // Good / Excellent → not an alert

    // Classify severity
    if ($row_pct < HEALTH_WARNING_THRESHOLD && $row_pct >= 1) {
        $severity    = ($row_pct < HEALTH_WARNING_THRESHOLD && $row_pct < 50) ? HEALTH_STATUS_CRITICAL : HEALTH_STATUS_WARNING;
        $sev_class   = ($severity === HEALTH_STATUS_CRITICAL) ? 'critical' : 'warning';
    } else {
        $severity  = HEALTH_STATUS_CRITICAL;
        $sev_class = 'critical';
    }

    $deficit_kwh = round($row_expected - $row_actual, 1);
    $reading_dt  = $row->reading_date ?? '';
    $display_date = !empty($reading_dt)
        ? date('M j, Y', strtotime($reading_dt))
        : ($row->label ?? 'Unknown date');

    $recent_alerts[] = [
        'date'        => $display_date,
        'reading_date'=> $reading_dt,
        'description' => "System performance at {$row_pct}% of expected generation ({$severity}). Deficit: {$deficit_kwh} kWh.",
        'severity'    => $severity,
        'sev_class'   => $sev_class,
        'pct'         => $row_pct,
    ];
}

// Sort most recent first, cap at 5 entries
usort($recent_alerts, fn($a, $b) => strcmp($b['reading_date'], $a['reading_date']));
$recent_alerts = array_slice($recent_alerts, 0, 5);

$quick_actions = [
    ['label' => 'Request Maintenance',    'url' => URLROOT . '/homeowner/service',          'icon' => 'fas fa-wrench',        'class' => 'btn-secondary'],
    ['label' => 'View Accessories Store', 'url' => URLROOT . '/homeowner/shop',             'icon' => 'fas fa-shopping-cart', 'class' => 'btn-secondary'],
    ['label' => 'Download Report',        'url' => URLROOT . '/homeowner/dashboard/report', 'icon' => 'fas fa-file-pdf',      'class' => 'btn-secondary'],
];
?>


<!-- Link to your custom CSS file for this page -->
<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/pages/homeowner/dashboard.css">
<!-- Include Chart.js for the charts to render -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-area">

    <!-- Page Header with Title and Upload Button -->
    <?php
    $pageHeaderConfig = [
        'title' => 'Good Morning, ' . $_SESSION['user_name'] . '!',
        'description' => 'Here\'s your solar performance overview.',
        'buttons' => [
            [
                'label' => 'Upload New SMS',
                'url' => URLROOT . '/homeowner/dashboard/uploadsms',
                'icon' => 'fas fa-cloud-upload-alt',
                'class' => 'btn-primary'
            ]
        ]
    ];
    $config = $pageHeaderConfig;
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <!-- Key Metrics - Using Stat Card Component -->
    <?php
    $statConfig = [
        'stats' => $stat_metrics,
        'columns' => 4
    ];
    $config = $statConfig;
    require APPROOT . '/views/inc/components/stat_card.php';
    ?>

    <!-- Main Grid: Charts, Health, and Financials -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Main Performance Chart -->
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <!-- Chart header: title + inline year filter -->
                    <div class="d-flex align-center justify-between mb-4" style="flex-wrap:wrap;gap:0.75rem;">
                        <h3 class="card-title text-2xl font-semibold" style="margin:0;">
                            Performance: Actual vs. Expected
                            <span class="text-sm text-secondary font-normal">(<?php echo $currentYear; ?>)</span>
                        </h3>
                        <form method="GET" action="" class="d-flex align-center gap-2" style="gap:0.5rem;">
                            <label for="yearFilter" class="text-sm font-medium" style="white-space:nowrap;">Year:</label>
                            <select id="yearFilter" name="year"
                                    onchange="this.form.submit()"
                                    style="padding:0.35rem 0.6rem;border-radius:6px;border:1px solid var(--border-color,#ccc);font-size:0.9rem;background:var(--card-bg,#fff);color:inherit;">
                                <?php foreach ($available_years as $yr): ?>
                                    <option value="<?php echo $yr; ?>" <?php echo ($yr == $currentYear) ? 'selected' : ''; ?>>
                                        <?php echo $yr; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                    <div class="chart-container">
                        <canvas id="performanceComparisonChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Daily Solar Forecast -->
            <div class="card shadow-lg rounded-xl mb-6 forecast-card">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold text-white">Today's Forecast</h3>
                    <div class="d-flex align-center justify-between">
                        <div>
                            <div class="text-5xl font-bold text-white">
                                <?php echo $daily_forecast['temperature']; ?>°C
                            </div>
                            <div class="text-white font-medium">
                                <?php echo htmlspecialchars($daily_forecast['condition']); ?>
                            </div>
                            <div class="text-white opacity-75 mt-2">
                                Max: <?php echo round($daily_forecast['temp_max']); ?>°C |
                                Min: <?php echo round($daily_forecast['temp_min']); ?>°C
                            </div>
                            <div class="text-white opacity-75">
                                Solar: <?php echo round($daily_forecast['solar_radiation_mj'], 2); ?> MJ/m²
                            </div>
                        </div>
                        <i class="fas fa-sun text-6xl text-white opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- System Health -->
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <div class="d-flex align-center justify-between mb-1">
                        <h3 class="card-title text-xl font-semibold" style="margin:0;">System Health</h3>
                        <span class="health-status-badge health-badge-<?php echo $system_health['css_class']; ?>">
                            <?php echo $system_health['status']; ?>
                        </span>
                    </div>
                    <p class="text-secondary text-sm mb-3">Actual vs. Expected Generation &mdash; Last Month</p>
                    <div class="d-flex justify-between text-sm mb-2">
                        <span class="text-secondary">Actual: <strong><?php echo number_format($system_health['actual_kwh'], 1); ?> kWh</strong></span>
                        <span class="text-secondary">Expected: <strong><?php echo number_format($system_health['expected_kwh'], 1); ?> kWh</strong></span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar <?php echo $system_health['css_class']; ?>"
                            style="width: <?php echo $system_health['performance_vs_expected']; ?>%;">
                            <span class="progress-bar-label"><?php echo $system_health['performance_vs_expected']; ?>%</span>
                        </div>
                    </div>
                    <p class="text-xs text-secondary mt-2">Thresholds: Critical &lt;<?php echo HEALTH_WARNING_THRESHOLD; ?>% &bull; Warning &lt;<?php echo HEALTH_GOOD_THRESHOLD; ?>% &bull; Good &lt;<?php echo HEALTH_EXCELLENT_THRESHOLD; ?>% &bull; Excellent &ge;90%</p>
                </div>
            </div>

            <!-- Lifetime Profit Tracker -->
            <!-- <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold">Lifetime Profit Tracker</h3>
                    <p class="text-secondary text-sm">Total Accumulated Savings</p>
                    <p class="text-3xl font-bold text-success mb-3"><?php echo $profit_tracker['total_accumulated']; ?></p>
                    <p class="text-secondary text-sm mt-n2 mb-2">This month's contribution: <strong>LKR <?php echo number_format($monthly_income_lkr); ?></strong></p>
                    <div class="progress-bar-container">
                        <div class="progress-bar bg-accent" style="width: <?php echo $profit_tracker['yearly_goal_progress']; ?>%;">
                            <span class="progress-bar-label"><?php echo $profit_tracker['yearly_goal_progress']; ?>% of yearly goal</span>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>

    <!-- Quick Actions & Recent Alerts -->
    <div class="row mt-6">
        <div class="col-lg-8">
            <div class="card shadow-lg rounded-xl h-100">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-4">Recent Faults &amp; Alerts</h3>
                    <?php if (empty($recent_alerts)): ?>
                        <div class="d-flex align-center gap-3 py-4">
                            <i class="fas fa-check-circle text-success text-2xl"></i>
                            <div>
                                <div class="font-semibold text-success">All Systems Performing Well</div>
                                <div class="text-sm text-secondary">No critical or warning alerts for the selected year.</div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recent_alerts as $alert): ?>
                            <div class="alert-item d-flex align-center py-3">
                                <?php if ($alert['sev_class'] === 'critical'): ?>
                                    <i class="fas fa-times-circle text-xl mr-4" style="color:#dc2626;flex-shrink:0;"></i>
                                <?php else: ?>
                                    <i class="fas fa-exclamation-triangle text-xl mr-4" style="color:#d97706;flex-shrink:0;"></i>
                                <?php endif; ?>
                                <div class="flex-1">
                                    <div class="font-semibold text-sm" style="color:<?php echo ($alert['sev_class'] === 'critical') ? '#dc2626' : '#d97706'; ?>">
                                        <?php echo htmlspecialchars($alert['severity']); ?> &mdash; <?php echo htmlspecialchars($alert['pct']); ?>% of Expected Generation
                                    </div>
                                    <div class="text-sm text-secondary"><?php echo htmlspecialchars($alert['description']); ?></div>
                                </div>
                                <div class="text-xs text-muted ml-3" style="white-space:nowrap;"><?php echo htmlspecialchars($alert['date']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-lg rounded-xl h-100">
                <div class="card-body d-flex flex-column justify-around gap-2">
                    <?php foreach ($quick_actions as $action): ?>
                        <a href="<?php echo htmlspecialchars($action['url']); ?>"
                            class="btn <?php echo htmlspecialchars($action['class']); ?> btn-lg btn-block">
                            <i class="<?php echo htmlspecialchars($action['icon']); ?> mr-2"></i>
                            <?php echo htmlspecialchars($action['label']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const performanceCtx = document.getElementById('performanceComparisonChart');
        if (performanceCtx) {
            new Chart(performanceCtx, {
                data: {
                    labels: <?php echo json_encode($performance_chart_data['labels']); ?>,
                    datasets: [
                        {
                            type: 'line',
                            label: 'Expected Generation (kWh)',
                            data: <?php echo json_encode($performance_chart_data['expected_generation']); ?>,
                            borderColor: 'rgba(12, 84, 163, 1)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            fill: false,
                            tension: 0.4,
                            pointRadius: 3,
                            yAxisID: 'y',
                        },
                        {
                            type: 'bar',
                            label: 'Actual Generation (kWh)',
                            data: <?php echo json_encode($performance_chart_data['actual_generation']); ?>,
                            backgroundColor: <?php echo json_encode($bar_colors); ?>,
                            borderColor: 'rgba(254, 150, 48, 1)',
                            borderWidth: 1,
                            borderRadius: 5,
                            yAxisID: 'y',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            title: { display: true, text: 'Energy Generation (kWh)' }
                        }
                    },
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
    });
</script>