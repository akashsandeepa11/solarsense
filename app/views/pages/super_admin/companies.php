<?php
// --- PHP Setup for Dummy Data ---

// Summary Cards (example values)
$summary_cards = [
    ['label' => 'Total Companies', 'value' => '6', 'icon' => 'fas fa-building', 'color' => 'primary'],
    ['label' => 'Verified Companies', 'value' => '3', 'icon' => 'fas fa-check-circle', 'color' => 'success'],
    ['label' => 'Pending Verifications', 'value' => '3', 'icon' => 'fas fa-hourglass-half', 'color' => 'warning']
];

// Verified company data from registration
$companies = [
    [
        "companyName" => "SunTech Installers",
        "email" => "info@suntech.com",
        "contactNumber" => "0771234567",
        "physicalAddress" => "45 Solar Avenue, Colombo",
        "submittedDate" => "2025-10-19",
        "status" => "pending"
    ],
    [
        "companyName" => "EcoWatt Solutions",
        "email" => "support@ecowatt.lk",
        "contactNumber" => "0769876543",
        "physicalAddress" => "22 Green Park Road, Kandy",
        "submittedDate" => "2025-10-18",
        "status" => "verified"
    ],
    [
        "companyName" => "SolarRise Energy",
        "email" => "contact@solarrise.lk",
        "contactNumber" => "0714456789",
        "physicalAddress" => "10 Lighthouse Road, Galle",
        "submittedDate" => "2025-10-17",
        "status" => "pending"
    ],
    [
        "companyName" => "GreenVolt Installers",
        "email" => "info@greenvolt.com",
        "contactNumber" => "0782345678",
        "physicalAddress" => "88 Powerline Street, Negombo",
        "submittedDate" => "2025-10-15",
        "status" => "verified"
    ],
    [
        "companyName" => "SunPeak Engineering",
        "email" => "service@sunpeak.lk",
        "contactNumber" => "0759988776",
        "physicalAddress" => "5 Tech Park Avenue, Kurunegala",
        "submittedDate" => "2025-10-16",
        "status" => "pending"
    ],
    [
        "companyName" => "BrightRay Solutions",
        "email" => "admin@brightray.com",
        "contactNumber" => "0701234987",
        "physicalAddress" => "32 Central Road, Matara",
        "submittedDate" => "2025-10-12",
        "status" => "verified"
    ]
];

// Function to color the status
function getStatusBadge($status) {
    switch ($status) {
        case 'verified':
            return '<span class="badge bg-success text-white px-3 py-1 rounded-lg">Verified</span>';
        case 'pending':
            return '<span class="badge bg-warning text-dark px-3 py-1 rounded-lg">Pending</span>';
        case 'rejected':
            return '<span class="badge bg-error text-white px-3 py-1 rounded-lg">Rejected</span>';
        default:
            return '<span class="badge bg-secondary text-white px-3 py-1 rounded-lg">Unknown</span>';
    }
}
?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/pages/installer/fleet_dashboard.css">

<div class="container-fluid p-8">
    <!-- Page Header -->
    <div class="d-flex justify-between align-center mb-6">
        <div>
            <h1 class="text-4xl font-bold">Company Fleet Dashboard</h1>
            <p class="text-secondary">Overview of all registered and verified installation companies.</p>
        </div>
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

    <!-- Company List Table -->
    <div class="card shadow-lg rounded-xl mt-6">
        <div class="card-body">
            <h3 class="card-title text-2xl font-semibold mb-4">All Companies</h3>
            <div class="table-responsive">
                <table class="client-table w-100">
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Registered Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($companies as $company): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($company['companyName']); ?></td>
                                <td><?php echo htmlspecialchars($company['email']); ?></td>
                                <td><?php echo htmlspecialchars($company['contactNumber']); ?></td>
                                <td><?php echo htmlspecialchars($company['physicalAddress']); ?></td>
                                <td><?php echo htmlspecialchars($company['submittedDate']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
