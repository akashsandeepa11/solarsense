<?php

$currentYear = $data['selected_year'];
// --- Define the solar tariff rate ---
define('SOLAR_TARIFF_RATE_LKR', 37.50); // LKR 37.50 per kWh exported

// User Info
$user_name = "Akash Sandeepa";

// Daily Solar Forecast
$daily_forecast = [
    'temperature' => 31, // Celsius
    'condition' => 'Sunny',
    'estimated_generation' => 22 // kWh
];

// Monthly Performance Summary (from last SMS)
$grid_export_kwh = 310;
$monthly_income_lkr = $grid_export_kwh * SOLAR_TARIFF_RATE_LKR;

$stats = $data['stats'];

$total_export = $stats->total_export ?? 0;
$total_import = $stats->total_import ?? 0;
$total_generation = $stats->total_generation ?? 0;

$stat_metrics = [
    [
        'label' => 'Total Solar Generation',
        'value' => $total_generation . ' kWh',
        'icon' => 'fas fa-solar-panel',
        'color' => 'primary'
    ],
    [
        'label' => 'Grid Export',
        'value' => $total_export . ' kWh',
        'icon' => 'fas fa-arrow-up',
        'color' => 'success'
    ],
    [
        'label' => 'Grid Import',
        'value' => $total_import . ' kWh',
        'icon' => 'fas fa-arrow-down',
        'color' => 'warning'
    ]
];

// System Health & Financials
$system_health = [
    'performance_vs_expected' => 98, // Percentage
];
$profit_tracker = [
    'total_accumulated' => 'LKR 145,750',
    'yearly_goal_progress' => 78 // Percentage
];

$performance_chart_data = [
    'labels' => $data['chart_labels'],
    'actual_generation' => $data['chart_generation'],
    'expected_generation' => [430, 470, 465, 500, 495, 455], // will be replaced below
];

/// --- Ensure 12 months of data for charts ---
$actual_generation = [];
$expected_generation_raw = $data['expected_generation'] ?? array_fill(0, 12, 0);
$expected_generation = [];
$chart_labels = [];

for ($month = 1; $month <= 12; $month++) {
    $yearMonthKey = $currentYear . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
    $chart_labels[] = DateTime::createFromFormat('!m', $month)->format('M');
    $actual_generation[] = $data['chart_generation'][$yearMonthKey] ?? 0;
    $expected_generation[] = $expected_generation_raw[$month - 1] ?? 0;
}

$performance_chart_data['labels'] = $chart_labels;
$performance_chart_data['actual_generation'] = $actual_generation;
$performance_chart_data['expected_generation'] = $expected_generation;

// Bar color logic — unchanged, works on real expected values now
$bar_colors = [];
for ($i = 0; $i < count($performance_chart_data['actual_generation']); $i++) {
    $actual = $performance_chart_data['actual_generation'][$i];
    $expected = $performance_chart_data['expected_generation'][$i];

    $performance_ratio = ($expected != 0) ? ($actual / $expected) : 0;

    if ($performance_ratio >= 0.9) {
        $bar_colors[] = 'rgba(34, 197, 94, 0.7)';
    } elseif ($performance_ratio >= 0.75) {
        $bar_colors[] = 'rgba(245, 158, 11, 0.7)';
    } else {
        $bar_colors[] = 'rgba(239, 68, 68, 0.7)';
    }
}

// Recent Faults & Alerts
$recent_alerts = [
    ['date' => 'July 15th', 'description' => 'Performance 15% below expected for weather conditions.'],
    ['date' => 'June 28th', 'description' => 'High grid import detected during peak sun hours.'],
    ['date' => 'June 22nd', 'description' => 'Inverter efficiency lower than usual. Schedule maintenance.'],
];

// Quick Action Buttons
$quick_actions = [
    [
        'label' => 'Request Maintenance',
        'url' => URLROOT . '/homeowner/service',
        'icon' => 'fas fa-wrench',
        'class' => 'btn-secondary'
    ],
    [
        'label' => 'View Accessories Store',
        'url' => URLROOT . '/homeowner/shop',
        'icon' => 'fas fa-shopping-cart',
        'class' => 'btn-secondary'
    ],
    [
        'label' => 'Download Report',
        'url' => URLROOT . '/homeowner/dashboard/report',
        'icon' => 'fas fa-file-pdf',
        'class' => 'btn-secondary'
    ],
];

// Chart Filter 
$currentYear = $data['selected_year'];

// Months array for dropdown
$months = [
    1 => 'January',
    2 => 'February',
    3 => 'March',
    4 => 'April',
    5 => 'May',
    6 => 'June',
    7 => 'July',
    8 => 'August',
    9 => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December'
];

// Generate year options (current year and 4 previous years)
$years = [];
for ($i = 0; $i < 5; $i++) {
    $year = date('Y') - $i;
    $years[$year] = $year;
}

?>

<!-- Link to your custom CSS file for this page -->
<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/pages/homeowner/dashboard.css">
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
            <!-- Chart Filter Component -->
            <?php
            $filterConfig = [
                'filters' => [
                    [
                        'id' => 'yearFilter',
                        'name' => 'year',
                        'label' => 'Year',
                        'options' => array_map(function ($year) use ($currentYear) {
                            return [
                                'value' => $year,
                                'label' => $year,
                                'selected' => $currentYear == $year
                            ];
                        }, array_keys($years))
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
                            (<?php echo $currentYear; ?>)
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
                            <div class="text-5xl font-bold text-white"><?php echo $daily_forecast['temperature']; ?>°C
                            </div>
                            <div class="text-white font-medium"><?php echo $daily_forecast['condition']; ?></div>
                        </div>
                        <i class="fas fa-sun text-6xl text-white opacity-75"></i>
                    </div>
                    <p class="text-white mt-4">Expect to generate approx.
                        <strong><?php echo $daily_forecast['estimated_generation']; ?> kWh</strong> today.
                    </p>
                </div>
            </div>

            <!-- System Health -->
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold">System Health</h3>
                    <p class="text-secondary text-sm mb-2">Performance vs. Expected</p>
                    <div class="progress-bar-container">
                        <div class="progress-bar"
                            style="width: <?php echo $system_health['performance_vs_expected']; ?>%;">
                            <span
                                class="progress-bar-label"><?php echo $system_health['performance_vs_expected']; ?>%</span>
                        </div>
                    </div>
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
                    <h3 class="card-title text-2xl font-semibold mb-4">Recent Faults & Alerts</h3>
                    <?php foreach ($recent_alerts as $alert): ?>
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
        fetch('/api/solar_generation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'estimate',
                system_id: YOUR_SYSTEM_ID,
                date_from: '2024-01-01',
                date_to: '2024-12-31'
            })
        })
            .then(res => res.json())
            .then(data => {
                console.log("API result:", data);
            });
        document.addEventListener('DOMContentLoaded', function () {
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