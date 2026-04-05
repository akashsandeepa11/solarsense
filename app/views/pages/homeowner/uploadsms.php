<!-- Link to custom CSS file for this page -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/uploadsms.css">

<div class="content-area">
    <!-- Page Header -->
    <?php
    $pageHeaderConfig = [
        'title' => 'Upload CEB Message',
        'description' => 'Import your electricity bill SMS to track consumption and optimize your solar system',
        'show_back' => true,
        'back_url' => URLROOT . '/homeowner/dashboard',
        'back_label' => 'Back to Dashboard'
    ];
    $config = $pageHeaderConfig;
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Left Column - Upload Form -->
        <div class="col-lg-8">
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-6">Import CEB Bill Message</h3>

                    <form id="smsUploadForm" action="<?php echo URLROOT ?>/homeowner/dashboard/uploadsms" method="POST" novalidate enctype="multipart/form-data">
                        <!-- SMS Content Textarea -->
                        <div class="mb-4">
                            <?php
                            $sampleSMS = "A/C No: 0510021204 (D1-NET MET)\nMR.V.S. RAMANAYAKA\n\nB/F: Rs. -3,630.80\nPayments: Rs. 0.00\nOutstanding: Rs. -3,630.80 by 2025-01-28\n\nReading Date: 2025-02-05 (438)\nB/F Units: 3474\nReadings: 43015(E), 39508(I)\nPrv. Readings: 42451(E), 38976(I)\nConsumption: 564 Unit\nCharge: Rs. 87.50\nSSC Levy: Rs. 2.24\nMonthly Bill: Rs. 89.74\n\nTotal Due: Rs. -3,541.06\n\nC/F Units: 3506";

                            $textareaConfig = [
                                'id' => 'smsContent',
                                'name' => 'smsContent',
                                'label' => 'Paste CEB SMS Message',
                                'value' => $sampleSMS,
                                'error' => $data['smsContent_err'] ?? '',
                                'rows' => 6,
                                'placeholder' => 'Copy and paste the entire SMS message from CEB exactly as received',
                                'required' => true,
                                'editable' => true,
                                'wrapperClass' => 'mb-3'
                            ];
                            $config = $textareaConfig;
                            require APPROOT . '/views/inc/components/textarea_field.php';
                            ?>
                            <small class="text-secondary d-block mt-2">
                                <i class="fas fa-circle-info mr-1"></i>
                                Paste the entire SMS message exactly as you received it from CEB
                            </small>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 mt-6">
                            <button type="submit" class="btn btn-primary rounded-lg">
                                <i class="fas fa-cloud-upload-alt mr-2"></i>Upload Message
                            </button>
                            <button type="reset" class="btn btn-secondary rounded-lg">
                                <i class="fas fa-redo mr-2"></i>Clear
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Instructions & Info -->
        <div class="col-lg-4">
            <!-- Instructions Card -->
            <div class="card shadow-lg rounded-xl mb-4">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold mb-4">
                        How to Upload
                    </h3>
                    <ol class="list-steps">
                        <li>Open the SMS from CEB on your phone</li>
                        <li>Select and copy the entire message</li>
                        <li>Paste it in the text box</li>
                        <li>Click "Upload Message"</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Uploads Section -->
    <div class="row mt-6">
        <div class="col-12">
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-4">Recent Uploads</h3>

                    <?php if (!empty($data['recentUploads'])): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-sm font-semibold text-secondary">Reading Date</th>
                                        <th class="text-sm font-semibold text-secondary">Export Reading</th>
                                        <th class="text-sm font-semibold text-secondary">Import Reading</th>
                                        <th class="text-sm font-semibold text-secondary">Consumption</th>
                                        <th class="text-sm font-semibold text-secondary">Bill Amount</th>
                                        <!-- <th class="text-sm font-semibold text-secondary">Status</th>
                                        <th class="text-sm font-semibold text-secondary">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['recentUploads'] as $upload): ?>
                                        <tr>
                                            <td class="text-sm">
                                                <?php echo htmlspecialchars($upload->reading_date); ?>
                                            </td>
                                            <td class="text-sm">
                                                <?php echo htmlspecialchars($upload->export_reading); ?>
                                            </td>
                                            <td class="text-sm">
                                                <?php echo htmlspecialchars($upload->import_reading); ?>
                                            </td>
                                            <td class="text-sm">
                                                <?php echo htmlspecialchars($upload->consumption_units); ?>
                                            </td>
                                            <td class="text-sm font-semibold">
                                                Rs. <?php echo htmlspecialchars($upload->monthly_bill); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-6">
                            <i class="fas fa-inbox text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                            <p class="text-secondary">No messages uploaded yet. Start by uploading your first CEB message!
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>