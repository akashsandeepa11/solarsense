    <?php
    // --- PHP Setup for Dummy Data ---

    // Summary Card Data
    $summary_cards = [
        ['label' => 'Total Clients', 'value' => '128', 'icon' => 'fas fa-users', 'color' => 'primary'],
        ['label' => 'Systems with Active Faults', 'value' => '4', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'error'],
        ['label' => 'Pending Maintenance', 'value' => '9', 'icon' => 'fas fa-wrench', 'color' => 'warning'],
        ['label' => 'Services Completed (Month)', 'value' => '23', 'icon' => 'fas fa-check-circle', 'color' => 'success']
    ];

    // Client Table Data
    $clients = [
        ['name' => 'John Doe', 'location' => 'Colombo', 'size' => 5.5, 'health' => 'Healthy', 'performance' => 102, 'last_upload' => '2025-08-20 09:45'],
        ['name' => 'Jane Smith', 'location' => 'Kandy', 'size' => 4.2, 'health' => 'Healthy', 'performance' => 99, 'last_upload' => '2025-08-20 10:15'],
        ['name' => 'Kamal Perera', 'location' => 'Galle', 'size' => 10.0, 'health' => 'Fault', 'performance' => 0, 'last_upload' => '2025-08-18 14:30'],
        ['name' => 'Nimali Silva', 'location' => 'Jaffna', 'size' => 3.0, 'health' => 'Underperforming', 'performance' => 85, 'last_upload' => '2025-08-20 08:30'],
        ['name' => 'David Miller', 'location' => 'Matara', 'size' => 7.8, 'health' => 'Healthy', 'performance' => 105, 'last_upload' => '2025-08-20 10:05'],
        ['name' => 'Fatima Rizvi', 'location' => 'Trincomalee', 'size' => 5.0, 'health' => 'Healthy', 'performance' => 98, 'last_upload' => '2025-08-20 09:55'],
        ['name' => 'Suresh Kumar', 'location' => 'Negombo', 'size' => 6.5, 'health' => 'Fault', 'performance' => 15, 'last_upload' => '2025-08-19 20:00'],
    ];

    // Function to determine the status dot color
    function getStatusClass($health) {
        switch ($health) {
            case 'Healthy':
                return 'bg-success';
            case 'Underperforming':
                return 'bg-warning';
            case 'Fault':
                return 'bg-error';
            default:
                return 'bg-secondary';
        }
    }
    ?>

    <link rel="stylesheet" href="<?php echo URLROOT?>/public/css/pages/installer/fleet_dashboard.css">


    <div class="container-fluid p-8">
        <!-- Page Header -->
        <div class="d-flex justify-between align-center mb-6">
            <div>
                <h1 class="text-4xl font-bold">Fleet Dashboard</h1>
                <p class="text-secondary">Overview of your client systems.</p>
            </div>
            <a href="<?php URLROOT?>/solarsense/installer/add_customer" class="btn btn-primary btn-lg rounded-lg text-decoration-none">
                <i class="fas fa-plus mr-2"></i> Add Customer
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <?php foreach ($summary_cards as $card): ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-md rounded-xl">
                        <div class="card-body d-flex align-center">
                            <div class="summary-card-icon d-flex align-center justify-center rounded-full mr-4 bg-<?php echo $card['color']; ?>">
                                <i class="<?php echo $card['icon']; ?>"></i>
                            </div>
                            <div>
                                <div class="text-4xl font-bold"><?php echo $card['value']; ?></div>
                                <div class="text-secondary"><?php echo $card['label']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Client List Table -->
        <div class="card shadow-lg rounded-xl">
            <div class="card-body">
                <h3 class="card-title text-2xl font-semibold mb-4">All Clients</h3>
                <div class="table-responsive">
                    <table class="client-table w-100">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Location</th>
                                <th>System Size</th>
                                <th>System Health</th>
                                <th>Performance</th>
                                <th>Last SMS Upload</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($client['name']); ?></td>
                                    <td><?php echo htmlspecialchars($client['location']); ?></td>
                                    <td><?php echo htmlspecialchars($client['size']); ?> kWp</td>
                                    <td>
                                        <div class="d-flex align-center">
                                            <span class="status-dot <?php echo getStatusClass($client['health']); ?> mr-2"></span>
                                            <?php echo htmlspecialchars($client['health']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($client['performance']); ?>%</td>
                                    <td><?php echo htmlspecialchars($client['last_upload']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


                                