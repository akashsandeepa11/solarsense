<?php
// --- PHP Setup for Super Admin Dashboard ---

// Platform Summary Cards
$summary_cards = [
    ['label' => 'Total Registered Users', 'value' => 1280, 'icon' => 'fas fa-users'],
    ['label' => 'Active Installer Companies', 'value' => 6, 'icon' => 'fas fa-building'],
    ['label' => 'Pending Company Verifications', 'value' => 3, 'icon' => 'fas fa-hourglass-half'],
    ['label' => 'Active Faults Across Platform', 'value' => 7, 'icon' => 'fas fa-exclamation-triangle'],
    ['label' => 'Monthly Recurring Revenue', 'value' => 'LKR 2.4M', 'icon' => 'fas fa-coins'],
];

// High-Priority System Alerts
$alerts = [
    ['client' => 'SunPeak Engineering', 'issue' => '3 client systems offline', 'priority' => 'high'],
    ['client' => 'GreenVolt Installers', 'issue' => 'Performance anomaly detected in 2 installations', 'priority' => 'medium'],
    ['client' => 'EcoWatt Solutions', 'issue' => 'Faulty inverter in Colombo site', 'priority' => 'high'],
];

// Platform Growth (new installers joining)
$new_companies_chart_data = [
    'labels' => ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    'data' => [1, 2, 2, 3, 3, 5, 6],
];



// Fleet-Wide Energy Generation (MWh)
$fleet_generation_data = [
    'labels' => ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    'data' => [250.2, 265.7, 280.1, 275.5, 288.3, 295.2, 310.8],
];

// Best Performing Companies
$best_companies = [
    ['name' => 'EcoWatt Solutions', 'performance' => 109, 'status_class' => 'text-success'],
    ['name' => 'BrightRay Solutions', 'performance' => 107, 'status_class' => 'text-success'],
    ['name' => 'SunTech Installers', 'performance' => 104, 'status_class' => 'text-success'],
];

// Underperforming Companies
$worst_companies = [
    ['name' => 'SolarRise Energy', 'performance' => 82, 'status_class' => 'text-warning'],
    ['name' => 'SunPeak Engineering', 'performance' => 76, 'status_class' => 'text-error'],
    ['name' => 'GreenVolt Installers', 'performance' => 70, 'status_class' => 'text-error'],
];
?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/pages/installer/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-area">
    <!-- Header -->
    <div class="d-flex justify-between align-center mb-6">
        <div>
            <h1 class="text-4xl font-bold">Welcome, Super Admin</h1>
            <p class="text-secondary">Here's a complete overview of the SolarSense platform.</p>
        </div>
    </div>

    <!-- Summary Cards -->
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
            <!-- Fleet Energy Generation -->
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-4">Platform-Wide Energy Generation (MWh)</h3>
                    <div class="chart-container">
                        <canvas id="fleetGenerationChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-4">High-Priority System Alerts</h3>
                    <?php foreach($alerts as $alert): ?>
                    <div class="alert-item d-flex justify-between align-center py-3 border-b">
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

            <!-- Charts -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-lg rounded-xl">
                        <div class="card-body">
                            <h3 class="card-title text-xl font-semibold mb-4">New Installer Companies</h3>
                            <div class="chart-container">
                                <canvas id="newCompaniesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Best & Worst Companies -->
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-4">Top Performing Companies</h3>
                    <?php foreach($best_companies as $company): ?>
                        <div class="d-flex justify-between align-center mb-3">
                            <span><?php echo $company['name']; ?></span>
                            <span class="<?php echo $company['status_class']; ?>"><?php echo $company['performance']; ?>%</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-4">Underperforming Companies</h3>
                    <?php foreach($worst_companies as $company): ?>
                        <div class="d-flex justify-between align-center mb-3">
                            <span><?php echo $company['name']; ?></span>
                            <span class="<?php echo $company['status_class']; ?>"><?php echo $company['performance']; ?>%</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fleet Energy Chart
    const fleetCtx = document.getElementById('fleetGenerationChart');
    if (fleetCtx) {
        new Chart(fleetCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($fleet_generation_data['labels']); ?>,
                datasets: [{
                    label: 'Energy Generated (MWh)',
                    data: <?php echo json_encode($fleet_generation_data['data']); ?>,
                    backgroundColor: 'rgba(34, 197, 94, 0.15)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    }

    // New Companies Chart
    const newCompaniesCtx = document.getElementById('newCompaniesChart');
    if (newCompaniesCtx) {
        new Chart(newCompaniesCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($new_companies_chart_data['labels']); ?>,
                datasets: [{
                    label: 'New Companies',
                    data: <?php echo json_encode($new_companies_chart_data['data']); ?>,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });
    }


});
</script>
