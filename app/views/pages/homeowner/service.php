<?php
// --- PHP Setup for Service Data ---
// Service Types
$serviceTypes = [
    'inspection' => 'Inspection',
    'repair' => 'Repair',
    'cleaning' => 'Cleaning',
    'maintenance' => 'Maintenance',
    'troubleshooting' => 'Troubleshooting'
];

// Service History Data
$serviceHistory = [
    [
        'id' => 'SR-001',
        'date' => '2025-08-10',
        'service_type' => 'Cleaning',
        'component' => 'Solar Panels',
        'technician' => 'John Smith',
        'remarks' => 'Cleaned and inspected all panels',
        'status' => 'completed'
    ],
    [
        'id' => 'SR-002',
        'date' => '2025-07-15',
        'service_type' => 'Repair',
        'component' => 'Inverter',
        'technician' => 'Mike Johnson',
        'remarks' => 'Replaced faulty capacitor',
        'status' => 'completed'
    ],
    [
        'id' => 'SR-003',
        'date' => '2025-07-05',
        'service_type' => 'Inspection',
        'component' => 'Wiring & Connections',
        'technician' => 'Sarah Davis',
        'remarks' => 'Routine inspection completed',
        'status' => 'completed'
    ],
    [
        'id' => 'SR-004',
        'date' => '2025-06-20',
        'service_type' => 'Maintenance',
        'component' => 'Battery Bank',
        'technician' => 'Robert Brown',
        'remarks' => 'Battery health check and cleaning',
        'status' => 'completed'
    ]
];

?>

<!-- Link to custom CSS file for this page -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/service.css">

<div class="content-area">
    <!-- Page Header -->
    <?php
    $pageHeaderConfig = [
        'title' => 'Request Service',
        'description' => 'Request maintenance, repairs, or inspections for your solar system',
    ];
    $config = $pageHeaderConfig;
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <!-- Main Grid: Request Form & Service Info -->
    <div class="row">
        <!-- Left Column - Service Request Form -->
        <div class="col-lg-8">
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-6">Submit Service Request</h3>
                    
                    <form id="serviceRequestForm" method="POST">
                        <!-- Service Type -->
                        <div class="mb-4">
                            <?php
                            $selectConfig = [
                                'id' => 'serviceType',
                                'name' => 'service_type',
                                'label' => 'Service Type',
                                'value' => '',
                                'options' => $serviceTypes,
                                'required' => true,
                                'wrapperClass' => 'mb-3'
                            ];
                            require APPROOT . '/views/inc/components/select_field.php';
                            ?>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <?php
                            $textareaConfig = [
                                'id' => 'serviceDescription',
                                'name' => 'service_description',
                                'label' => 'Describe the Issue',
                                'value' => '',
                                'rows' => 5,
                                'placeholder' => 'Provide detailed description of the issue or service needed',
                                'required' => true,
                                'wrapperClass' => 'mb-3'
                            ];
                            require APPROOT . '/views/inc/components/textarea_field.php';
                            ?>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-lg">
                                <i class="fas fa-paper-plane mr-2"></i>Submit Request
                            </button>
                            <button type="reset" class="btn btn-secondary rounded-lg">
                                <i class="fas fa-redo mr-2"></i>Clear
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Service Information -->
        <div class="col-lg-4">
            <!-- Response Time Card -->
            <div class="card shadow-lg rounded-xl mb-4">
                <div class="card-body">
                    <h3 class="card-title text-lg font-semibold mb-4">
                        <i class="fas fa-clock text-primary mr-2"></i>Service Details
                    </h3>
                    <div class="info-item mb-3 pb-3 border-bottom">
                        <p class="text-secondary text-sm mb-1">Response Time</p>
                        <p class="text-lg font-semibold text-primary">24-48 Hours</p>
                    </div>
                    <div class="info-item mb-3 pb-3 border-bottom">
                        <p class="text-secondary text-sm mb-1">Service Hours</p>
                        <p class="text-lg font-semibold">Mon - Sat, 8AM - 6PM</p>
                    </div>
                    <div class="info-item">
                        <p class="text-secondary text-sm mb-1">Support Contact</p>
                        <p class="text-lg font-semibold">
                            <i class="fas fa-phone text-success mr-1"></i>
                            076 416 4347
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-lg font-semibold mb-4">
                        <i class="fas fa-headset text-success mr-2"></i>Need Help?
                    </h3>
                    <div class="d-flex flex-column gap-2">
                        <a href="#" class="btn btn-secondary btn-sm w-100 rounded-lg">
                            <i class="fas fa-phone mr-2"></i>Call Support
                        </a>
                        <a href="#" class="btn btn-secondary btn-sm w-100 rounded-lg">
                            <i class="fas fa-envelope mr-2"></i>Email Us
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service History Section -->
    <div class="row mt-6">
        <div class="col-12">
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-6">Service History</h3>
                    
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-sm font-semibold text-secondary">Request ID</th>
                                    <th class="text-sm font-semibold text-secondary">Date</th>
                                    <th class="text-sm font-semibold text-secondary">Service Type</th>
                                    <th class="text-sm font-semibold text-secondary">Component</th>
                                    <th class="text-sm font-semibold text-secondary">Technician</th>
                                    <th class="text-sm font-semibold text-secondary">Remarks</th>
                                    <th class="text-sm font-semibold text-secondary">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($serviceHistory as $record): ?>
                                    <tr>
                                        <td class="text-sm font-semibold"><?php echo htmlspecialchars($record['id']); ?></td>
                                        <td class="text-sm"><?php echo htmlspecialchars($record['date']); ?></td>
                                        <td class="text-sm"><?php echo htmlspecialchars($record['service_type']); ?></td>
                                        <td class="text-sm"><?php echo htmlspecialchars($record['component']); ?></td>
                                        <td class="text-sm"><?php echo htmlspecialchars($record['technician']); ?></td>
                                        <td class="text-sm"><?php echo htmlspecialchars($record['remarks']); ?></td>
                                        <td class="text-sm">
                                            <?php if ($record['status'] === 'completed'): ?>
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i>Completed
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock mr-1"></i>Pending
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceForm = document.getElementById('serviceRequestForm');
    
    if (serviceForm) {
        serviceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const customerName = document.getElementById('customerName').value.trim();
            const serviceType = document.getElementById('serviceType').value;
            const description = document.getElementById('serviceDescription').value.trim();
            
            // Validate
            if (!customerName || !serviceType || !description) {
                alert('Please fill in all required fields');
                return;
            }
            
            // Show loading state
            const submitBtn = serviceForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
            submitBtn.disabled = true;
            
            // Simulate processing
            setTimeout(function() {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                alert('Service request submitted successfully! We will contact you within 24-48 hours.');
                serviceForm.reset();
            }, 1500);
        });
    }
});
</script>