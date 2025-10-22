<?php
// --- Performance Analytics Data ---

// Sample system data with detailed metrics
$systemsData = [
    [
        'id' => 1,
        'name' => 'Ravi Fernando System',
        'location' => 'Colombo, Western Province',
        'capacity' => '10 kWp',
        'installed_date' => '2021-05-15',
        'current_output' => '8.5 kW',
        'daily_generation' => '68 kWh',
        'monthly_generation' => '1,840 kWh',
        'expected_monthly' => '1,950 kWh',
        'performance_ratio' => 108,
        'health_status' => 'Excellent',
        'last_updated' => '2 min ago',
        'efficiency' => 94.2,
        'revenue_generated' => 'LKR 45,200',
        'co2_offset' => '25.3 tons'
    ],
    [
        'id' => 2,
        'name' => 'Samanthi De Silva System',
        'location' => 'Kandy, Central Province',
        'capacity' => '8 kWp',
        'installed_date' => '2021-08-22',
        'current_output' => '6.8 kW',
        'daily_generation' => '52 kWh',
        'monthly_generation' => '1,560 kWh',
        'expected_monthly' => '1,485 kWh',
        'performance_ratio' => 105,
        'health_status' => 'Excellent',
        'last_updated' => '3 min ago',
        'efficiency' => 92.1,
        'revenue_generated' => 'LKR 38,400',
        'co2_offset' => '21.8 tons'
    ],
    [
        'id' => 3,
        'name' => 'John Doe System',
        'location' => 'Galle, Southern Province',
        'capacity' => '7.5 kWp',
        'installed_date' => '2020-12-10',
        'current_output' => '6.2 kW',
        'daily_generation' => '48 kWh',
        'monthly_generation' => '1,440 kWh',
        'expected_monthly' => '1,395 kWh',
        'performance_ratio' => 103,
        'health_status' => 'Good',
        'last_updated' => '5 min ago',
        'efficiency' => 90.5,
        'revenue_generated' => 'LKR 35,280',
        'co2_offset' => '20.1 tons'
    ],
    [
        'id' => 4,
        'name' => 'Nimali Silva System',
        'location' => 'Jaffna, Northern Province',
        'capacity' => '6 kWp',
        'installed_date' => '2022-03-18',
        'current_output' => '2.4 kW',
        'daily_generation' => '18 kWh',
        'monthly_generation' => '540 kWh',
        'expected_monthly' => '1,125 kWh',
        'performance_ratio' => 65,
        'health_status' => 'Critical',
        'last_updated' => '1 min ago',
        'efficiency' => 35.2,
        'revenue_generated' => 'LKR 13,260',
        'co2_offset' => '7.5 tons'
    ],
    [
        'id' => 5,
        'name' => 'Suresh Kumar System',
        'location' => 'Ratnapura, Sabaragamuwa Province',
        'capacity' => '9 kWp',
        'installed_date' => '2021-11-05',
        'current_output' => '5.4 kW',
        'daily_generation' => '38 kWh',
        'monthly_generation' => '1,140 kWh',
        'expected_monthly' => '1,460 kWh',
        'performance_ratio' => 78,
        'health_status' => 'Warning',
        'last_updated' => '4 min ago',
        'efficiency' => 68.3,
        'revenue_generated' => 'LKR 27,960',
        'co2_offset' => '15.9 tons'
    ],
    [
        'id' => 6,
        'name' => 'Kamal Perera System',
        'location' => 'Nuwara Eliya, Central Highlands',
        'capacity' => '5.5 kWp',
        'installed_date' => '2022-01-12',
        'current_output' => '3.2 kW',
        'daily_generation' => '26 kWh',
        'monthly_generation' => '780 kWh',
        'expected_monthly' => '952 kWh',
        'performance_ratio' => 82,
        'health_status' => 'Good',
        'last_updated' => '6 min ago',
        'efficiency' => 75.4,
        'revenue_generated' => 'LKR 19,140',
        'co2_offset' => '10.9 tons'
    ]
];

// Fleet statistics
$fleetStats = [
    'total_capacity' => '45.5 kWp',
    'total_systems' => count($systemsData),
    'total_monthly_generation' => '6,390 kWh',
    'total_expected' => '7,407 kWh',
    'overall_performance' => 86.3,
    'average_efficiency' => 76.1,
    'total_revenue' => 'LKR 179,240',
    'total_co2_offset' => '101.5 tons',
    'systems_excellent' => 3,
    'systems_good' => 2,
    'systems_warning' => 1,
    'systems_critical' => 1
];

// Hourly generation data
$hourlyData = [
    'labels' => ['6 AM', '7 AM', '8 AM', '9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM'],
    'data' => [0.5, 2.3, 4.2, 6.8, 8.5, 9.2, 9.8, 9.5, 8.8, 7.2, 5.1, 2.4]
];

// Monthly comparison
$monthlyData = [
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    'actual' => [450, 480, 520, 580, 620, 650, 680, 670, 600, 550, 480, 420],
    'expected' => [500, 510, 540, 600, 640, 700, 720, 710, 640, 580, 520, 480]
];

function getStatusColor($status) {
    switch ($status) {
        case 'Excellent': return '#22c55e';
        case 'Good': return '#3b82f6';
        case 'Warning': return '#f59e0b';
        case 'Critical': return '#ef4444';
        default: return '#6b7280';
    }
}

function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Excellent': return 'badge-success';
        case 'Good': return 'badge-info';
        case 'Warning': return 'badge-warning';
        case 'Critical': return 'badge-error';
        default: return 'badge-secondary';
    }
}
?>

<link rel="stylesheet" href="<?php echo URLROOT?>/public/css/components.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .performance-container {
        background: #f9fafb;
        min-height: 100vh;
        padding: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card-small {
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .stat-card-small .label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .stat-card-small .value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #212121;
    }

    .stat-card-small .subtext {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 0.5rem;
    }

    .performance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .performance-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .performance-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .performance-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: start;
    }

    .performance-card-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #212121;
        margin: 0;
    }

    .performance-card-header .location {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }

    .performance-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
    }

    .performance-card-body {
        padding: 1.5rem;
    }

    .metric-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .metric-row:last-child {
        border-bottom: none;
    }

    .metric {
        display: flex;
        flex-direction: column;
    }

    .metric-label {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metric-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #212121;
    }

    .progress-bar-container {
        width: 100%;
        height: 8px;
        background: #e5e7eb;
        border-radius: 9999px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #22c55e 0%, #3b82f6 100%);
        transition: width 0.3s ease;
    }

    .performance-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 0.5rem;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .chart-card h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #212121;
        margin: 0 0 1rem 0;
    }

    .chart-container {
        height: 300px;
        position: relative;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .btn-small {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        background: white;
        color: #212121;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-small:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .btn-small.primary {
        background: #fe9630;
        color: white;
        border-color: #fe9630;
    }

    .btn-small.primary:hover {
        background: #f08c1c;
        border-color: #f08c1c;
    }

    .badge-success { background-color: #d1fae5; color: #065f46; }
    .badge-info { background-color: #dbeafe; color: #1e40af; }
    .badge-warning { background-color: #fed7aa; color: #92400e; }
    .badge-error { background-color: #fee2e2; color: #991b1b; }
    .badge-secondary { background-color: #e5e7eb; color: #374151; }

    .badge {
        display: inline-block;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .performance-grid {
            grid-template-columns: 1fr;
        }

        .charts-grid {
            grid-template-columns: 1fr;
        }

        .metric-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'System Performance Analytics',
        'description' => 'Detailed performance metrics and analytics for all systems',
        'show_back' => true,
        'back_url' => URLROOT . '/installeradmin/dashboard',
        'back_label' => 'Back to Dashboard'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Fleet Overview Stats -->
    <div class="stats-grid">
        <div class="stat-card-small">
            <div class="label">Total Capacity</div>
            <div class="value"><?php echo $fleetStats['total_capacity']; ?></div>
            <div class="subtext"><?php echo $fleetStats['total_systems']; ?> systems</div>
        </div>
        <div class="stat-card-small">
            <div class="label">Monthly Generation</div>
            <div class="value">6,390</div>
            <div class="subtext">kWh (Target: 7,407 kWh)</div>
        </div>
        <div class="stat-card-small">
            <div class="label">Overall Performance</div>
            <div class="value"><?php echo $fleetStats['overall_performance']; ?>%</div>
            <div class="subtext">86% of expected</div>
        </div>
        <div class="stat-card-small">
            <div class="label">Revenue Generated</div>
            <div class="value"><?php echo $fleetStats['total_revenue']; ?></div>
            <div class="subtext">This month</div>
        </div>
        <div class="stat-card-small">
            <div class="label">CO₂ Offset</div>
            <div class="value"><?php echo $fleetStats['total_co2_offset']; ?></div>
            <div class="subtext">Total equivalent</div>
        </div>
        <div class="stat-card-small">
            <div class="label">System Health</div>
            <div class="value">
                <span style="color: #22c55e;">●</span>
                <span style="color: #3b82f6;">●</span>
                <span style="color: #f59e0b;">●</span>
                <span style="color: #ef4444;">●</span>
            </div>
            <div class="subtext">3 Ex | 2 Good | 1 Warn | 1 Crit</div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="charts-grid">
        <!-- Hourly Generation Chart -->
        <div class="chart-card">
            <h3>Today's Generation Profile</h3>
            <div class="chart-container">
                <canvas id="hourlyChart"></canvas>
            </div>
        </div>

        <!-- Monthly Comparison Chart -->
        <div class="chart-card">
            <h3>Monthly Performance vs Target (YTD)</h3>
            <div class="chart-container">
                <canvas id="monthlyComparisonChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <?php
    $config = [
        'search' => [
            'id' => 'searchSystems',
            'name' => 'search',
            'label' => 'Search Systems',
            'placeholder' => 'Search by name or location...'
        ],
        'filters' => [
            [
                'id' => 'filterHealth',
                'name' => 'health',
                'label' => 'System Health',
                'options' => [
                    ['value' => '', 'label' => 'All Status'],
                    ['value' => 'excellent', 'label' => 'Excellent'],
                    ['value' => 'good', 'label' => 'Good'],
                    ['value' => 'warning', 'label' => 'Warning'],
                    ['value' => 'critical', 'label' => 'Critical']
                ]
            ],
            [
                'id' => 'filterEfficiency',
                'name' => 'efficiency',
                'label' => 'Efficiency Range',
                'options' => [
                    ['value' => '', 'label' => 'All Ranges'],
                    ['value' => 'high', 'label' => 'High (80%+)'],
                    ['value' => 'medium', 'label' => 'Medium (60-80%)'],
                    ['value' => 'low', 'label' => 'Low (<60%)']
                ]
            ]
        ],
        'buttons' => [],
        'form_action' => URLROOT . '/installeradmin/system_performance',
        'form_method' => 'GET',
        'auto_submit' => true,
        'reset_on_clear' => true
    ];
    include __DIR__ . '/../../inc/components/filter_bar.php';
    ?>

    <!-- Individual System Performance Details -->
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #212121;">
            System Performance Details
        </h2>
        
        <?php
        $config = [
            'headers' => [
                ['key' => 'name', 'label' => 'System Name'],
                ['key' => 'location', 'label' => 'Location'],
                ['key' => 'capacity', 'label' => 'Capacity'],
                ['key' => 'current_output', 'label' => 'Current Output'],
                ['key' => 'daily_generation', 'label' => 'Daily Gen.'],
                ['key' => 'monthly_generation', 'label' => 'Monthly Gen.'],
                ['key' => 'performance_ratio', 'label' => 'Performance'],
                ['key' => 'efficiency', 'label' => 'Efficiency'],
                ['key' => 'health_status', 'label' => 'Status']
            ],
            'rows' => array_map(function($system) {
                return [
                    'id' => $system['id'],
                    'name' => $system['name'],
                    'location' => $system['location'],
                    'capacity' => $system['capacity'],
                    'current_output' => $system['current_output'],
                    'daily_generation' => $system['daily_generation'],
                    'monthly_generation' => $system['monthly_generation'],
                    'expected_monthly' => $system['expected_monthly'],
                    'performance_ratio' => $system['performance_ratio'],
                    'efficiency' => $system['efficiency'],
                    'health_status' => $system['health_status'],
                    'installed_date' => $system['installed_date']
                ];
            }, $systemsData),
            'columns' => [
                [
                    'key' => 'name',
                    'render' => function($row) {
                        return '<div style="font-weight: 600; color: #212121;">' . htmlspecialchars($row['name']) . '</div>' .
                               '<div style="font-size: 0.875rem; color: #6b7280;">Installed: ' . date('M Y', strtotime($row['installed_date'])) . '</div>';
                    }
                ],
                [
                    'key' => 'location',
                    'render' => function($row) {
                        return '<div style="color: #6b7280;">' . htmlspecialchars($row['location']) . '</div>';
                    }
                ],
                [
                    'key' => 'capacity',
                    'render' => function($row) {
                        return '<div style="color: #212121; font-weight: 500;">' . htmlspecialchars($row['capacity']) . '</div>';
                    }
                ],
                [
                    'key' => 'current_output',
                    'render' => function($row) {
                        return '<div style="color: #212121; font-weight: 500;">' . htmlspecialchars($row['current_output']) . '</div>';
                    }
                ],
                [
                    'key' => 'daily_generation',
                    'render' => function($row) {
                        return '<div style="color: #212121; font-weight: 500;">' . htmlspecialchars($row['daily_generation']) . '</div>';
                    }
                ],
                [
                    'key' => 'monthly_generation',
                    'render' => function($row) {
                        return '<div style="color: #212121; font-weight: 600;">' . htmlspecialchars($row['monthly_generation']) . '</div>' .
                               '<div style="font-size: 0.75rem; color: #9ca3af;">Target: ' . htmlspecialchars($row['expected_monthly']) . '</div>';
                    }
                ],
                [
                    'key' => 'performance_ratio',
                    'render' => function($row) {
                        $color = ($row['performance_ratio'] >= 100) ? '#22c55e' : (($row['performance_ratio'] >= 80) ? '#3b82f6' : (($row['performance_ratio'] >= 70) ? '#f59e0b' : '#ef4444'));
                        return '<div style="font-weight: 700; font-size: 1.1rem; color: ' . $color . ';">' . $row['performance_ratio'] . '%</div>';
                    }
                ],
                [
                    'key' => 'efficiency',
                    'render' => function($row) {
                        return '<div style="color: #212121; font-weight: 500;">' . number_format($row['efficiency'], 1) . '%</div>';
                    }
                ],
                [
                    'key' => 'health_status',
                    'render' => function($row) {
                        $statusClass = getStatusBadgeClass($row['health_status']);
                        return '<span class="badge ' . $statusClass . '" style="display: inline-block; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600;">' . htmlspecialchars($row['health_status']) . '</span>';
                    }
                ]
            ],
            'empty_message' => 'No systems available'
        ];
        include __DIR__ . '/../../inc/components/data_table.php';
        ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        font: { size: 12, family: "'Segoe UI', sans-serif" },
                        color: '#666',
                        padding: 15
                    }
                },
                filler: {
                    propagate: true
                }
            }
        };

        // Hourly Generation Chart
        const hourlyCtx = document.getElementById('hourlyChart');
        if (hourlyCtx) {
            new Chart(hourlyCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($hourlyData['labels']); ?>,
                    datasets: [{
                        label: 'Generation (kW)',
                        data: <?php echo json_encode($hourlyData['data']); ?>,
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    ...chartOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#e5e7eb' },
                            ticks: { color: '#6b7280' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#6b7280' }
                        }
                    }
                }
            });
        }

        // Monthly Comparison Chart
        const monthlyCtx = document.getElementById('monthlyComparisonChart');
        if (monthlyCtx) {
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($monthlyData['labels']); ?>,
                    datasets: [
                        {
                            label: 'Actual Generation',
                            data: <?php echo json_encode($monthlyData['actual']); ?>,
                            backgroundColor: 'rgba(34, 197, 94, 0.7)',
                            borderColor: 'rgba(34, 197, 94, 1)',
                            borderWidth: 0,
                            borderRadius: 5
                        },
                        {
                            label: 'Expected Generation',
                            data: <?php echo json_encode($monthlyData['expected']); ?>,
                            backgroundColor: 'rgba(59, 130, 246, 0.3)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 2,
                            borderRadius: 5,
                            fill: false,
                            type: 'line',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    ...chartOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#e5e7eb' },
                            ticks: { color: '#6b7280' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#6b7280' }
                        }
                    }
                }
            });
        }
    });
</script>
