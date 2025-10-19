<?php
// Operation Manager dashboard (componentized)

// Business Summary Cards (example data)
$summary_cards = [
    ['label' => 'Total Active Clients', 'value' => 128, 'icon' => 'fas fa-users'],
    ['label' => 'Systems with Critical Faults', 'value' => 4, 'icon' => 'fas fa-exclamation-triangle'],
    ['label' => 'Pending Maintenance', 'value' => 9, 'icon' => 'fas fa-wrench'],
    ['label' => 'Pending Installations', 'value' => 5, 'icon' => 'fas fa-tools'],
    ['label' => 'Active Service Agents', 'value' => 6, 'icon' => 'fas fa-users-cog'],
    ['label' => 'Monthly Recurring Revenue', 'value' => 'LKR 1.2M', 'icon' => 'fas fa-coins'],
];

// Alerts
$alerts = [
    ['client' => 'Kamal Perera', 'issue' => 'System Offline > 24hrs', 'priority' => 'high'],
    ['client' => 'Suresh Kumar', 'issue' => 'Inverter Fault Detected', 'priority' => 'high'],
    ['client' => 'Nimali Silva', 'issue' => 'Performance degraded by 35%', 'priority' => 'medium'],
];

// Charts / performers / agents (copied example data)
$fleet_generation_data = [ 'labels' => ['Feb','Mar','Apr','May','Jun','Jul'], 'data' => [56.4,62.1,60.5,68.2,65.7,61.3] ];
$new_customers_chart_data = [ 'labels' => ['Feb','Mar','Apr','May','Jun','Jul'], 'data' => [5,8,6,10,9,12] ];
$service_tasks_chart_data = [ 'labels' => ['Pending','In Progress','Completed'], 'data' => [9,4,23], 'colors' => ['#f59e0b','#00bcd4','#22c55e'] ];
$best_performers = [ ['name'=>'Ravi Fernando','performance'=>108,'status_class'=>'text-success'] ];
$worst_performers = [ ['name'=>'Nimali Silva','performance'=>65,'status_class'=>'text-error'] ];
$service_agents = [ ['name'=>'Anura Kumara','status'=>'Available'], ['name'=>'Dasun Shanaka','status'=>'On Task'] ];

?>

<link rel="stylesheet" href="<?php echo URLROOT?>/public/css/pages/installer/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-area">
    <div class="d-flex justify-between align-center mb-6">
        <div>
            <h1 class="text-4xl font-bold">Good Afternoon, Operation Manager!</h1>
            <p class="text-secondary">Here's your operations overview.</p>
        </div>
        <div>
            <a href="#" class="btn btn-primary rounded-lg mr-2">+ Add New Customer</a>
        </div>
    </div>

    <div class="row">
        <?php foreach($summary_cards as $card): ?>
            <?php require APPROOT . '/views/inc/components/summary_card.php'; ?>
        <?php endforeach; ?>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-4">Fleet-Wide Energy Generation (MWh)</h3>
                    <div class="chart-container"><canvas id="fleetGenerationChart"></canvas></div>
                </div>
            </div>

            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-4">Immediate Attention Required</h3>
                    <?php foreach($alerts as $alert): ?>
                        <?php require APPROOT . '/views/inc/components/alert_item.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-lg rounded-xl">
                        <div class="card-body">
                            <h3 class="card-title text-xl font-semibold mb-4">New Customers (Last 6 Months)</h3>
                            <div class="chart-container"><canvas id="newCustomersChart"></canvas></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-lg rounded-xl">
                        <div class="card-body">
                            <h3 class="card-title text-xl font-semibold mb-4">Service Task Status</h3>
                            <div class="chart-container"><canvas id="serviceTasksChart"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <?php $title = 'Best'; $performers = $best_performers; require APPROOT . '/views/inc/components/performer_list.php'; ?>
                    <?php $title = 'Worst'; $performers = $worst_performers; require APPROOT . '/views/inc/components/performer_list.php'; ?>
                </div>
            </div>

            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-4">Service Team Availability</h3>
                    <?php foreach($service_agents as $agent): ?>
                        <div class="d-flex align-center justify-between py-2">
                            <span><?php echo htmlspecialchars($agent['name']); ?></span>
                            <div class="d-flex align-center">
                                <span class="status-dot <?php echo $agent['status'] === 'Available' ? 'bg-success' : 'bg-warning'; ?> mr-2"></span>
                                <span class="text-sm font-semibold"><?php echo htmlspecialchars($agent['status']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Fleet chart
    const ctx = document.getElementById('fleetGenerationChart');
    if (ctx) {
        new Chart(ctx, { type: 'line', data: { labels: <?php echo json_encode($fleet_generation_data['labels']); ?>, datasets: [{ label: 'Total Generation (MWh)', data: <?php echo json_encode($fleet_generation_data['data']); ?>, backgroundColor: 'rgba(34, 197, 94, 0.1)', borderColor: 'rgba(34, 197, 94, 1)', borderWidth: 2, fill: true, tension: 0.4 }] }, options: { responsive:true, maintainAspectRatio:false, scales:{ y:{beginAtZero:false} }, plugins:{ legend:{ display:false } } } });
    }

    // New customers
    const newCustomersCtx = document.getElementById('newCustomersChart');
    if (newCustomersCtx) {
        new Chart(newCustomersCtx, { type:'bar', data:{ labels: <?php echo json_encode($new_customers_chart_data['labels']); ?>, datasets:[{ label:'New Customers', data: <?php echo json_encode($new_customers_chart_data['data']); ?>, backgroundColor:'rgba(254,150,48,0.7)', borderColor:'rgba(254,150,48,1)', borderWidth:1, borderRadius:5 }] }, options:{ responsive:true, maintainAspectRatio:false, scales:{ y:{ beginAtZero:true } }, plugins:{ legend:{ display:false } } } });
    }

    // Service tasks
    const serviceTasksCtx = document.getElementById('serviceTasksChart');
    if (serviceTasksCtx) {
        new Chart(serviceTasksCtx, { type:'doughnut', data:{ labels: <?php echo json_encode($service_tasks_chart_data['labels']); ?>, datasets:[{ data: <?php echo json_encode($service_tasks_chart_data['data']); ?>, backgroundColor: <?php echo json_encode($service_tasks_chart_data['colors']); ?> }] }, options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } } } });
    }
});
</script>
<!-- stray header removed -->