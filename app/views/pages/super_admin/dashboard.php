<?php
// --- PHP Setup for Super Admin Dashboard ---
$stats = $data['stats'];

$total_solar_companies = $stats->total_solar_companies ?? 0;
$pending_verifications = $stats->pending_verifications ?? 0;
$total_platform_users = $stats->total_platform_users ?? 0;

// Platform Overview Cards
$summary_cards = [
    ['label' => 'Total Solar Companies', 'value' => $total_solar_companies, 'icon' => 'fas fa-building', 'color' => 'primary'],
    ['label' => 'Pending Verifications', 'value' => $pending_verifications, 'icon' => 'fas fa-clock', 'color' => 'warning'],
    ['label' => 'Total Platform Users', 'value' => $total_platform_users, 'icon' => 'fas fa-users', 'color' => 'success'],
    ['label' => 'Active Support Tickets', 'value' => 12, 'icon' => 'fas fa-ticket-alt', 'color' => 'accent'],
    //['label' => 'Verified Companies', 'value' => 19, 'icon' => 'fas fa-check-circle', 'color' => 'success'],
    //['label' => 'Monthly Platform Revenue', 'value' => 'LKR 3.8M', 'icon' => 'fas fa-coins', 'color' => 'success'],
];

// Recent Verification Requests

$verification_requests_data = $data['verification_requests'] ?? [];

$verification_requests = [];

if (!empty($verification_requests_data)) {
    foreach ($verification_requests_data as $req) {
        $verification_requests[] = [
            'id' => $req->company_id ?? ($req->id ?? 0),
            'company_name' => $req->company_name ?? 'Unknown Company',
            'email' => $req->email ?? '',
            'district' => $req->district ?? 'Unknown'
        ];
    }
} else {
    // No pending verifications
    $verification_requests = [];
}

// Platform Activity Statistics
$activity_stats = [
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    'new_companies' => [1, 2, 1, 3, 2, 4, 3, 2, 3, 5],
    'new_users' => [45, 67, 89, 123, 145, 167, 189, 201, 223, 247]
];

// High-Priority Alerts
// $alerts = [
//     ['client' => 'SolarTech Solutions', 'issue' => 'Verification documents pending review', 'priority' => 'high'],
//     ['client' => 'GreenEnergy Systems', 'issue' => 'Support ticket escalated - urgent', 'priority' => 'high'],
//     ['client' => 'EcoPower Installations', 'issue' => 'Account suspension appeal submitted', 'priority' => 'medium'],
// ];



// District-wise Company Distribution

$district_labels = [];
$district_data = [];

if (!empty($data['company_district'])) {
    foreach ($data['company_district'] as $row) {
        $district_labels[] = $row->district;
        $district_data[] = $row->total;
    }
}

$growthLabels = [];
$growthData = [];

if (!empty($data['growth'])) {
    foreach ($data['growth'] as $row) {
        $growthLabels[] = $row->year;
        $growthData[] = $row->total;
    }
}


$user_type_labels = [];
$user_type_data = [];

if (!empty($data['user_type'])) {
    foreach ($data['user_type'] as $row) {
        $user_type_labels[] = $row->user_type;
        $user_type_data[] = $row->total;
    }
}

?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/layouts/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-area" style="padding: 1.5rem;">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Super Admin Dashboard',
        'description' => 'Platform management and monitoring overview',
        // 'buttons' => [
        //     [
        //         'label' => 'Generate Report',
        //         'url' => URLROOT . '/superadmin/reports/generate',
        //         'icon' => 'fas fa-file-alt',
        //         'class' => 'btn-primary'
        //     ]
        // ]
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Platform Summary Cards -->
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
        <div class="col-lg-7">
            <!-- Platform Growth Chart -->
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold mb-4">Platform Growth - Total Companies</h3>
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="platformGrowthChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- High-Priority Alerts -->
            <!-- <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold mb-4">
                        <i class="fas fa-exclamation-circle text-error mr-2"></i>Priority Alerts
                    </h3>
                    <?php if (!empty($alerts)): ?>
                        <?php foreach ($alerts as $alert): ?>
                        <div class="alert-item d-flex justify-between align-center py-3 border-bottom" style="border-bottom-color: #e5e7eb;">
                            <div class="d-flex align-center gap-3">
                                <i class="fas fa-exclamation-circle text-error text-xl"></i>
                                <div>
                                    <div class="font-semibold"><?php echo htmlspecialchars($alert['client']); ?></div>
                                    <div class="text-secondary text-sm"><?php echo htmlspecialchars($alert['issue']); ?></div>
                                </div>
                            </div>
                            <a href="#" class="btn btn-sm btn-secondary rounded-lg">View Details</a>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-secondary text-center py-4">No alerts at this time</p>
                    <?php endif; ?>
                </div>
            </div> -->

            <!-- Charts Row -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card shadow-lg rounded-xl mb-6">
                        <div class="card-body">
                            <h3 class="card-title text-lg font-semibold mb-4">Registered Users - Role Based</h3>
                            <div class="chart-container" style="height: 250px;">
                                <canvas id="userTypeChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-5">
            <!-- Pending Verifications -->
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h3 class="card-title text-xl font-semibold">Pending Verifications</h3>
                        <a href="<?php echo URLROOT; ?>/superadmin/verification" class="btn btn-sm btn-primary"
                            style="padding: 0.5rem 1rem; background-color: #fe9630; color: white; text-decoration: none; border-radius: 0.375rem; font-size: 0.875rem;">
                            View All
                        </a>
                    </div>

                    <?php if (!empty($verification_requests)): ?>
                        <?php foreach ($verification_requests as $request): ?>
                            <div class="performer-item d-flex justify-between align-center py-3 px-3"
                                style="background: rgba(254, 150, 48, 0.05); border-radius: 0.5rem; margin-bottom: 0.75rem;">
                                <div style="flex: 1;">
                                    <div class="font-semibold text-sm"><?php echo htmlspecialchars($request['company_name']); ?>
                                    </div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">
                                        <i class="fas fa-user mr-1"></i><?php echo htmlspecialchars($request['email']); ?>
                                    </div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">
                                        <i
                                            class="fas fa-map-marker-alt mr-1"></i><?php echo htmlspecialchars($request['district']); ?>
                                    </div>
                                </div>
                                <a href="<?php echo URLROOT; ?>/superadmin/verification" class="btn btn-sm"
                                    style="background-color: #fe9630; color: white; padding: 0.35rem 0.75rem; font-size: 0.75rem;">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-secondary text-center py-4">No pending verifications</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mb-4">
                <div class="card shadow-lg rounded-xl">
                    <div class="card-body">
                        <h3 class="card-title text-lg font-semibold mb-4">Company District Distribution</h3>
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="districtChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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

        // Platform Growth Chart (Line)
        const platformGrowthCtx = document.getElementById('platformGrowthChart');
        if (platformGrowthCtx) {
            new Chart(platformGrowthCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($growthLabels); ?>,
                    datasets: [{
                        label: 'Total Companies (Yearly)',
                        data: <?php echo json_encode($growthData); ?>,
                        backgroundColor: 'rgba(254, 150, 48, 0.1)',
                        borderColor: 'rgba(254, 150, 48, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(254, 150, 48, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    ...chartOptions,
                    scales: {
                        y: { beginAtZero: false, grid: { color: '#e5e7eb' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // User Registration Chart (Line)
        const userTypeCtx = document.getElementById('userTypeChart');
        if (userTypeCtx) {
            new Chart(userTypeCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($user_type_labels); ?>,
                    datasets: [{
                        label: 'User Types',
                        data: <?php echo json_encode($user_type_data); ?>,
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    ...chartOptions,
                    scales: {
                        y: { beginAtZero: false, grid: { color: '#e5e7eb' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // District Distribution Chart (Doughnut)
        const districtCtx = document.getElementById('districtChart');
        if (districtCtx) {
            const districtData = <?php echo json_encode($district_data); ?>;
            const districtLabels = <?php echo json_encode($district_labels); ?>;

            new Chart(districtCtx, {
                type: 'doughnut',
                data: {
                    labels: districtLabels,
                    datasets: [{
                        data: districtData,
                        backgroundColor: [
                            '#fe9630',
                            '#3b82f6',
                            '#22c55e',
                            '#f59e0b',
                            '#8b5cf6',
                            '#6b7280'
                        ],
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