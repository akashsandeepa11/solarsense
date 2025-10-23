<?php
// --- PHP Setup for Super Admin Dashboard ---

// Platform Overview Cards
$summary_cards = [
    ['label' => 'Total Installer Companies', 'value' => '24', 'icon' => 'fas fa-building', 'color' => 'primary'],
    ['label' => 'Pending Verifications', 'value' => 5, 'icon' => 'fas fa-clock', 'color' => 'warning'],
    ['label' => 'Total Platform Users', 'value' => '1,847', 'icon' => 'fas fa-users', 'color' => 'success'],
    ['label' => 'Active Support Tickets', 'value' => 12, 'icon' => 'fas fa-ticket-alt', 'color' => 'accent'],
    ['label' => 'Verified Companies', 'value' => 19, 'icon' => 'fas fa-check-circle', 'color' => 'success'],
    ['label' => 'Monthly Platform Revenue', 'value' => 'LKR 3.8M', 'icon' => 'fas fa-coins', 'color' => 'success'],
];

// Recent Verification Requests
$verification_requests = [
    [
        'id' => 1,
        'company_name' => 'SolarTech Solutions (Pvt) Ltd',
        'contact_person' => 'Kamal Perera',
        'email' => 'kamal@solartech.lk',
        'submitted_date' => '2025-10-23',
        'district' => 'Colombo',
        'status' => 'pending'
    ],
    [
        'id' => 2,
        'company_name' => 'GreenEnergy Systems',
        'contact_person' => 'Nimal Fernando',
        'email' => 'nimal@greenenergy.lk',
        'submitted_date' => '2025-10-22',
        'district' => 'Kandy',
        'status' => 'pending'
    ],
    [
        'id' => 3,
        'company_name' => 'EcoPower Installations',
        'contact_person' => 'Saman Silva',
        'email' => 'saman@ecopower.lk',
        'submitted_date' => '2025-10-21',
        'district' => 'Gampaha',
        'status' => 'under_review'
    ]
];

// Platform Activity Statistics
$activity_stats = [
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    'new_companies' => [1, 2, 1, 3, 2, 4, 3, 2, 3, 5],
    'new_users' => [45, 67, 89, 123, 145, 167, 189, 201, 223, 247]
];

// High-Priority Alerts
$alerts = [
    ['client' => 'SolarTech Solutions', 'issue' => 'Verification documents pending review', 'priority' => 'high'],
    ['client' => 'GreenEnergy Systems', 'issue' => 'Support ticket escalated - urgent', 'priority' => 'high'],
    ['client' => 'EcoPower Installations', 'issue' => 'Account suspension appeal submitted', 'priority' => 'medium'],
];

// Recent System Activities
$recent_activities = [
    [
        'type' => 'company_approved',
        'message' => 'New company "BrightSolar Ltd" approved and activated',
        'timestamp' => '2 hours ago',
        'icon' => 'fas fa-check-circle',
        'color' => 'success'
    ],
    [
        'type' => 'verification_submitted',
        'message' => 'New verification request from "SolarTech Solutions"',
        'timestamp' => '4 hours ago',
        'icon' => 'fas fa-file-alt',
        'color' => 'info'
    ],
    [
        'type' => 'complaint_resolved',
        'message' => 'Support ticket #1847 marked as resolved',
        'timestamp' => '6 hours ago',
        'icon' => 'fas fa-clipboard-check',
        'color' => 'primary'
    ],
    [
        'type' => 'company_suspended',
        'message' => 'Company "XYZ Installations" temporarily suspended',
        'timestamp' => '1 day ago',
        'icon' => 'fas fa-pause-circle',
        'color' => 'warning'
    ]
];

// District-wise Company Distribution
$district_distribution = [
    ['district' => 'Colombo', 'count' => 8],
    ['district' => 'Gampaha', 'count' => 5],
    ['district' => 'Kandy', 'count' => 4],
    ['district' => 'Galle', 'count' => 3],
    ['district' => 'Kurunegala', 'count' => 2],
    ['district' => 'Others', 'count' => 2]
];

// Platform Growth Stats
$platform_growth = [
    'labels' => ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    'data' => [18, 19, 20, 21, 22, 24]
];

// User Registration Stats
$user_registration = [
    'labels' => ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    'data' => [1420, 1535, 1648, 1721, 1789, 1847]
];
?>

<link rel="stylesheet" href="<?php echo URLROOT?>/public/css/layouts/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-area"  style="padding: 1.5rem;">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Super Admin Dashboard',
        'description' => 'Platform management and monitoring overview',
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
        <div class="col-lg-8">
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
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold mb-4">
                        <i class="fas fa-exclamation-circle text-error mr-2"></i>Priority Alerts
                    </h3>
                    <?php if(!empty($alerts)): ?>
                        <?php foreach($alerts as $alert): ?>
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
            </div>

            <!-- Charts Row -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-lg rounded-xl">
                        <div class="card-body">
                            <h3 class="card-title text-lg font-semibold mb-4">Total Registered Users</h3>
                            <div class="chart-container" style="height: 250px;">
                                <canvas id="userRegistrationChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-lg rounded-xl">
                        <div class="card-body">
                            <h3 class="card-title text-lg font-semibold mb-4">Company Distribution</h3>
                            <div class="chart-container" style="height: 250px;">
                                <canvas id="districtChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Pending Verifications -->
            <div class="card shadow-lg rounded-xl mb-6">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h3 class="card-title text-xl font-semibold">Pending Verifications</h3>
                        <a href="<?php echo URLROOT; ?>/superadmin/verification" class="btn btn-sm btn-primary" style="padding: 0.5rem 1rem; background-color: #fe9630; color: white; text-decoration: none; border-radius: 0.375rem; font-size: 0.875rem;">
                            View All
                        </a>
                    </div>
                    
                    <?php foreach($verification_requests as $request): ?>
                    <div class="performer-item d-flex justify-between align-center py-3 px-3" style="background: rgba(254, 150, 48, 0.05); border-radius: 0.5rem; margin-bottom: 0.75rem;">
                        <div style="flex: 1;">
                            <div class="font-semibold text-sm"><?php echo htmlspecialchars($request['company_name']); ?></div>
                            <div class="text-secondary" style="font-size: 0.75rem;">
                                <i class="fas fa-user mr-1"></i><?php echo htmlspecialchars($request['contact_person']); ?>
                            </div>
                            <div class="text-secondary" style="font-size: 0.75rem;">
                                <i class="fas fa-map-marker-alt mr-1"></i><?php echo htmlspecialchars($request['district']); ?>
                            </div>
                        </div>
                        <a href="<?php echo URLROOT; ?>/superadmin/verification" class="btn btn-sm" style="background-color: #fe9630; color: white; padding: 0.35rem 0.75rem; font-size: 0.75rem;">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold mb-4">
                        <i class="fas fa-history text-primary mr-2"></i>Recent Activities
                    </h3>
                    <?php foreach($recent_activities as $activity): ?>
                    <div class="d-flex align-center gap-3 py-3" style="border-bottom: 1px solid #e5e7eb;">
                        <div class="stat-icon" style="width: 40px; height: 40px; font-size: 1rem; background-color: <?php 
                            echo $activity['color'] === 'success' ? '#22c55e' : 
                                ($activity['color'] === 'info' ? '#00bcd4' : 
                                ($activity['color'] === 'warning' ? '#f59e0b' : '#fe9630')); 
                        ?>;">
                            <i class="<?php echo $activity['icon']; ?>"></i>
                        </div>
                        <div style="flex: 1;">
                            <p class="mb-1 text-sm" style="margin: 0; line-height: 1.4;"><?php echo htmlspecialchars($activity['message']); ?></p>
                            <small class="text-secondary" style="font-size: 0.75rem;">
                                <i class="fas fa-clock mr-1"></i><?php echo htmlspecialchars($activity['timestamp']); ?>
                            </small>
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

    // Platform Growth Chart (Line)
    const platformGrowthCtx = document.getElementById('platformGrowthChart');
    if (platformGrowthCtx) {
        new Chart(platformGrowthCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($platform_growth['labels']); ?>,
                datasets: [{
                    label: 'Total Companies',
                    data: <?php echo json_encode($platform_growth['data']); ?>,
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
    const userRegistrationCtx = document.getElementById('userRegistrationChart');
    if (userRegistrationCtx) {
        new Chart(userRegistrationCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($user_registration['labels']); ?>,
                datasets: [{
                    label: 'Total Users',
                    data: <?php echo json_encode($user_registration['data']); ?>,
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
        const districtData = <?php echo json_encode(array_column($district_distribution, 'count')); ?>;
        const districtLabels = <?php echo json_encode(array_column($district_distribution, 'district')); ?>;
        
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
