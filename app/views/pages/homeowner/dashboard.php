<?php
define('SOLAR_TARIFF_RATE_LKR', 37.50);

$user_name = "Akash Sandeepa";

$power_cut = [
    'district'   => 'Colombo',
    'start_time' => '2:00 PM',
    'end_time'   => '4:00 PM'
];

$daily_forecast = [
    'temperature'          => 31,
    'condition'            => 'Sunny',
    'estimated_generation' => 22
];

// --- Build chart arrays from DB data ---
$labels              = [];
$actual_generation   = [];
$expected_generation = [];
$grid_import         = [];
$grid_export         = [];
$monthly_bills       = [];

foreach ($chart_data as $row) {
    $labels[]              = $row->label;
    $actual_generation[]   = (float) $row->consumption_units;
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

// --- System health: actual vs expected (last month) ---
$performance_pct = ($latest_expected > 0)
    ? min(100, round(($latest_consumption / $latest_expected) * 100))
    : 0;

// --- Lifetime profit: sum of all grid-export income ---
$total_export_kwh   = array_sum($grid_export);
$total_income_lkr   = $total_export_kwh * SOLAR_TARIFF_RATE_LKR;
$yearly_goal        = 500000; // LKR – adjust as needed
$yearly_goal_pct    = min(100, round(($total_income_lkr / $yearly_goal) * 100));

$system_health   = ['performance_vs_expected' => $performance_pct];
$profit_tracker  = [
    'total_accumulated'   => 'LKR ' . number_format($total_income_lkr),
    'yearly_goal_progress' => $yearly_goal_pct,
];

$stat_metrics = [
    [
        'label' => 'Actual Consumption',
        'value' => number_format($latest_consumption) . ' kWh',
        'icon'  => 'fas fa-solar-panel',
        'color' => 'primary',
        'trend' => ['direction' => 'up', 'percentage' => 0]
    ],
    [
        'label' => 'Grid Export',
        'value' => $latest_export . ' kWh',
        'icon'  => 'fas fa-arrow-up',
        'color' => 'success',
        'trend' => ['direction' => 'up', 'percentage' => 0]
    ],
    [
        'label' => 'Grid Import',
        'value' => $latest_import . ' kWh',
        'icon'  => 'fas fa-arrow-down',
        'color' => 'warning',
        'trend' => ['direction' => 'down', 'percentage' => 0]
    ],
    [
        'label' => 'Monthly Income',
        'value' => 'LKR ' . number_format($monthly_income_lkr),
        'icon'  => 'fas fa-coins',
        'color' => 'accent',
        'trend' => ['direction' => 'up', 'percentage' => 0]
    ],
];

// --- Bar colors based on actual vs expected ---
$bar_colors = [];
foreach ($actual_generation as $i => $actual) {
    $expected = $expected_generation[$i] ?? 0;
    $ratio    = ($expected > 0) ? ($actual / $expected) : 1;
    if ($ratio >= 0.9)       $bar_colors[] = 'rgba(34, 197, 94, 0.7)';
    elseif ($ratio >= 0.75)  $bar_colors[] = 'rgba(245, 158, 11, 0.7)';
    else                     $bar_colors[] = 'rgba(239, 68, 68, 0.7)';
}

$recent_alerts = [
    ['date' => 'July 15th',  'description' => 'Performance 15% below expected for weather conditions.'],
    ['date' => 'June 28th',  'description' => 'High grid import detected during peak sun hours.'],
    ['date' => 'June 22nd',  'description' => 'Inverter efficiency lower than usual. Schedule maintenance.'],
];

$quick_actions = [
    ['label' => 'Request Maintenance',    'url' => URLROOT . '/homeowner/service',          'icon' => 'fas fa-wrench',        'class' => 'btn-secondary'],
    ['label' => 'View Accessories Store', 'url' => URLROOT . '/homeowner/shop',             'icon' => 'fas fa-shopping-cart', 'class' => 'btn-secondary'],
    ['label' => 'Download Report',        'url' => URLROOT . '/homeowner/dashboard/report', 'icon' => 'fas fa-file-pdf',      'class' => 'btn-secondary'],
];

$currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$currentYear  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date('Y');
$startDate    = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$endDate      = isset($_GET['end_date'])   ? $_GET['end_date']   : date('Y-m-t');

$months = [
    1=>'January',2=>'February',3=>'March',4=>'April',
    5=>'May',6=>'June',7=>'July',8=>'August',
    9=>'September',10=>'October',11=>'November',12=>'December'
];

$years = [];
for ($i = 0; $i < 5; $i++) {
    $year = date('Y') - $i;
    $years[$year] = $year;
}
?>


<!-- Link to your custom CSS file for this page -->
<link rel="stylesheet" href="<?php echo URLROOT?>/public/css/pages/homeowner/dashboard.css">
<!-- Include Chart.js for the charts to render -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-area">

    <!-- Page Header with Title and Upload Button -->
    <?php
    $pageHeaderConfig = [
        'title' => 'Good Morning, ' . $user_name . '!',
        'description' => 'Here\'s your solar performance overview.',
        'buttons' => [
            [
                'label' => '+ Upload New SMS',
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
            <!-- Chart Filter Component -->
            <?php
            $filterConfig = [
                'filters' => [
                    [
                        'id' => 'monthFilter',
                        'name' => 'month',
                        'label' => 'Month',
                        'options' => array_map(function($num, $name) use ($currentMonth) {
                            return [
                                'value' => $num,
                                'label' => $name,
                                'selected' => $currentMonth == $num
                            ];
                        }, array_keys($months), $months)
                    ],
                    [
                        'id' => 'yearFilter',
                        'name' => 'year',
                        'label' => 'Year',
                        'options' => array_map(function($year) use ($currentYear) {
                            return [
                                'value' => $year,
                                'label' => $year,
                                'selected' => $currentYear == $year
                            ];
                        }, array_keys($years))
                    ]
                ],
                'buttons' => [
                    [
                        'label' => 'Apply Filters',
                        'icon' => 'fas fa-filter',
                        'class' => 'btn-primary',
                        'type' => 'submit'
                    ]
                ],
                'form_method' => 'GET',
                'auto_submit' => true,
                'reset_on_clear' => true
            ];
            $config = $filterConfig;
            require APPROOT . '/views/inc/components/filter_bar.php';
            ?>

            <!-- Main Performance Chart -->
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-4">
                        Performance: Actual vs. Expected
                        <span class="text-sm text-secondary font-normal">
                            (<?php echo $months[$currentMonth]; ?> <?php echo $currentYear; ?>)
                        </span>
                    </h3>
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
                            <div class="text-5xl font-bold text-white"><?php echo $daily_forecast['temperature']; ?>°C</div>
                            <div class="text-white font-medium"><?php echo $daily_forecast['condition']; ?></div>
                        </div>
                        <i class="fas fa-sun text-6xl text-white opacity-75"></i>
                    </div>
                    <p class="text-white mt-4">Expect to generate approx. <strong><?php echo $daily_forecast['estimated_generation']; ?> kWh</strong> today.</p>
                </div>
            </div>

            <!-- System Health -->
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold">System Health</h3>
                    <p class="text-secondary text-sm mb-2">Performance vs. Expected</p>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: <?php echo $system_health['performance_vs_expected']; ?>%;">
                            <span class="progress-bar-label"><?php echo $system_health['performance_vs_expected']; ?>%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lifetime Profit Tracker -->
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold">Lifetime Profit Tracker</h3>
                    <p class="text-secondary text-sm">Total Accumulated Savings</p>
                    <p class="text-3xl font-bold text-success mb-3"><?php echo $profit_tracker['total_accumulated']; ?></p>
                    <!-- --- NEW DETAIL --- -->
                    <p class="text-secondary text-sm mt-n2 mb-2">This month's contribution: <strong>LKR <?php echo number_format($monthly_income_lkr); ?></strong></p>
                    <div class="progress-bar-container">
                        <div class="progress-bar bg-accent" style="width: <?php echo $profit_tracker['yearly_goal_progress']; ?>%;">
                            <span class="progress-bar-label"><?php echo $profit_tracker['yearly_goal_progress']; ?>% of yearly goal</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions & Recent Alerts -->
    <div class="row mt-6">
        <div class="col-lg-8">
             <div class="card shadow-lg rounded-xl h-100">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-4">Recent Faults & Alerts</h3>
                    <?php foreach($recent_alerts as $alert): ?>
                        <?php require APPROOT . '/views/inc/components/alert_item.php'; ?>
                    <?php endforeach; ?>
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
        // Performance Comparison Chart
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
                            borderColor: 'rgba(12, 84, 163, 1)', // secondary color
                            borderWidth: 2,
                            borderDash: [5, 5], // Makes the line dashed
                            fill: false,
                            tension: 0.4,
                            pointRadius: 0,
                            yAxisID: 'y', // Main axis
                        },
                        {
                            type: 'line',
                            label: 'Grid Import (kWh)',
                            data: <?php echo json_encode($performance_chart_data['grid_import']); ?>,
                            borderColor: 'rgba(239, 68, 68, 1)', // error color
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y1', // Secondary axis
                        },
                        {
                            type: 'bar',
                            label: 'Actual Generation (kWh)',
                            data: <?php echo json_encode($performance_chart_data['actual_generation']); ?>,
                            backgroundColor: <?php echo json_encode($bar_colors); ?>, // Dynamic colors
                            borderColor: 'rgba(254, 150, 48, 1)',
                            borderWidth: 1,
                            borderRadius: 5,
                            yAxisID: 'y', // Main axis
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
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            title: { display: true, text: 'Grid Import (kWh)' },
                            grid: { drawOnChartArea: false } // Hide grid lines for this axis
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
