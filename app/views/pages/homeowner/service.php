<?php
$serviceTypes = [
    1 => 'Inspection',
    2 => 'Repair',
    3 => 'Cleaning',
    4 => 'Maintenance',
    5 => 'Troubleshooting'
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
                                <?php foreach ($data['serviceHistory'] as $record): ?>
                                    <tr>
                                        <td class="text-sm font-semibold"><?php echo htmlspecialchars($record->task_id); ?>
                                        </td>
                                        <td class="text-sm"><?php echo htmlspecialchars($record->request_date); ?></td>
                                        <td class="text-sm">
                                            <?php
                                            $serviceTypes = [
                                                1 => 'Inspection',
                                                2 => 'Repair',
                                                3 => 'Cleaning',
                                                4 => 'Maintenance',
                                                5 => 'Troubleshooting'
                                            ];
                                            echo $serviceTypes[$record->service_type] ?? 'Unknown';
                                            ?>
                                            ?>
                                        </td>
                                        <td class="text-sm">Solar System</td>
                                        <td class="text-sm">
                                            <?php echo (empty($record->agent_id)) ? 'Pending' : htmlspecialchars($record->agent_id); ?>
                                        </td>
                                        <td class="text-sm"><?php echo htmlspecialchars($record->service_description); ?>
                                        </td>
                                        <td class="text-sm">
                                            <?php if ($record->status === 'Completed'): ?>
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i>Completed
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">
                                                    <i
                                                        class="fas fa-clock mr-1"></i><?php echo htmlspecialchars($record->status); ?>
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