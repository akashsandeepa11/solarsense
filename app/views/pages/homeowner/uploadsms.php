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

    <!-- Dummy Data for Recent Uploads -->
    <?php
    $recentUploads = [
        [
            'date' => '2025-02-05',
            'consumption' => '564 Units',
            'amount' => 'Rs. 89.74',
            'account' => 'A/C No: 0510021204',
            'name' => 'MR.V.S. RAMANAYAKA',
            'status' => 'Processed'
        ],
        [
            'date' => '2025-01-05',
            'consumption' => '587 Units',
            'amount' => 'Rs. 95.20',
            'account' => 'A/C No: 0510021204',
            'name' => 'MR.V.S. RAMANAYAKA',
            'status' => 'Processed'
        ],
        [
            'date' => '2024-12-05',
            'consumption' => '543 Units',
            'amount' => 'Rs. 82.15',
            'account' => 'A/C No: 0510021204',
            'name' => 'MR.V.S. RAMANAYAKA',
            'status' => 'Processed'
        ],
        [
            'date' => '2024-11-05',
            'consumption' => '612 Units',
            'amount' => 'Rs. 104.50',
            'account' => 'A/C No: 0510021204',
            'name' => 'MR.V.S. RAMANAYAKA',
            'status' => 'Processed'
        ],
        [
            'date' => '2024-10-05',
            'consumption' => '598 Units',
            'amount' => 'Rs. 99.75',
            'account' => 'A/C No: 0510021204',
            'name' => 'MR.V.S. RAMANAYAKA',
            'status' => 'Processed'
        ]
    ];
    ?>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Left Column - Upload Form -->
        <div class="col-lg-8">
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-6">Import CEB Bill Message</h3>
                    
                    <form id="smsUploadForm" method="POST">
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
                        <i class="fas fa-question-circle text-primary mr-2"></i>How to Upload
                    </h3>
                    <ol class="list-steps">
                        <li>Open the SMS from CEB on your phone</li>
                        <li>Select and copy the entire message</li>
                        <li>Paste it in the text box</li>
                        <li>Click "Upload Message"</li>
                    </ol>
                </div>
            </div>

            <!-- Benefits Card -->
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold mb-4">
                        <i class="fas fa-lightbulb text-warning mr-2"></i>Benefits
                    </h3>
                    <ul class="benefit-list">
                        <li>
                            <i class="fas fa-chart-line text-success mr-2"></i>
                            <span>Track your consumption patterns</span>
                        </li>
                        <li>
                            <i class="fas fa-chart-pie text-accent mr-2"></i>
                            <span>Compare actual vs. expected</span>
                        </li>
                        <li>
                            <i class="fas fa-coins text-primary mr-2"></i>
                            <span>Optimize your savings</span>
                        </li>
                        <li>
                            <i class="fas fa-bell text-warning mr-2"></i>
                            <span>Get personalized alerts</span>
                        </li>
                    </ul>
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
                    
                    <?php if (isset($recentUploads) && !empty($recentUploads)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-sm font-semibold text-secondary">Date</th>
                                        <th class="text-sm font-semibold text-secondary">Consumption</th>
                                        <th class="text-sm font-semibold text-secondary">Bill Amount</th>
                                        <th class="text-sm font-semibold text-secondary">Status</th>
                                        <th class="text-sm font-semibold text-secondary">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentUploads as $upload): ?>
                                        <tr>
                                            <td class="text-sm"><?php echo htmlspecialchars($upload['date']); ?></td>
                                            <td class="text-sm"><?php echo htmlspecialchars($upload['consumption']); ?></td>
                                            <td class="text-sm font-semibold"><?php echo htmlspecialchars($upload['amount']); ?></td>
                                            <td class="text-sm">
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i>Processed
                                                </span>
                                            </td>
                                            <td class="text-sm">
                                                <a href="#" class="text-primary font-semibold">
                                                    <i class="fas fa-eye mr-1"></i>View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-6">
                            <i class="fas fa-inbox text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                            <p class="text-secondary">No messages uploaded yet. Start by uploading your first CEB message!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const smsUploadForm = document.getElementById('smsUploadForm');
    
    if (smsUploadForm) {
        smsUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get the message content
            const smsContent = document.getElementById('smsContent').value.trim();
            
            if (!smsContent) {
                alert('Please paste the CEB SMS message');
                return;
            }
            
            // Show loading state
            const submitBtn = smsUploadForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            submitBtn.disabled = true;
            
            // Simulate processing and show success
            setTimeout(function() {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                alert('Message uploaded successfully!');
                smsUploadForm.reset();
            }, 1500);
        });
    }
});