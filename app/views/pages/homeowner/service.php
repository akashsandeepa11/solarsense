<?php


$serviceTypes = $data['serviceTypes'];
$history = $data['serviceHistory'];


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

    <div class="row mt-6">
        <div class="col-12">
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>


    

    <!-- Service History Section -->
    <div class="row mt-6">
        <div class="col-12">
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <div class="d-flex align-center justify-between mb-6">
                        <h3 class="card-title text-2xl font-semibold mb-0">Service History</h3>
                        <?php
                        $config = [
                            'table_selector' => '.service-history-table',
                            'report_title'   => 'Service History',
                            'report_subtitle'=> 'A complete record of your solar system service requests',
                            'columns'        => ['Service ID', 'Request Date', 'Service Type', 'Technician', 'Status'],
                            'btn_id'         => 'btn-download-service-report',
                        ];
                        require APPROOT . '/views/inc/components/download_report_btn.php';
                        ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0 service-history-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-sm font-semibold text-secondary">Service ID</th>
                                    <th class="text-sm font-semibold text-secondary">RequestDate</th>
                                    <th class="text-sm font-semibold text-secondary">Service Type</th>
                                    <th class="text-sm font-semibold text-secondary">Technician</th>
                            
                                    <th class="text-sm font-semibold text-secondary">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php foreach ($data['serviceHistory'] as $record): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($record->task_id) ?></td>

                                            <td><?= htmlspecialchars($record->request_date) ?></td>

                                            <td><?= htmlspecialchars($record->service_type) ?></td>

                                            <!-- <td><?= htmlspecialchars($record->agent_name ?? 'Not Assigned') ?></td> -->

                                            <td>
                                                <?php if (!empty($record->agent_name)): ?>
                                                    <?= htmlspecialchars($record->agent_name) ?>
                                                <?php else: ?>
                                                    <span class="badge badge-success">Not Assigned</span>
                                                <?php endif; ?>
                                        </td>
                                            <td>
                                                <?php if ($record->status == 'Completed'): ?>
                                                    <span class="badge badge-success">Completed</span>
                                                <?php elseif ($record->status == 'In Progress'): ?>
                                                    <span class="badge badge-primary">In Progress</span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">Pending</span>
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

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>