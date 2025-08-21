<?php
// --- PHP Setup for Dummy Data ---

// Business Summary Cards
$summary_cards = [
    ['label' => 'Total Active Clients', 'value' => 128, 'icon' => 'fas fa-users'],
    ['label' => 'Systems with Critical Faults', 'value' => 4, 'icon' => 'fas fa-exclamation-triangle'],
    ['label' => 'Pending Maintenance', 'value' => 9, 'icon' => 'fas fa-wrench'],
    ['label' => 'Pending Installations', 'value' => 5, 'icon' => 'fas fa-tools'],
    ['label' => 'Active Service Agents', 'value' => 6, 'icon' => 'fas fa-users-cog'],
    ['label' => 'Monthly Recurring Revenue', 'value' => 'LKR 1.2M', 'icon' => 'fas fa-coins'],
];

// High-Priority Alerts
$alerts = [
    ['client' => 'Kamal Perera', 'issue' => 'System Offline > 24hrs', 'priority' => 'high'],
    ['client' => 'Suresh Kumar', 'issue' => 'Inverter Fault Detected', 'priority' => 'high'],
    ['client' => 'Nimali Silva', 'issue' => 'Performance degraded by 35%', 'priority' => 'medium'],
];

// Chart Data
$new_customers_chart_data = [
    'labels' => ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    'data' => [5, 8, 6, 10, 9, 12],
];
$service_tasks_chart_data = [
    'labels' => ['Pending', 'In Progress', 'Completed'],
    'data' => [9, 4, 23],
    'colors' => ['#f59e0b', '#00bcd4', '#22c55e'] // warning, accent, success
];

// Service Team Status
$service_agents = [
    ['name' => 'Anura Kumara', 'status' => 'Available'],
    ['name' => 'Bhanu Rajapaksha', 'status' => 'On Task'],
    ['name' => 'Charith Weerasinghe', 'status' => 'Available'],
    ['name' => 'Dasun Shanaka', 'status' => 'On Task'],
    ['name' => 'Eshan Priyantha', 'status' => 'Offline'],
    ['name' => 'Fathima Noor', 'status' => 'Available'],
];

// Fleet-Wide Energy Generation Chart Data (in MWh for larger scale)
$fleet_generation_data = [
    'labels' => ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    'data' => [56.4, 62.1, 60.5, 68.2, 65.7, 61.3], // MWh
];

// Best & Worst Performing Systems
$best_performers = [
    ['name' => 'Ravi Fernando', 'performance' => 108, 'status_class' => 'text-success'],
    ['name' => 'Samanthi De Silva', 'performance' => 105, 'status_class' => 'text-success'],
    ['name' => 'John Doe', 'performance' => 103, 'status_class' => 'text-success'],
];
$worst_performers = [
    ['name' => 'Nimali Silva', 'performance' => 65, 'status_class' => 'text-error'],
    ['name' => 'Suresh Kumar', 'performance' => 78, 'status_class' => 'text-warning'],
    ['name' => 'Kamal Perera', 'performance' => 82, 'status_class' => 'text-warning'],
];


function getAgentStatusClass($status) {
    switch ($status) {
        case 'Available': return 'bg-success';
        case 'On Task': return 'bg-warning';
        default: return 'bg-secondary';
    }
}
?>

<!-- Link to your custom CSS file -->
<link rel="stylesheet" href="<?php echo URLROOT?>/public/css/pages/installer/dashboard.css">
<!-- Include Chart.js for the charts to render -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-area">
        <!-- Header Section -->
        <div class="d-flex justify-between align-center mb-6">
            <div>
                <h1 class="text-4xl font-bold">Good Afternoon, Installer!</h1>
                <p class="text-secondary">Here's your business and fleet performance overview.</p>
            </div>
            <div>
                <a href="#" class="btn btn-primary rounded-lg mr-2">+ Add New Customer</a>
                <a href="#" class="btn btn-secondary rounded-lg">+ Add Service Agent</a>
            </div>
        </div>

        <!-- Business Summary Cards -->
        <div class="row">
            <?php foreach($summary_cards as $card): ?>
            <div class="col-lg-4 col-xl-2 col-md-6 mb-4">
                <div class="card shadow-md rounded-xl h-100">
                    <div class="card-body">
                        <i class="<?php echo $card['icon']; ?> text-3xl text-secondary mb-3"></i>
                        <div class="text-4xl font-bold"><?php echo $card['value']; ?></div>
                        <div class="text-secondary"><?php echo $card['label']; ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Main Grid -->
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Fleet-Wide Energy Generation Chart -->
                <div class="card shadow-lg rounded-xl mb-6">
                    <div class="card-body">
                        <h3 class="card-title text-2xl font-semibold mb-4">Fleet-Wide Energy Generation (MWh)</h3>
                        <div class="chart-container">
                            <canvas id="fleetGenerationChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- High-Priority Alerts -->
                <div class="card shadow-lg rounded-xl mb-6">
                    <div class="card-body">
                        <h3 class="card-title text-2xl font-semibold mb-4">Immediate Attention Required</h3>
                        <?php foreach($alerts as $alert): ?>
                        <div class="alert-item d-flex justify-between align-center py-3">
                            <div class="d-flex align-center">
                                <i class="fas fa-exclamation-circle text-error text-xl mr-4"></i>
                                <div>
                                    <div class="font-semibold"><?php echo $alert['client']; ?></div>
                                    <div class="text-secondary text-sm"><?php echo $alert['issue']; ?></div>
                                </div>
                            </div>
                            <a href="#" class="btn btn-sm btn-secondary rounded-lg">View Details</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Performance Charts -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-lg rounded-xl">
                            <div class="card-body">
                                <h3 class="card-title text-xl font-semibold mb-4">New Customers (Last 6 Months)</h3>
                                <div class="chart-container">
                                    <canvas id="newCustomersChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-lg rounded-xl">
                            <div class="card-body">
                                <h3 class="card-title text-xl font-semibold mb-4">Service Task Status</h3>
                                <div class="chart-container">
                                    <canvas id="serviceTasksChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Best & Worst Performers -->
                <div class="card shadow-lg rounded-xl mb-6">
                    <div class="card-body">
                        <h3 class="card-title text-2xl font-semibold mb-4">System Performance Snapshot</h3>
                        <!-- Best Performers -->
                        <div class="mb-4">
                            <h4 class="font-semibold text-success mb-2">Best Performing Systems</h4>
                            <?php foreach($best_performers as $performer): ?>
                            <div class="performer-item d-flex justify-between align-center py-2">
                                <span><i class="fas fa-arrow-up mr-2"></i><?php echo $performer['name']; ?></span>
                                <span class="font-bold <?php echo $performer['status_class']; ?>"><?php echo $performer['performance']; ?>%</span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Worst Performers -->
                        <div>
                            <h4 class="font-semibold text-error mb-2">Worst Performing Systems</h4>
                             <?php foreach($worst_performers as $performer): ?>
                            <div class="performer-item d-flex justify-between align-center py-2">
                                <span><i class="fas fa-arrow-down mr-2"></i><?php echo $performer['name']; ?></span>
                                <span class="font-bold <?php echo $performer['status_class']; ?>"><?php echo $performer['performance']; ?>%</span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Service Team Status -->
                <div class="card shadow-lg rounded-xl">
                    <div class="card-body">
                        <h3 class="card-title text-2xl font-semibold mb-4">Service Team Availability</h3>
                        <?php foreach($service_agents as $agent): ?>
                        <div class="d-flex align-center justify-between py-2">
                            <span><?php echo $agent['name']; ?></span>
                            <div class="d-flex align-center">
                                <span class="status-dot <?php echo getAgentStatusClass($agent['status']); ?> mr-2"></span>
                                <span class="text-sm font-semibold"><?php echo $agent['status']; ?></span>
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
            // Fleet-Wide Energy Generation Chart (Line)
            const fleetGenerationCtx = document.getElementById('fleetGenerationChart');
            if (fleetGenerationCtx) {
                new Chart(fleetGenerationCtx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($fleet_generation_data['labels']); ?>,
                        datasets: [{
                            label: 'Total Generation (MWh)',
                            data: <?php echo json_encode($fleet_generation_data['data']); ?>,
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            borderColor: 'rgba(34, 197, 94, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: false }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            }

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
                            borderWidth: 1,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true }
                        },
                        plugins: { legend: { display: false } }
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
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });
            }
        });
    </script>
