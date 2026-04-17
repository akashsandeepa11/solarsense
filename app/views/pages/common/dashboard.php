<?php
// --- PHP Setup for Data ---

// Get current month and year from request, or use current date
$selectedMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// --- PHP Setup for Data ---
$stats = $data['stats'];

$summary_cards = [
    ['label' => 'Total Active Clients', 'value' => $stats['active_clients'], 'icon' => 'fas fa-users', 'color' => 'primary'],
    ['label' => 'Critical Faults', 'value' => $stats['critical_faults'], 'icon' => 'fas fa-exclamation-triangle', 'color' => 'error'],
    ['label' => 'Underperforming Systems', 'value' => $stats['underperforming'], 'icon' => 'fas fa-chart-line', 'color' => 'warning'],
    ['label' => 'Pending Tasks', 'value' => $stats['pending_tasks'], 'icon' => 'fas fa-wrench', 'color' => 'warning'],
    ['label' => 'Pending Installations', 'value' => $stats['pending_installations'], 'icon' => 'fas fa-tools', 'color' => 'warning'],
    ['label' => 'Active Agents', 'value' => $stats['active_agents'], 'icon' => 'fas fa-users-cog', 'color' => 'accent']
];

// High-Priority Alerts
$alerts = [];
if (!empty($data['alerts'])) {
    foreach ($data['alerts'] as $alert) {
        $alerts[] = [
            'client' => $alert->email, // Displaying client email as requested
            'issue' => $alert->issue,
            'priority' => $alert->priority
        ];
    }
}

// Chart Data (Dynamic based on selected month/year)
$labels = [];
$counts = [];

if (!empty($data['new_customers_data'])) {
    foreach ($data['new_customers_data'] as $row) {
        $labels[] = $row->month_label;
        $counts[] = $row->customer_count;
    }
}

$new_customers_chart_data = [
    'labels' => $labels,
    'data' => $counts,
];
// --- Inside dashboard.php, replace the static $service_tasks_chart_data array ---
$task_labels = ['Pending', 'In Progress', 'Completed'];
$task_counts = [0, 0, 0]; // Default values

if (!empty($data['service_tasks_data'])) {
    foreach ($data['service_tasks_data'] as $row) {
        if ($row->status == 'Pending') $task_counts[0] = $row->task_count;
        if ($row->status == 'In Progress') $task_counts[1] = $row->task_count;
        if ($row->status == 'Completed') $task_counts[2] = $row->task_count;
    }
}

$service_tasks_chart_data = [
    'labels' => $task_labels,
    'data' => $task_counts,
    'colors' => ['#f59e0b', '#00bcd4', '#22c55e'] // Warning, Accent, Success
];

// Service Team Status
$service_agents = [];
if (!empty($data['service_agents'])) {
    foreach ($data['service_agents'] as $agent) {
        $service_agents[] = [
            'name' => $agent->name,
            'status' => ucfirst($agent->status)
        ];
    }
}

// Fleet-Wide Energy Generation Chart Data (in MWh)
$fleet_generation_data = [
    'labels' => ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    'data' => [56.4, 62.1, 60.5, 68.2, 65.7, 61.3],
];

// --- Inside dashboard.php, replace the static arrays ---
$snapshot = $data['performance_snapshot'];

// Best Performers
$best_performers = [];
foreach ($snapshot['best'] as $item) {
    $best_performers[] = [
        'name' => $item->full_name,
        'performance' => $item->performance,
        'status_class' => 'text-success'
    ];
}

// Worst Performers
$worst_performers = [];
foreach ($snapshot['worst'] as $item) {
    $worst_performers[] = [
        'name' => $item->full_name,
        'performance' => $item->performance,
        // Systems below 50% are marked with 'text-error' (Critical)
        'status_class' => ($item->performance < 50) ? 'text-error' : 'text-warning'
    ];
}



function getAgentStatusClass($status) {
    switch ($status) {
        case 'Available': return 'bg-success';
        case 'On Task': return 'bg-warning';
        default: return 'bg-secondary';
    }
}
?>

<link rel="stylesheet" href="<?php echo URLROOT?>/public/css/pages/installer/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-area">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Dashboard',
        'description' => 'Business and fleet performance overview',
        'buttons' => [
            [
                'label' => 'Generate Report',
                'url' => URLROOT . '/operationmanager/reports/generate',
                'icon' => 'fas fa-file-alt',
                'class' => 'btn-primary'
            ]
        ]
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Business Summary Cards -->
    <?php
    $config = [
        'stats' => $summary_cards,
        'columns' => 6
    ];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Month/Year Selector Card -->
            <!-- <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body p-6">
                    <h3 class="card-title text-xl font-semibold mb-4">Filter by Period</h3>
                    <form method="GET" class="d-flex gap-3 align-end">
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label text-sm font-semibold">Month</label>
                            <select name="month" class="form-control" onchange="this.form.submit()">
                                <?php for($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?php echo $m; ?>" <?php echo $selectedMonth == $m ? 'selected' : ''; ?>>
                                        <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label text-sm font-semibold">Year</label>
                            <select name="year" class="form-control" onchange="this.form.submit()">
                                <?php for($y = date('Y') - 3; $y <= date('Y'); $y++): ?>
                                    <option value="<?php echo $y; ?>" <?php echo $selectedYear == $y ? 'selected' : ''; ?>>
                                        <?php echo $y; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div> -->

           <!-- Performance Charts Row -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-lg rounded-xl">
                        <div class="card-body">
                            <h3 class="card-title text-lg font-semibold mb-4">New Customers (Last 6 Months)</h3>
                            <div class="chart-container" style="height: 250px;">
                                <canvas id="newCustomersChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-lg rounded-xl">
                        <div class="card-body">
                            <h3 class="card-title text-lg font-semibold mb-4">Service Task Status</h3>
                            <div class="chart-container" style="height: 250px;">
                                <canvas id="serviceTasksChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- High-Priority Alerts -->
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold mb-4">
                        <i class="fas fa-exclamation-circle text-error mr-2"></i>Immediate Attention Required
                    </h3>
                    <?php if(!empty($alerts)): ?>
                        <?php foreach($alerts as $alert): ?>
                        <div class="alert-item d-flex justify-between align-center py-3 border-bottom" style="border-bottom-color: #e5e7eb;">
                            <div class="d-flex align-center gap-3">
                                <i class="fas fa-exclamation-circle text-error text-xl"></i>
                                <div>
                                    <div class="font-semibold"><?php echo htmlspecialchars($alert['client']); ?></div>
                                </div>
                            </div>
                            <a href="#" class="btn btn-sm btn-secondary rounded-lg">View Details</a>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-secondary text-center py-4">No alerts at this time</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- System Performance Snapshot -->
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h3 class="card-title text-xl font-semibold">System Performance Snapshot</h3>
                        <a href="<?php echo URLROOT; ?>/installeradmin/dashboard/system_performance" class="btn btn-sm btn-primary" style="padding: 0.5rem 1rem; background-color: #fe9630; color: white; text-decoration: none; border-radius: 0.375rem; font-size: 0.875rem;">
                            <i class="fas fa-chart-bar mr-2"></i>View Full Analytics
                        </a>
                    </div>
                    
                    <!-- Best Performers -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-success mb-3">
                            <i class="fas fa-arrow-up mr-2"></i>Best Performing
                        </h4>
                        <?php foreach($best_performers as $performer): ?>
                        <div class="performer-item d-flex justify-between align-center py-2 px-3" style="background: rgba(34, 197, 94, 0.05); border-radius: 0.5rem; margin-bottom: 0.5rem;">
                            <span class="text-sm"><?php echo htmlspecialchars($performer['name']); ?></span>
                            <span class="font-bold <?php echo $performer['status_class']; ?>"><?php echo $performer['performance']; ?>%</span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Worst Performers -->
                    <div>
                        <h4 class="font-semibold text-error mb-3">
                            <i class="fas fa-arrow-down mr-2"></i>Worst Performing
                        </h4>
                        <?php foreach($worst_performers as $performer): ?>
                        <div class="performer-item d-flex justify-between align-center py-2 px-3" style="background: rgba(239, 68, 68, 0.05); border-radius: 0.5rem; margin-bottom: 0.5rem;">
                            <span class="text-sm"><?php echo htmlspecialchars($performer['name']); ?></span>
                            <span class="font-bold <?php echo $performer['status_class']; ?>"><?php echo $performer['performance']; ?>%</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Service Team Status -->
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold mb-4">
                        <i class="fas fa-users-cog text-primary mr-2"></i>Service Team Status
                    </h3>
                    <?php foreach($service_agents as $agent): ?>
                    <div class="d-flex align-center justify-between py-3" style="border-bottom: 1px solid #e5e7eb;">
                        <span class="text-sm font-medium"><?php echo htmlspecialchars($agent['name']); ?></span>
                        <div class="d-flex align-center gap-2">
                            <span class="status-dot <?php echo getAgentStatusClass($agent['status']); ?>"></span>
                            <span class="text-xs font-semibold"><?php echo htmlspecialchars($agent['status']); ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
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
                        font: { size: 12, family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif" },
                        color: '#666'
                    }
                }
            }
        };

        // New Customers Chart (Bar)
        const newCustomersCtx = document.getElementById('newCustomersChart');
        if (newCustomersCtx) {
            new Chart(newCustomersCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($new_customers_chart_data['labels']); ?>,
                    datasets: [{
                        label: 'New Customers',
                        data: <?php echo json_encode($new_customers_chart_data['data']); ?>,
                        backgroundColor: 'rgba(254, 150, 48, 0.7)',
                        borderColor: 'rgba(254, 150, 48, 1)',
                        borderWidth: 0,
                        borderRadius: 5
                    }]
                },
                options: {
                    ...chartOptions,
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#e5e7eb' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Service Tasks Chart (Doughnut)
        const serviceTasksCtx = document.getElementById('serviceTasksChart');
        if (serviceTasksCtx) {
            new Chart(serviceTasksCtx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($service_tasks_chart_data['labels']); ?>,
                    datasets: [{
                        label: 'Tasks',
                        data: <?php echo json_encode($service_tasks_chart_data['data']); ?>,
                        backgroundColor: <?php echo json_encode($service_tasks_chart_data['colors']); ?>,
                        hoverOffset: 4,
                        borderWidth: 0
                    }]
                },
                options: {
                    ...chartOptions,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 15 }
                        }
                    }
                }
            });
        }
    });
</script>
